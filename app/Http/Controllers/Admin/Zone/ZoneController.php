<?php

namespace App\Http\Controllers\Admin\Zone;

use App\Contracts\Repositories\CityRepositoryInterface;
use App\Contracts\Repositories\ZoneRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Zone;
use App\Enums\ViewPaths\Admin\RemoteConnections;
use App\Enums\ViewPaths\Admin\Zone as ZoneViewPath;
use App\Enums\ViewPaths\Admin\City as CityViewPath;

use App\Exports\ZoneExport;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\RestAPI\RemoteAPiController;
use App\Http\Requests\Admin\ZoneAddRequest;
use App\Http\Requests\Admin\ZoneModuleUpdateRequest;
use App\Http\Requests\Admin\ZoneUpdateRequest;
use App\Services\ZoneService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ZoneController extends BaseController
{
    public function __construct(
        protected CityRepositoryInterface $cityRepo,
        protected ZoneRepositoryInterface $zoneRepo,
        protected ZoneService $zoneService,
        protected TranslationRepositoryInterface $translationRepo
    ) {
    }

    public function index(?Request $request): View|Collection|LengthAwarePaginator|null
    {
        return $this->getAddView($request);
    }

    private function getAddView(Request $request): View
    {
        $zones = $this->zoneRepo->getListWhere(
            searchValue: $request['search'],
            dataLimit: config('default_pagination')
        );
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        return view(ZoneViewPath::INDEX[VIEW], compact('zones', 'language', 'defaultLang'));
    }

    public function add2(ZoneAddRequest $request): JsonResponse
    {

        $zoneId = $this->zoneRepo->getAll()->count() + 1;
        $zone = $this->zoneRepo->add(data: $this->zoneService->getAddData(request: $request, zoneId: $zoneId));

        $this->translationRepo->addByModel(request: $request, model: $zone, modelPath: 'App\Models\Zone', attribute: 'name');

        $zones = $this->zoneRepo->getListWhere(
            relations: ['stores', 'deliverymen'],
            dataLimit: config('default_pagination')
        );

        return response()->json([
            'view' => view('admin-views.zone.partials._table', compact('zones'))->render(),
            'id' => $zone->id,
            'total' => $zones->count()
        ]);
    }

    public function add(): JsonResponse
    {
        $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['regions'];

        $body = [
            "companyName" => "XXXXX",
            "password" => "XXXXX"

        ];
        // try {

        $api_response = Http::post(
            $request_url,
            $body

        );
        foreach ($api_response["data"] as  $region) {


            $regionTemp = [];
            $zoneId = $this->zoneRepo->getAll()->count() + 1;
            $zoneExample = \App\Models\Zone::first();
            $zoneTemp = \App\Models\Zone::where('regionId', $region['id'])->first();
            if (!isset($zoneTemp)) {
                $regionTemp =
                    [
                        'name' => $region['nameAr'],
                        'coordinates' => $zoneExample->coordinates,
                        'store_wise_topic' => 'zone_' . $zoneId . '_store',
                        'customer_wise_topic' => 'zone_' . $zoneId . '_customer',
                        'deliveryman_wise_topic' => 'zone_' . $zoneId . '_delivery_man',
                        'cash_on_delivery' => 0,
                        'digital_payment' => 0,
                        'regionId' => $region['id'],
                    ];

                $zone = $this->zoneRepo->add(data: $regionTemp);
            }
        }


        $zones = $this->zoneRepo->getListWhere(
            relations: ['stores', 'deliverymen'],
            dataLimit: config('default_pagination')
        );
        return response()->json([
            'view' => view('admin-views.zone.partials._table', compact('zones'))->render(),
            'id' => 12,
            'total' => $zones->count()
        ]);
    }
    public function getUpdateView(string|int $id): View|RedirectResponse
    {
        if (env('APP_MODE') == 'demo' && $id == 1) {
            Toastr::warning(translate('messages.you_can_not_edit_this_zone_please_add_a_new_zone_to_edit'));
            return back();
        }

        $zone = $this->zoneRepo->getWithCoordinateWhere(
            params: ['id' => $id]
        );

        $area = json_decode($zone['coordinates'][0]->toJson(), true);
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());

        return view(ZoneViewPath::UPDATE[VIEW], compact(['zone', 'area', 'language', 'defaultLang']));
    }

    public function update(ZoneUpdateRequest $request, $id): RedirectResponse
    {
        $zone = $this->zoneRepo->update(id: $id, data: $this->zoneService->getAddData(request: $request, zoneId: $id));

        $this->translationRepo->updateByModel(request: $request, model: $zone, modelPath: 'App\Models\Zone', attribute: 'name');

        Toastr::success(translate('messages.zone_updated_successfully'));
        return back();
    }

    public function delete(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo' && $request['id'] == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_zone_please_add_a_new_zone_to_delete'));
            return back();
        }
        $this->zoneRepo->delete(id: $request['id']);
        Toastr::success(translate('messages.zone_deleted_successfully'));
        return back();
    }

    public function exportList(Request $request, string $type): BinaryFileResponse
    {
        $collection = $this->zoneRepo->getExportList($request);
        $data = [
            'data' => $collection,
            'search' => $request['search'] ?? null,
        ];
        if ($type == 'csv') {
            return Excel::download(new ZoneExport($data), Zone::EXPORT_CSV);
        }
        return Excel::download(new ZoneExport($data), Zone::EXPORT_XLSX);
    }

    public function zoneFilter($id): RedirectResponse
    {
        if ($id == 'all') {
            if (session()->has('zone_id')) {
                session()->forget('zone_id');
            }
        } else {
            session()->put('zone_id', $id);
        }

        return back();
    }

    public function getModuleSetupView($id): View
    {
        $zone = $this->zoneRepo->getFirstWhere(
            params: ['id' => $id],
            relations: ['modules']
        );
        return view(ZoneViewPath::MODULE_SETUP[VIEW], compact('zone'));
    }

    public function getLatestModuleSetupView(): View
    {
        $zone = $this->zoneRepo->getLatest(
            relations: ['modules']
        );
        return view(ZoneViewPath::MODULE_SETUP[VIEW], compact('zone'));
    }

    public function updateModuleSetup(ZoneModuleUpdateRequest $request, $id): RedirectResponse
    {
        $data = $this->zoneService->checkModuleDeliveryCharge(moduleData: $request->module_data);
        if (array_key_exists('flag', $data) && $data['flag'] == 'max_delivery_charge') {
            Toastr::error(translate('Maximum delivery charge must be greater than minimum delivery charge.'));
            return back();
        }
        $this->zoneRepo->zoneModuleSetupUpdate(id: $id, data: $this->zoneService->getZoneModuleSetupData(request: $request), moduleData: $request->module_data);

        Toastr::success(translate('messages.zone_module_updated_successfully'));
        return redirect()->route('admin.business-settings.zone.home');
    }

    public function getInstruction(): View
    {
        session()->put('zone-instruction', 1);
        $zones = $this->zoneRepo->getWithCountLatest(
            relations: ['stores', 'deliverymen'],
            dataLimit: config('default_pagination')
        );
        return view(ZoneViewPath::INDEX[VIEW], compact('zones'));
    }

    public function updateStatus(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo' && $request['id'] == 1) {
            Toastr::warning('Sorry!You can not inactive this zone!');
            return back();
        }
        $this->zoneRepo->update(id: $request['id'], data: ['status' => $request['status']]);
        Toastr::success(translate('messages.zone_status_updated'));
        return back();
    }

    public function updateDigitalPayment(Request $request): RedirectResponse
    {
        $this->zoneRepo->update(id: $request['id'], data: ['digital_payment' => $request['digital_payment']]);
        Toastr::success(translate('messages.zone_digital_payment_status_updated'));
        return back();
    }

    public function updateCashOnDelivery(Request $request): RedirectResponse
    {
        $this->zoneRepo->update(id: $request['id'], data: ['cash_on_delivery' => $request['cash_on_delivery']]);
        Toastr::success(translate('messages.zone_cash_on_delivery_status_updated'));
        return back();
    }

    public function updateOfflinePayment(Request $request): RedirectResponse
    {
        $this->zoneRepo->update(id: $request['id'], data: ['offline_payment' => $request['offline_payment']]);
        Toastr::success(translate('messages.zone_offline_payment_status_updated'));
        return back();
    }

    public function getCoordinates($id): JsonResponse
    {
        $zone = $this->zoneRepo->getWithCoordinateWhere(
            params: ['id' => $id]
        );
        $area = json_decode($zone['coordinates'][0]->toJson(), true);
        $data = $this->zoneService->formatCoordinates(coordinates: $area['coordinates']);
        $center = (object)['lat' => (float)trim(explode(' ', $zone['center'])[1], 'POINT()'), 'lng' => (float)trim(explode(' ', $zone['center'])[0], 'POINT()')];
        return response()->json(['coordinates' => $data, 'center' => $center]);
    }

    public function getAllZoneCoordinates($id = 0): JsonResponse
    {
        $zones = $this->zoneRepo->getActiveListExcept(
            params: ['id' => $id]
        );

        $data = $this->zoneService->formatZoneCoordinates(zones: $zones);

        return response()->json($data);
    }


    // cities area


    public function index_cities(?Request $request): View|Collection|LengthAwarePaginator|null
    {

        $cities = $this->cityRepo->getListWhere(
            searchValue: $request['search'],
            dataLimit: config('default_pagination')
        );
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        return view(CityViewPath::INDEX[VIEW], compact('cities', 'language', 'defaultLang'));
    }


    public function import_cities(): JsonResponse
    {
        info("importing citites");

    
        $zones = $this->zoneRepo->getListWhere(
            relations: ['stores', 'deliverymen'],
            dataLimit: config('default_pagination')
        );
        
        foreach ($zones as  $region) {
            info($region);
            if (isset($region->regionId) && $region->regionId != null && $region->regionId != '') {
                $request_url = RemoteConnections::TAWSEEL['URL'] .  RemoteConnections::TAWSEEL['cities'];

                $body = [
                    "credential" => RemoteConnections::TAWSEEL['credential'],
                    "regionId" => $region->regionId
                ];
                // try {

                $api_response = Http::post(
                    $request_url,
                    $body
                );
                info("reponse is ");
                info($api_response);
                foreach ($api_response["data"] as  $city) {
                    $regionTemp = [];
                    $zoneTemp = \App\Models\City::where('cityId', $city['id'])->first();
                    if (!isset($zoneTemp)) {
                        $regionTemp =
                            [
                                'name' => $city['nameAr'],
                                'regionId' => $region->regionId,
                                // 'nameAr' => $city['nameAr'],
                                'cityId' => $city['id'],
                            ];

                        $zone = $this->cityRepo->add(data: $regionTemp);
                    }
                }
            }
        }
        $cities = $this->cityRepo->getListWhere(
            relations: ['stores', 'deliverymen'],
            dataLimit: config('default_pagination')
        );
        info("citiiiies");
        info($cities);
        return response()->json([
            'view' => view('admin-views.city.partials._table', compact('cities'))->render(),
            'id' => 12,
            'total' => $cities->count()
        ]);
    }
    // end cities area
}
