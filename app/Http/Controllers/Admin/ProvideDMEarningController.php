<?php

namespace App\Http\Controllers\Admin;

use App\Models\DeliveryMan;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\ProvideDMEarning;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\DeliverymanPaymentExport;
use App\Models\Zone;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Enums\ViewPaths\Admin\DeliveryMan as DeliveryManViewPath;
use App\Enums\ViewPaths\Admin\RemoteConnections;
use App\Http\Controllers\RestAPI\RemoteAPiController;
use App\Models\DeliveryManWallet;
use App\Models\StcBulkPayouts;
use App\Models\StcDeliveryPayouts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ProvideDMEarningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];
        $provide_dm_earning = ProvideDMEarning::when(isset($key), function ($query) use ($key) {
            return $query->whereHas('delivery_man', function ($query) use ($key) {
                foreach ($key as $value) {
                    $query->where('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%");
                }
            });
        })->latest()->paginate(config('default_pagination'));
        return view('admin-views.deliveryman-earning-provide.index', compact('provide_dm_earning'));
    }
    private function getListView(Request $request): View
    {
        $zoneId = $request->query('zone_id', 'all');
        $deliveryMen = ProvideDMEarningController::getDeliveryManListWhere(
            zoneId: $zoneId,
            searchValue: $request['search'],
            filters: ['type' => 'zone_wise', 'application_status' => 'approved'],
            relations: ['zone'],
            dataLimit: config('default_pagination')
        );

        $zone = is_numeric($zoneId) ? Zone::where(['id' => $zoneId])->first() : null;

        return view(DeliveryManViewPath::EARNINGLIST[VIEW], compact('deliveryMen', 'zone'));
    }

    public function getDeliveryManListWhere(string $zoneId = 'all', string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $data = DeliveryMan::with($relations)

            ->whereHas('wallet', function ($query) {
                $query->where(DB::raw("CONCAT(`total_withdrawn`, '+', `pending_withdraw`, '+',`collected_cash`)"), '<', 'total_earning');
            })
            ->latest();

        if ($dataLimit == 'all') {
            return $data->get();
        }

        return $data->paginate($dataLimit);
    }
    public function earningList(?Request $request): View|Collection|LengthAwarePaginator|null
    {
        return $this->getListView($request);
    }

    public function stcPayouts(?Request $request): View|Collection|LengthAwarePaginator|null
    {

        return $this->getListstcPayoutsView($request);
    }

    public function stcPayoutsInquery(?Request $request): View|Collection|LengthAwarePaginator|null
    {

        $StcBulkPayouts = StcBulkPayouts::get();
        $data = RemoteAPiController::stcBulkPayoutsInquery($StcBulkPayouts);
        if (!$data['status'])
            Toastr::error($data['data']);
        else
            Toastr::success('stc Bulk Payout retrived successfuly');

        return $this->getListstcPayoutsView($request);
    }

    private function getListstcPayoutsView(Request $request): View|Collection|LengthAwarePaginator|null
    {
        $payouts = ProvideDMEarningController::getStcDeliveryPayoutsListWhere(
            relations: ['StcBulkPayouts'],
            dataLimit: config('default_pagination')
        );
        $zone = is_numeric(1) ? Zone::where(['id' => 1])->first() : null;

        return view(DeliveryManViewPath::STCPAYOUTS[VIEW], compact('payouts', 'zone'));
        // return view(DeliveryManViewPath::STCPAYOUTS[VIEW], compact('payouts'));
    }
    public function getStcDeliveryPayoutsListWhere(string $zoneId = 'all', string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $data = StcDeliveryPayouts::with($relations)->latest();

        if ($dataLimit == 'all') {
            return $data->get();
        }

        return $data->paginate($dataLimit);
    }
    public function payoutPaymentsEarningList(?Request $request)
    {
        $Payments = [];

        $DeliveryMans = DeliveryMan::with('wallet')->get();

        foreach ($DeliveryMans as  $DeliveryMan) {
            $amount = 0;

            $item = [];
            if (($DeliveryMan->wallet->total_withdrawn + $DeliveryMan->wallet->pending_withdraw + $DeliveryMan->wallet->collected_cash)
                < $DeliveryMan->wallet->total_earning
            ) {

                $amount = $DeliveryMan->wallet->total_earning - ($DeliveryMan->wallet->total_withdrawn + $DeliveryMan->wallet->pending_withdraw + $DeliveryMan->wallet->collected_cash);
                $item = [
                    'MobileNumber' => $DeliveryMan->phone,
                    'ItemReference' => rand(100, 999) . '-' . Str::random(5) . '-' . $DeliveryMan->phone,
                    'Amount' => $amount,
                ];

                $wallet = DeliveryManWallet::where('delivery_man_id', $DeliveryMan->id)->first();
                $wallet->pending_withdraw = $wallet->pending_withdraw + $amount;
                $wallet->total_earning = $wallet->total_earning - $amount;
                $wallet->save();

                array_push($Payments, $item);
            }
        }
        if (Count($Payments) > 0) {
            $payOutStatus = RemoteAPiController::stcBulkPayoutsDeliverymen($Payments, rand(100, 999) . '-' . Str::random(5));
            if ($payOutStatus['status']) {
                Toastr::success('stc Bulk Payout to Deliverymens successfuly');
                return back();
            } else
                return  redirect()->back()->withErrors($payOutStatus['data']);

            return redirect()->back(['errors' => $payOutStatus['data']]);
        }
        return  redirect()->back()->withErrors('no records of withdraw able balance');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deliveryman_id' => 'required',
            'method' => 'max:191',
            'ref' => 'max:191',
            'amount' => 'required|numeric|between:0.01,999999999999.99',
        ]);


        $dm = DeliveryMan::findOrFail($request['deliveryman_id']);

        $current_balance = $dm->wallet ? $dm->wallet->total_earning - $dm->wallet->total_withdrawn : 0;

        if (round($current_balance, 2) < round($request['amount'], 2)) {
            $validator->getMessageBag()->add('amount', 'Insufficient balance!');
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $provide_dm_earning = new ProvideDMEarning();

        $provide_dm_earning->delivery_man_id = $dm->id;
        $provide_dm_earning->method = $request['method'];
        $provide_dm_earning->ref = $request['ref'];
        $provide_dm_earning->amount = $request['amount'];

        try {
            DB::beginTransaction();
            $provide_dm_earning->save();
            $dm->wallet->increment('total_withdrawn', $request['amount']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e], 200);
        }

        return response()->json(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account_transaction = AccountTransaction::findOrFail($id);
        return view('admin-views.account.view', compact('account_transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DeliveryMan::where('id', $id)->delete();
        Toastr::success(translate('messages.provided_dm_earnings_removed'));
        return back();
    }

    public function dm_earning_list_export(Request $request)
    {
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];
        $dm_earnings = ProvideDMEarning::when(isset($key), function ($query) use ($key) {
            return $query->whereHas('delivery_man', function ($query) use ($key) {
                foreach ($key as $value) {
                    $query->where('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%");
                }
            });
        })->latest()->get();

        $data = [
            'dm_earnings' => $dm_earnings,
            'search' => $request->search ?? null,

        ];

        if ($request->type == 'excel') {
            return Excel::download(new DeliverymanPaymentExport($data), 'ProvideDMEarning.xlsx');
        } else if ($request->type == 'csv') {
            return Excel::download(new DeliverymanPaymentExport($data), 'ProvideDMEarning.csv');
        }
    }

    public function search_deliveryman_earning(Request $request)
    {
        $key = explode(' ', $request['search']);
        $provide_dm_earning = ProvideDMEarning::with('delivery_man')->whereHas('delivery_man', function ($query) use ($key) {
            foreach ($key as $value) {
                $query->where('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%");
            }
        })->get();

        return response()->json([
            'view' => view('admin-views.deliveryman-earning-provide.partials._table', compact('provide_dm_earning'))->render(),
            'total' => $provide_dm_earning->count()
        ]);
    }
}
