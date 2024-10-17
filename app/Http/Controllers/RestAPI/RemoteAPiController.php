<?php

namespace App\Http\Controllers\RestAPI;

use App\Enums\ViewPaths\Admin\RemoteConnections;
use App\Http\Controllers\Controller;
use App\Models\StcBulkPayouts;
use App\Models\StcDeliveryPayouts;
use App\Models\StcPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RemoteAPiController extends Controller
{
    public static function carsTypeList()
    {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['car-types'];

        $body =RemoteConnections::TAWSEEL['credential'];
        $api_response = Http::post($request_url, $body);
        $data = RemoteAPiController::callBack($api_response);

        return response()->json($data, 200);
    }

    public static function countryList()
    {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['countryList'];

        $body =  RemoteConnections::TAWSEEL['credential'];

        $api_response = Http::post(
            $request_url,
            $body

        );

        info($request_url);
        info($body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }

    public static function authoritiesList()
    {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['authorityList'];
        $body =  RemoteConnections::TAWSEEL['credential'];

        $api_response = Http::post($request_url, $body);

        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }
    public static function categoriesList()
    {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['categoriesList'];

        $body =  RemoteConnections::TAWSEEL['credential'];

        // try {

        $api_response = Http::post($request_url, $body);

        info($request_url);
        info($body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }

    public static function identityTypesList()
    {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['identities'];

        $body =  RemoteConnections::TAWSEEL['credential'];

        // try {

        $api_response = Http::post($request_url, $body);

        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }


    public static function paymentMethods()
    {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['payments'];

        $body =  RemoteConnections::TAWSEEL['credential'];

        // try {

        $api_response = Http::post($request_url, $body);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }

    public static function cityList($id)
    {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['cities'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "regionId" => $id

        ];

        $api_response = Http::post($request_url, $body);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }


    public static function CreateDriver(
        $identityTypeId,
        $idNumber,
        $dateOfBirth,
        $registrationDate,
        $mobile,
        $regionId,
        $carTypeId,
        $cityId,
        $carNumber,
        $vehicleSequenceNumber
    ) {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['CreateDriver'];


        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "driver" => [
                "identityTypeId" => $identityTypeId,
                "idNumber" => $idNumber,
                "dateOfBirth" => $dateOfBirth,
                "registrationDate" => $registrationDate,
                "mobile" => $mobile,
                "regionId" => $regionId,
                "carTypeId" => $carTypeId,
                "cityId" => $cityId,
                "carNumber" => $carNumber,
                "vehicleSequenceNumber" => $vehicleSequenceNumber
            ]
        ];

        $api_response = Http::post($request_url, $body);
        $data = RemoteAPiController::callBack($api_response);
        return $data;
    }

    // $body = [
    //     "credential" => RemoteConnections::TAWSEEL['credential'],
    //         "driver" => [
    //         "identityTypeId" => "NV25GlPuOnQ=",
    //         "idNumber" => "1016990911",
    //         "dateOfBirth" => 19900419,
    //         "registrationDate" => "2020-04-02T17:41:59.277Z",
    //         "mobile" => "0525478480",
    //         "regionId" => "NV25GlPuOnQ=",
    //         "carTypeId" => "NV25GlPuOnQ=",
    //         "cityId" => "NV25GlPuOnQ=",
    //         "carNumber" => "1234ABC",
    //         "vehicleSequenceNumber" => "123456789"
    //         ]   
    // ];

    public static function editeDriver(
        $refrenceCode,
        $identityTypeId,
        $idNumber,
        $dateOfBirth,
        $registrationDate,
        $mobile,
        $regionId,
        $carTypeId,
        $cityId,
        $carNumber,
        $vehicleSequenceNumber
    ) {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['CreateDriver'];


        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "driver" => [
                "refrenceCode" => $refrenceCode,
                "identityTypeId" => $identityTypeId,
                "idNumber" => $idNumber,
                "dateOfBirth" => $dateOfBirth,
                "registrationDate" => $registrationDate,
                "mobile" => $mobile,
                "regionId" => $regionId,
                "carTypeId" => $carTypeId,
                "cityId" => $cityId,
                "carNumber" => $carNumber,
                "vehicleSequenceNumber" => $vehicleSequenceNumber
            ]
        ];

        info($body);

        $api_response = Http::post($request_url, $body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }

    public static function getDriver($idNumber)
    {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['getDriver'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "idNumber" => $idNumber
        ];

        $api_response = Http::post($request_url, $body);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }


    public static function deActivateDrive($idNumber)
    {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['deActivateDriver'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "idNumber" => $idNumber
        ];

        $api_response = Http::post($request_url, $body);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }


    public static function CreateOrder(
        $orderNumber,
        $authorityId,
        $deliveryTime,
        $regionId,
        $cityId,
        $coordinates,
        $storetName,
        $storeLocation,
        $categoryId,
        $orderDate,
        $recipientMobileNumber,

    ) {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['CreateOrder'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "order" => [
                "orderNumber" => $orderNumber,
                "authorityId" => $authorityId,
                "deliveryTime" => $deliveryTime,
                "regionId" => $regionId,
                "cityId" => $cityId,
                "coordinates" => $coordinates,
                "storetName" => $storetName,
                "storeLocation" => $storeLocation,
                "categoryId" => $categoryId,
                "orderDate" => $orderDate,
                "recipientMobileNumber" => $recipientMobileNumber,
            ]
        ];

        $api_response = Http::post($request_url, $body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return $data;
    }

    public static function AcceptOrder(
        $referenceCode,
        $acceptanceDateTime,
    ) {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['acceptOrder'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "referenceCode" => $referenceCode,
            "acceptanceDateTime" => $acceptanceDateTime

        ];

        $api_response = Http::post($request_url, $body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }

    public static function rejectOrder(
        $referenceCode
    ) {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['rejectOrder'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "referenceCode" => $referenceCode,

        ];

        $api_response = Http::post($request_url, $body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return $data;
    }

    public static function executeOrder(
        $referenceCode,
        $executionTime,
        $paymentMethodId,
        float $price,
        float $priceWithoutDelivery,
        float $deliveryPrice,
        float $driverIncome
    ) {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['executeOrder'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            'orderExecutionData' => [
                "referenceCode" => $referenceCode,
                "executionTime" => $executionTime,
                "paymentMethodId" => $paymentMethodId,
                "price" => $price,
                "priceWithoutDelivery" => $priceWithoutDelivery,
                "deliveryPrice" => $deliveryPrice,
                "driverIncome" => $driverIncome
            ]
        ];

        $api_response = Http::post($request_url, $body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return $data;
    }

    public static function editOrderDeliveryAddress(
        $referenceCode,
        $regionId,
        $cityId,
        $coordinates,
    ) {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['editOrderDeliveryAddress'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "deliveryInfo" => [
                "referenceCode" => $referenceCode,
                "regionId" => $regionId,
                "cityId" => $cityId,
                "coordinates" => $coordinates,
            ]
        ];

        $api_response = Http::post($request_url, $body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }

    public static function assignDriverToOrder(
        $referenceCode,
        $idNumber,
    ) {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['assignDriverToOrder'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "deliveryInfo" => [
                "referenceCode" => $referenceCode,
                "idNumber" => $idNumber
            ]
        ];

        $api_response = Http::post($request_url, $body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }

    public static function cancelOrder(
        $orderId,
        $cancelationReasonId,
    ) {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['cancelOrder'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "deliveryInfo" => [
                "orderId" => $orderId,
                "cancelationReasonId" => $cancelationReasonId
            ]
        ];

        $api_response = Http::post($request_url, $body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return $data;
    }

    public static function getOrder($refrenceCode)
    {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['cancelOrder'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "deliveryInfo" => [
                "refrenceCode" => $refrenceCode
            ]
        ];

        $api_response = Http::post($request_url, $body);
        info($api_response);
        $data = RemoteAPiController::callBack($api_response);
        return response()->json($data, 200);
    }
    public static function getContactInfo()
    {

        $request_url = 'https://demo-apitawseel.naql.sa/api/app/contact-info/get';

        $body = 
             RemoteConnections::TAWSEEL['credential'];
    
        info($body);

        $api_response = Http::post($request_url, $body);
        info($api_response);
        $data =$api_response;
        return $data;
    }

    public static function updateContactInfo(
        $responsibleName,
        $responsibleEmail,
        $responsibleMobileNumber,
        $technicalName,
        $technicalEmail,
        $technicalMobileNumber,
    ) {

        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['ContactInfo'];

        $body = [
            "credential" => RemoteConnections::TAWSEEL['credential'],
            "appContactInfo" => [
                "responsibleName" => $responsibleName,
                "responsibleEmail" => $responsibleEmail,
                "responsibleMobileNumber" => $responsibleMobileNumber,
                "technicalName" => $technicalName,
                "technicalEmail" => $technicalEmail,
                "technicalMobileNumber" => $technicalMobileNumber
            ]
        ];
        

        $api_response = Http::post($request_url, $body);
        if($api_response->status()==200)
        return true;
        else
        return false;

    }
    public static function stcBulkPayoutsDeliverymen($payments, $desc)
    {

        $response = [];
        $CustomerReference = rand(100, 999) . '-' . Str::random(5) . '-' . time();
        $request_url = RemoteConnections::STC['paymentOrderURL'];

        $body = [
            "PaymentOrderRequestMessage" =>
            [
                'CustomerReference' => $CustomerReference,
                'Description' => $desc,
                'ValueDate' => Carbon::now()->format('Y-m-d'),
                'Payments' => $payments

            ]

        ];

        $StcBulkPayouts = new StcBulkPayouts();
        $StcBulkPayouts['CustomerReference'] = $CustomerReference;
        $StcBulkPayouts['PaymentReference'] = 'null';
        $StcBulkPayouts->save();


        foreach ($payments as $payment) {

            $stcPaymetItem = new StcDeliveryPayouts();
            $stcPaymetItem['mobile'] = $payment['MobileNumber'];
            $stcPaymetItem['ItemReference'] = $payment['ItemReference'];
            $stcPaymetItem['Amount'] = $payment['Amount'];
            $stcPaymetItem['status'] = 0;
            $stcPaymetItem['bulk_payment_id'] = $StcBulkPayouts->id;
            $stcPaymetItem->save();
        }
        // for test
        // return  $response = [
        //     'status' => true,
        //     'code' => 200,
        //     'data' => 'succes payout process',

        // ];
        try {
            $api_response = Http::withHeaders(RemoteConnections::STC['headers'])->post($request_url, $body);
        
        } catch (\Exception $e) {

            $response = [
                'status' => false,
                'code' => 503,
                'data' => $e->getMessage(),

            ];
            return $response;
        }

        if (isset($api_response['PaymetOrderResponseMessage']['PaymentOrderReference'])) {
            $StcBulkPayouts = new StcBulkPayouts();
            $StcBulkPayouts['CustomerReference'] = $CustomerReference;
            $StcBulkPayouts['PaymentReference'] = $api_response['PaymetOrderResponseMessage']['PaymetOrderReference'];
            $StcBulkPayouts->save();
            foreach ($payments as $payment) {

                $stcPaymetItem = new StcDeliveryPayouts();
                $stcPaymetItem['mobile'] = $payment['MobileNumber'];
                $stcPaymetItem['ItemReference'] = $payment['ItemReference'];
                $stcPaymetItem['Amount'] = $payment['Amount'];
                $stcPaymetItem['status'] = 0;
                $stcPaymetItem['bulk_payment_id'] = $StcBulkPayouts->id;
                $stcPaymetItem->save();
            }
            $response = [
                'status' => true,
                'code' => 200,
                'data' => 'succes payout process',

            ];
            return response()->json($response, 200);
        } else if (isset($api_response['Code']) && $api_response['Code'] != 200) {
            $StcBulkPayouts['PaymentReference'] = 'null';
            $StcBulkPayouts->save();

            $response = [
                'status' => false,
                'code' => $api_response['Code'],
                'data' => $api_response['Text'],

            ];
            return response()->json($response, 500);
        } else {
            $StcBulkPayouts['PaymentReference'] = 'null';
            $StcBulkPayouts->save();
            return response()->json($api_response, 503);
        }
    }
    public static function stcBulkPayoutsInquery($StcBulkPayouts)
    {

        $response = [];
        $request_url = RemoteConnections::STC['paymentOrderInqueryURL'];
        $payouts = [];
        foreach ($StcBulkPayouts as $StcBulkPayout) {
            $body = [
                "PaymentOrderInqueryRequestMessage" =>
                [
                    'PaymentOrderReference' => $StcBulkPayout->PaymentReference,
                    'CustomerReference' => $StcBulkPayout->CustomerReference,
                ]
            ];

            try {
            $api_response = Http::withHeaders(RemoteConnections::STC['headers'])->post($request_url, $body);
            } catch (\Exception $e) {
    
                $response = [
                    'status' => false,
                    'code' => 200,
                    'data' => $e->getMessage(),
                ];
                return  $response;

            }
            if (
                isset($api_response['PaymentOrderInqueryResponseMessage'])
                &&
                $api_response['PaymentOrderInqueryResponseMessage']['PaymetOrderReference'] == $StcBulkPayout->PaymentReference
            ) {

                foreach ($api_response['PaymentOrderInqueryResponseMessage']['Payments'] as $key => $Payment) {

                    $StcDeliveryPayout = StcDeliveryPayouts::where(
                        [
                            'mobile' => $Payment['MobileNumber'], 'Amount' => $Payment['Amount'], 'bulk_payment_id' => $StcBulkPayout->id
                        ]
                    )->first();

                    $StcDeliveryPayout->status = $Payment['Status'];
                    $StcDeliveryPayout->save();
                }
            }
        }

        $response = [
            'status' => true,
            'code' => 200,
            'data' => 'succes payout process',
        ];
        return $response;
    }

    public static function directPaymentauthorize(
        $BranchID,
        $TellerID,
        $DeviceID,
        $RefNum,
        $BillNumber,
        $Amount,
        $MobileNo,
        $MerchantNote,
        $delivery_id
    ) {

        // temp data
        $data['OtpReference'] = 'OtpReference1';
        $data['StcPayPmtReference'] = 'StcPayPmtReference2';

        $response = [
            'status' => true,
            'code' => 200,
            'data' => $data,
        ];
        // return $response;
        $request_url = RemoteConnections::STC['DirectPaymentAuthorize'];
        $body = [
            "DirectPaymentAuthorizeV4RequestMessage" => [
                "BranchID" => $BranchID,
                "TellerID" => $TellerID,
                "DeviceID" => $DeviceID,
                "RefNum" => $RefNum,
                "BillNumber" => $BillNumber,
                "Amount" => $Amount,
                "MobileNo" => $MobileNo,
                "MerchantNote" => $MerchantNote
            ]
        ];

        $api_response = Http::withHeaders(RemoteConnections::STC['headers'])->post($request_url, $body);
        $data = [];
        info($api_response);

        if (isset($api_response['DirectPaymentAuthorizeV4ResponseMessage'])) {
            $StcPayment = new StcPayment();
            $StcPayment['delivery_id'] = $delivery_id;
            $StcPayment['mobile'] = $MobileNo;
            $StcPayment['RefNum'] = $RefNum;
            $StcPayment['Amount'] = $Amount;
            $StcPayment['MerchantNote'] = $MerchantNote;
            $StcPayment['BillNumber'] = $BillNumber;
            $StcPayment['OtpReference'] = $api_response['DirectPaymentAuthorizeV4ResponseMessage']['OtpReference'];
            $StcPayment['OtpValue'] = '0';
            $StcPayment['StcPayPmtReference'] = $api_response['DirectPaymentAuthorizeV4ResponseMessage']['StcPayPmtReference'];
            $StcPayment['StcPayRefNum'] = '0';
            $StcPayment['ExpiryDuration'] = $api_response['DirectPaymentAuthorizeV4ResponseMessage']['ExpiryDuration'];
            $StcPayment['status'] = 0;
            $StcPayment->save();

            $data['OtpReference'] = $StcPayment['OtpReference'];
            $data['StcPayPmtReference'] = $StcPayment['StcPayPmtReference'];

            $response = [
                'status' => true,
                'code' => 200,
                'data' => $data,
            ];
            return $response;
        } else if (isset($api_response['Code'])) {
            return  $response = [
                'status' => false,
                'code' => $api_response['Code'],
                'data' => $api_response['Text'],
            ];
        } else {
            return  $response = [
                'status' => false,
                'code' => 409,
                'data' => 'فشل الاتصال بمزود الخدمة stc',
            ];
        }
    }

    public static function directPaymentConfirm(
        $OtpReference,
        $OtpValue,
        $StcPayPmtReference,
        $delivery_id
    ) {

        $response = [
            'status' => true,
            'code' => 2,
            'data' => 'تمت عملية الدفع بنجاح ',
        ];
        return  $response;
        
        $request_url = RemoteConnections::STC['DirectPaymentConfirm'];
        $body = [
            "DirectPaymentConfirmV4RequestMessage" => [
                "OtpReference" => $OtpReference,
                "OtpValue" => $OtpValue,
                "StcPayPmtReference" => $StcPayPmtReference,
                "TokenReference" => $StcPayPmtReference,
                "TokenizeYn" => true
            ]
        ];

        $api_response = Http::withHeaders(RemoteConnections::STC['headers'])->post($request_url, $body);
        $data = [];

        if (isset($api_response['DirectPaymentConfirmV4ResponseMessage'])) {
            $StcPayment = StcPayment::where(['OtpReference' => $OtpReference, 'StcPayPmtReference' => $StcPayPmtReference, 'delivery_id' => $delivery_id])->first();

            $StcPayment['OtpValue'] = $OtpValue;
            $StcPayment['status'] = $api_response['DirectPaymentConfirmV4ResponseMessage']['PaymentStatus'];
            $data['OtpReference'] = $StcPayment['status'];
            $data['StcPayPmtReference'] = $StcPayment['StcPayPmtReference'];
            if ($api_response['DirectPaymentConfirmV4ResponseMessage']['PaymentStatus'] == 1) {
                $response = [
                    'status' => false,
                    'code' => 1,
                    'data' => 'لم تكتمل عملية الدفع ، العملية معلقة ',
                ];
                return  $response;
            } else if ($api_response['DirectPaymentConfirmV4ResponseMessage']['PaymentStatus'] == 2) {
                $StcPayment['StcPayRefNum'] = $api_response['DirectPaymentConfirmV4ResponseMessage']['StcPayRefNum'];

                $response = [
                    'status' => true,
                    'code' => 2,
                    'data' => 'تمت عملية الدفع بنجاح ',
                ];
                return  $response;
            } else if ($api_response['DirectPaymentConfirmV4ResponseMessage']['PaymentStatus'] == 5) {
                $response = [
                    'status' => false,
                    'code' => 5,
                    'data' => 'انتهت صلاحية كود OTP اعد محاولة الدفع مرة اخرى ',
                ];
                return  $response;
            }
            $StcPayment->save();
        } else if (isset($api_response['Code'])) {
            return  $response = [
                'status' => false,
                'code' => $api_response['Code'],
                'data' => $api_response['Text'],
            ];
        } else {
            return  $response = [
                'status' => false,
                'code' => 409,
                'data' => 'فشل الاتصال بمزود الخدمة stc',
            ];
        }
    }
    public static function directPaymentInquery(
        $RefNum,
        $delivery_id
    ) {

        $request_url = RemoteConnections::STC['PaymentInquiry'];
        $body = [
            "PaymentInqueryV4RequestMessage" => [
                "RefNum" => $RefNum,
            ]
        ];

        $api_response = Http::withHeaders(RemoteConnections::STC['headers'])->post($request_url, $body);
        $data = [];

        if (isset($api_response['PaymentInqueryV4ResponseMessage'])) {
            $StcPayment = StcPayment::where(['RefNum' => $RefNum, 'delivery_id' => $delivery_id])->first();

            $StcPayment['status'] = $api_response['PaymentInqueryV4ResponseMessage']['PaymentStatus'];
            $StcPayment->save();

            if ($api_response['PaymentInqueryV4ResponseMessage']['PaymentStatus'] == 1) {
                $response = [
                    'status' => false,
                    'code' => 1,
                    'data' => 'لم تكتمل عملية الدفع ، العملية معلقة ',
                ];
                return  $response;
            } else if ($api_response['PaymentInqueryV4ResponseMessage']['PaymentStatus'] == 2) {
                $response = [
                    'status' => true,
                    'code' => 2,
                    'data' => 'تمت عملية الدفع بنجاح ',
                ];
                return  $response;
            } else if ($api_response['PaymentInqueryV4ResponseMessage']['PaymentStatus'] == 5) {
                $response = [
                    'status' => false,
                    'code' => 5,
                    'data' => 'انتهت صلاحية كود OTP اعد محاولة الدفع مرة اخرى ',
                ];
                return  $response;
            }
        } else if (isset($api_response['Code'])) {
            return  $response = [
                'status' => false,
                'code' => $api_response['Code'],
                'data' => $api_response['Text'],
            ];
        } else {
            return  $response = [
                'status' => false,
                'code' => 409,
                'data' => 'فشل الاتصال بمزود الخدمة stc',
            ];
        }
    }
    public static function callBack($remoteResponse)
    {
        info($remoteResponse);

        if (isset($remoteResponse) && isset($remoteResponse["data"]))
            $data =
                [
                    'status' => $remoteResponse["status"],
                    'data' => $remoteResponse["data"],
                ];
        else 
        if (isset($remoteResponse)) {
            if (isset($remoteResponse['status']))
            $data =
                [
                    'status' => $remoteResponse["status"],
                    'errorCodes' => $remoteResponse["errorCodes"],
                ];
            else
            $data =
                [
                    'status' => false,
                    'errorCodes' => '500',
                ];
            


        } else
            $data =
                [
                    'status' => false,
                    'errorCodes' => [500],
                ];


        return $data;
    }
}
