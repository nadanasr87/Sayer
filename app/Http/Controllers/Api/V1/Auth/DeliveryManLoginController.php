<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RestAPI\RemoteAPiController;
use App\Models\Admin;
use App\Models\City;
use App\Models\DeliveryMan;
use App\Models\TwaseelData;
use App\Models\Zone;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use App\CentralLogics\SMS_module;
use Illuminate\Support\Facades\DB; // Add this line

class DeliveryManLoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|min:4'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'phone' => $request->phone,
            'password' => $request->password
        ];

        if (auth('delivery_men')->attempt($data)) {
            $token = Str::random(120);

            if(auth('delivery_men')->user()->application_status != 'approved')
            {
                return response()->json([
                    'errors' => [
                        ['code' => 'auth-003', 'message' => translate('messages.your_application_is_not_approved_yet')]
                    ]
                ], 401);
            }
            else if(!auth('delivery_men')->user()->status)
            {
                $errors = [];
                array_push($errors, ['code' => 'auth-003', 'message' => translate('messages.your_account_has_been_suspended')]);
                return response()->json([
                    'errors' => $errors
                ], 401);
            }

            $delivery_man =  DeliveryMan::where(['phone' => $request['phone']])->first();
            $delivery_man->auth_token = $token;
            $delivery_man->save();

            if(isset($delivery_man->zone)){
                if($delivery_man->vehicle_id){

                    $topic = 'delivery_man_'.$delivery_man->zone->id.'_'.$delivery_man->vehicle_id;
                }else{
                    $topic = $delivery_man->type=='zone_wise'?$delivery_man->zone->deliveryman_wise_topic:'restaurant_dm_'.$delivery_man->store_id;
                }
            }
            return response()->json(['token' => $token, 'topic'=> isset($topic)?$topic:'No_topic_found'], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }
    public function send_otp (Request $request){
        info("otp will sebd"); 
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:11|max:14',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user = DeliveryMan::where('phone', $request->phone)->first();
        $response = SMS_module::send($request['phone'],$request['otp']);
        info($response);
        info($request['otp']);
        info($request->otp);
        if ($response == 'Success'){
            if (isset($user)){
                DB::table('delivery_men')->where(['phone' => $request['phone']])->update([
                    'password' => bcrypt($request['otp'])
                ]);
            return response()->json([
                'message' => 'old',
                'token' => 'active'
            ], 200);
           }
            else 
            return response()->json([
                'message' => 'new',
                'token' => 'active'
            ], 200);
        }else {
            $errors = [];
            array_push($errors, ['code' => 'otp', 'message' => translate('messages.faield_to_send_sms')]);
            return response()->json([
                'errors' => $errors
            ], 405);

        }


    }
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'f_name' => 'required',
    //         'identity_type' => 'required|in:passport,driving_license,nid',
    //         'identity_number' => 'required',
    //         'email' => 'required|unique:delivery_men',
    //         'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:delivery_men',
    //         'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
    //         'zone_id' => 'required',
    //         'vehicle_id' => 'required',
    //         'earning' => 'required'
    //     ], [
    //         'f_name.required' => translate('messages.first_name_is_required'),
    //         'zone_id.required' => translate('messages.select_a_zone'),
    //         'earning.required' => translate('messages.select_dm_type'),
    //         'vehicle_id.required' => translate('messages.select_a_vehicle'),
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => Helpers::error_processor($validator)],403);
    //     }

    //     if ($request->has('image')) {
    //         $image_name = Helpers::upload('delivery-man/', 'png', $request->file('image'));
    //     } else {
    //         $image_name = 'def.png';
    //     }

    //     $id_img_names = [];
    //     if (!empty($request->file('identity_image'))) {
    //         foreach ($request->identity_image as $img) {
    //             $identity_image = Helpers::upload('delivery-man/', 'png', $img);
    //             array_push($id_img_names, $identity_image);
    //         }
    //         $identity_image = json_encode($id_img_names);
    //     } else {
    //         $identity_image = json_encode([]);
    //     }

    //     $dm = New DeliveryMan();
    //     $dm->f_name = $request->f_name;
    //     $dm->l_name = $request->l_name;
    //     $dm->email = $request->email;
    //     $dm->phone = $request->phone;
    //     $dm->identity_number = $request->identity_number;
    //     $dm->identity_type = $request->identity_type;
    //     $dm->identity_image = $identity_image;
    //     $dm->vehicle_id = $request->vehicle_id;
    //     $dm->image = $image_name;
    //     $dm->status = 0;
    //     $dm->active = 0;
    //     $dm->application_status = 'pending';
    //     $dm->zone_id = $request->zone_id;
    //     $dm->earning = $request->earning;
    //     $dm->password = bcrypt($request->password);
        
    //     $dm->save();
    //     try{
    //         $admin= Admin::where('role_id', 1)->first();
    //         $mail_status = Helpers::get_mail_status('registration_mail_status_dm');
    //         if(config('mail.status') && $mail_status == '1'){
    //             Mail::to($request->email)->send(new \App\Mail\DmSelfRegistration('pending', $dm->f_name.' '.$dm->l_name));
    //         }
    //         $mail_status = Helpers::get_mail_status('dm_registration_mail_status_admin');
    //         if(config('mail.status') && $mail_status == '1'){
    //             Mail::to($admin['email'])->send(new \App\Mail\DmRegistration('pending', $dm->f_name.' '.$dm->l_name));
    //         }
    //     }catch(\Exception $ex){
    //         info($ex->getMessage());
    //     }

    //     return response()->json(['message' => translate('messages.deliveryman_added_successfully')], 200);
    // }

    public function store(Request $request)
    {
        info($request);
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'identity_type' => 'required|in:passport,driving_license,nid,NV25GlPuOnQ=',
            'identity_number' => 'required',
            'email' => 'required|unique:delivery_men',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:delivery_men',
            'password' => ['required'/*, Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()*/],
            'zone_id' => 'required',
            'earning' => 'required'
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
            'zone_id.required' => translate('messages.select_a_zone'),
            'earning.required' => translate('messages.select_dm_type'),
            'vehicle_id.required' => translate('messages.select_a_vehicle'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }


        $dateOfBirthTemp = Carbon::parse($request->dateOfBirth)->format('Y-m-d');
        $dateOfBirth = str_replace('-', '', $dateOfBirthTemp);
        $phone = str_replace('+966', '0', $request->phone);

        if ($request->has('image')) {
            info('there is image');
            $image_name = Helpers::upload('delivery-man/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }

        $id_img_names = [];
        if (!empty($request->file('identity_image'))) {
            info('there is identity_image');

            foreach ($request->identity_image as $img) {
                $identity_image = Helpers::upload('delivery-man/', 'png', $img);
                array_push($id_img_names, $identity_image);
            }
            $identity_image = json_encode($id_img_names);
        } else {
            $identity_image = json_encode([]);
        }

        $city = City::whereId($request->zone_id)->first();
        $zone = Zone::where('regionId', $city->regionId)->first();

        $dm = new DeliveryMan();
        $dm->f_name = $request->f_name;
        $dm->l_name = $request->l_name;
        $dm->email = $request->email;
        $dm->phone = $request->phone;
        $dm->identity_number = $request->identity_number;
        $dm->identity_type = $request->identity_type;
        $dm->identity_image = $identity_image;
        $dm->vehicle_id = $request->vehicle_id;
        $dm->image = $image_name;
        $dm->status = 0;
        $dm->active = 0;
        $dm->application_status = 'pending';
        $dm->zone_id = $zone->id;
        $dm->earning = $request->earning;

        $dm->national = $request->national;
        $dm->dateOfBirth = $request->dateOfBirth;
        $dm->chassisNumber = $request->chassisNumber;
        $dm->carNumber = $request->carNumber;
        $dm->carTypeId = $request->carTypeId;
        $dm->drivingLicense_issueDate = $request->drivingLicense_issueDate;
        $dm->drivingLicense_expiryDate = $request->drivingLicense_expiryDate;
        $dm->plateNumber = $request->plateNumber;
        $dm->vehicleModel = $request->vehicleModel;
        $dm->vehicleBrand = $request->vehicleBrand;
        $dm->vehicleWeight = $request->vehicleWeight;
        $dm->vehicleLoad = $request->vehicleLoad;
        $dm->vehicleColor = $request->vehicleColor;
        $dm->manufacturingYear = $request->manufacturingYear;
        $dm->serialNumber = $request->serialNumber;
        $dm->registrationType = $request->registrationType;
        
        $dm->password = bcrypt($request->password);


        $tawseel_model =
            [
                "identityTypeId" => $dm->identity_type,
                "idNumber" => $dm->identity_number,
                "dateOfBirth" => $dateOfBirth,
                "registrationDate" => Carbon::now()->format('Y-m-d H:i:s'),
                "mobile" => $phone,
                "regionId" => $city->regionId,
                "carTypeId" => $request->carTypeId == 'خاص' ? 'NV25GlPuOnQ=' : '',
                "cityId" => $city->cityId,
                "carNumber" => $request->carNumber,
                "vehicleSequenceNumber" => $dm->vehicle_id
            ];

        $data = RemoteAPiController::CreateDriver(
            $tawseel_model['identityTypeId'],
            $tawseel_model['idNumber'],
            $tawseel_model['dateOfBirth'],
            $tawseel_model['registrationDate'],
            $tawseel_model['mobile'],
            $tawseel_model['regionId'],
            $tawseel_model['carTypeId'],
            $tawseel_model['cityId'],
            $tawseel_model['carNumber'],
            $tawseel_model['vehicleSequenceNumber']
        );
        $dm->save();

        TwaseelData::create([
            'type' => 'CreateDriver',
            'reference_table' => 'delivery_men',
            'reference' => $dm->id,
            'data' => $dm,
            'status' => $data['status']
        ]);


        try {
            $admin = Admin::where('role_id', 1)->first();
            $mail_status = Helpers::get_mail_status('registration_mail_status_dm');
            if (config('mail.status') && $mail_status == '1') {
                Mail::to($request->email)->send(new \App\Mail\DmSelfRegistration('pending', $dm->f_name . ' ' . $dm->l_name));
            }
            $mail_status = Helpers::get_mail_status('dm_registration_mail_status_admin');
            if (config('mail.status') && $mail_status == '1') {
                Mail::to($admin['email'])->send(new \App\Mail\DmRegistration('pending', $dm->f_name . ' ' . $dm->l_name));
            }
        } catch (\Exception $ex) {
            info($ex->getMessage());
        }

        return response()->json(['message' => translate('messages.deliveryman_added_successfully')], 200);
    }
    
}
