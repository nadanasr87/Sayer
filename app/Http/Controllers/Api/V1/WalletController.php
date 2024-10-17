<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\User;
use App\Models\WalletBonus;
use App\Models\WalletPayment;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Library\Payer;
use App\Traits\Payment;
use App\Library\Receiver;
use App\Library\Payment as PaymentInfo;
use App\Models\Order;

class WalletController extends Controller
{
    public function transactions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $paginator = WalletTransaction::where('user_id', $request->user()->id)
            ->when($request['type'] && $request['type'] == 'order', function ($query) {
                $query->whereIn('transaction_type', ['order_place', 'order_refund', 'partial_payment']);
            })
            ->when($request['type'] && $request['type'] == 'loyalty_point', function ($query) {
                $query->whereIn('transaction_type', ['loyalty_point']);
            })
            ->when($request['type'] && $request['type'] == 'add_fund', function ($query) {
                $query->whereIn('transaction_type', ['add_fund']);
            })
            ->when($request['type'] && $request['type'] == 'referrer', function ($query) {
                $query->whereIn('transaction_type', ['referrer']);
            })
            ->latest()->paginate($request->limit, ['*'], 'page', $request->offset);

        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request->limit,
            'offset' => $request->offset,
            'data' => $paginator->items()
        ];
        return response()->json($data, 200);
    }

    public function add_fund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $digital_payment = Helpers::get_business_settings('digital_payment');
        if ($digital_payment['status'] == 0) {
            return response()->json(['errors' => ['message' => 'digital_payment_is_disable']], 403);
        }

        $customer = User::find($request->user()->id);

        $wallet = new WalletPayment();
        $wallet->user_id = $customer->id;
        $wallet->amount = $request->amount;
        $wallet->payment_status = 'pending';
        $wallet->payment_method = $request->payment_method;
        $wallet->save();

        $wallet_amount = $request->amount;

        if (!isset($customer)) {
            return response()->json(['errors' => ['message' => 'Customer not found']], 403);
        }

        if (!isset($wallet_amount)) {
            return response()->json(['errors' => ['message' => 'Amount not found']], 403);
        }

        if (!$request->has('payment_method')) {
            return response()->json(['errors' => ['message' => 'Payment not found']], 403);
        }

        $payer = new Payer(
            $customer->f_name . ' ' . $customer->l_name,
            $customer->email,
            $customer->phone,
            ''
        );

        $currency = BusinessSetting::where(['key' => 'currency'])->first()->value;
        $additional_data = [
            'business_name' => BusinessSetting::where(['key' => 'business_name'])->first()?->value,
            'business_logo' => asset('storage/business') . '/' . BusinessSetting::where(['key' => 'logo'])->first()?->value
        ];
        $payment_info = new PaymentInfo(
            success_hook: 'wallet_success',
            failure_hook: 'wallet_failed',
            currency_code: $currency,
            payment_method: $request->payment_method,
            payment_platform: $request->payment_platform,
            payer_id: $customer->id,
            receiver_id: '100',
            additional_data: $additional_data,
            payment_amount: $wallet_amount,
            external_redirect_link: $request->has('callback') ? $request['callback'] : session('callback'),
            attribute: 'wallet_payments',
            attribute_id: $wallet->id
        );

        $receiver_info = new Receiver('receiver_name', 'example.png');

        $redirect_link = Payment::generate_link($payer, $payment_info, $receiver_info);

        $data = [
            'redirect_link' => $redirect_link,
            'id' => $wallet->id
        ];
        return response()->json($data, 200);
    }

    public function get_bonus()
    {
        $bonuses = WalletBonus::Active()->Running()->latest()->get();
        return response()->json($bonuses ?? [], 200);
    }


    public function getTelrPaymentStatus($ref, $id, $type)
    {
        $client = new \GuzzleHttp\Client();
        $payload = [
            'method' => 'check',
            'store' => 29981,
            'authkey' => 'mB4FG@HFDj^gbJ6H',
            'order' => [
                'ref' => $ref
            ]
        ];
        $response = $client->request('POST', 'https://secure.telr.com/gateway/order.json', [
            'body' => json_encode($payload),
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
        ]);
        $responseBody = $response->getBody();
        $responseBody = json_decode($responseBody, true);
        if ($type === 'wallet') {
            $data = WalletPayment::find($id);
            if ($responseBody['order']['amount'] >= $data->amount && $responseBody['order']['status']['code']) {
                $data->attribute_id = $data->id;
                $data->payment_method = 'telr';
                $data->payment_amount = $data->amount;
                $data->payer_id = $data->user_id;
                wallet_success($data);
            }
            $data2 = [
                'success' => 'true',
            ];
            return response()->json($data2, 200);
        } else if ($type === 'order') {
            $data = Order::find($id);
            $data->attribute_id = $data->id;
            $data->payment_method = 'telr';
            $data->payment_amount = $data->amount;
            $data->payer_id = $data->user_id;
            if ($responseBody['order']['amount'] >= $data->amount && $responseBody['order']['status']['code']) {
                order_place($data);
            }
            $data2 = [
                'success' => 'true',
            ];
            return response()->json($data2, 200);
        }
    }


    public function cancelPayment($id, $type)
    {
        if ($type === 'wallet') {
            $data = WalletPayment::find($id);
            $data->attribute_id = $data->id;
            $data->payment_method = 'telr';
            wallet_failed($data);
            $data2 = [
                'success' => 'true',
            ];
            return response()->json($data2, 200);
        } else {
        }
    }
}
