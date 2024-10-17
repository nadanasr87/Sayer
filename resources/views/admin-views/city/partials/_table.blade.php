@php($config = \App\CentralLogics\Helpers::get_business_settings('cash_on_delivery'))
@php($digital_payment = \App\CentralLogics\Helpers::get_business_settings('digital_payment'))
@php($offline_payment = \App\CentralLogics\Helpers::get_business_settings('offline_payment_status'))
@php($non_mod = 0)
@foreach ($zones as $key => $zone)
    @php($non_mod = count($zone->modules) > 0 && $non_mod == 0 ? $non_mod : $non_mod + 1)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $zone->id }}</td>
        <td>
            <span class="d-block font-size-sm text-body">
                {{ $zone['name'] }}
            </span>
        </td>
        <td>{{ $zone->stores_count }}</td>
        <td>{{ $zone->deliverymen_count }}</td>
        <td>
            <label class="toggle-switch toggle-switch-sm" for="status-{{ $zone['id'] }}">
                <input type="checkbox" class="toggle-switch-input dynamic-checkbox" data-id="status-{{ $zone['id'] }}"
                    data-type="status" data-image-on='{{ asset('/assets/admin/img/modal') }}/zone-status-on.png'
                    data-image-off="{{ asset('/assets/admin/img/modal') }}/zone-status-off.png"
                    data-title-on="{{ translate('Want_to_activate_this_Zone?') }}"
                    data-title-off="{{ translate('Want_to_deactivate_this_Zone?') }}"
                    data-text-on="<p>{{ translate('If_you_activate_this_zone,_Customers_can_see_all_stores_&_products_available_under_this_Zone_from_the_Customer_App_&_Website.') }}</p>"
                    data-text-off="<p>{{ translate('If_you_deactivate_this_zone,_Customers_Will_NOT_see_all_stores_&_products_available_under_this_Zone_from_the_Customer_App_&_Website.') }}</p>"
                    id="status-{{ $zone['id'] }}" {{ $zone->status ? 'checked' : '' }}>
                <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
            <form action="{{ route('admin.business-settings.zone.status', [$zone['id'], $zone->status ? 0 : 1]) }}"
                method="get" id="status-{{ $zone['id'] }}_form">
            </form>
        </td>


    </tr>
@endforeach
