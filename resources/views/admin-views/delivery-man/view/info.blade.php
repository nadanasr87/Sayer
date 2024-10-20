@extends('layouts.admin.app')

@section('title', translate('Delivery Man Preview'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title text-break">
                <span class="page-header-icon">
                    <img src="{{ asset('assets/admin/img/delivery-man.png') }}" class="w--26" alt="">
                </span>
                <span>{{ translate('messages.deliveryman_preview') }}<span class="badge badge-soft-dark ml-2"
                        id="itemCount">{{ $reviews->total() }}</span></span>
            </h1>
            <div class="row">
                @if ($deliveryMan->application_status == 'approved')
                    <div class="col-md-12">
                        <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
                            <!-- Nav -->
                            <ul class="nav nav-tabs mb-3 border-0 nav--tabs">
                                <li class="nav-item">
                                    <a class="nav-link active"
                                        href="{{ route('admin.users.delivery-man.preview', ['id' => $deliveryMan->id, 'tab' => 'info']) }}"
                                        aria-disabled="true">{{ translate('messages.info') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('admin.users.delivery-man.preview', ['id' => $deliveryMan->id, 'tab' => 'transaction']) }}"
                                        aria-disabled="true">{{ translate('messages.transaction') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('admin.users.delivery-man.preview', ['id' => $deliveryMan->id, 'tab' => 'conversation']) }}"
                                        aria-disabled="true">{{ translate('messages.conversations') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('admin.users.delivery-man.preview', ['id' => $deliveryMan->id, 'tab' => 'disbursement']) }}"
                                        aria-disabled="true">{{ translate('messages.disbursements') }}</a>
                                </li>
                            </ul>
                            <!-- End Nav -->
                        </div>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="btn--container justify-content-end">
                            <a class="btn btn--primary text-capitalize font-weight-bold request-alert"
                                data-url="{{ route('admin.users.delivery-man.application', [$deliveryMan['id'], 'approved']) }}"
                                data-message="{{ translate('messages.you_want_to_approve_this_application') }}"
                                href="javascript:"><i class="tio-checkmark-circle-outlined font-weight-bold pr-1"></i>
                                {{ translate('messages.approve') }}</a>
                            @if ($deliveryMan->application_status != 'denied')
                                <a class="btn btn--danger text-capitalize font-weight-bold request-alert"
                                    data-url="{{ route('admin.users.delivery-man.application', [$deliveryMan['id'], 'denied']) }}"
                                    data-message="{{ translate('messages.you_want_to_deny_this_application') }}"
                                    href="javascript:"><i class="tio-clear-circle-outlined font-weight-bold pr-1"></i>
                                    {{ translate('messages.deny') }}</a>
                            @endif
                        </div>
                    </div>

                @endif
            </div>
        </div>
        <!-- End Page Header -->
        @if ($deliveryMan->application_status == 'approved')
            <div class="row mb-3 row-3">
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-sm-6 mb-2 col-md-4">
                    <div class="resturant-card card--bg-1">
                        <h2 class="title">
                            {{ $deliveryMan->total_delivered_orders()->count() }}
                        </h2>
                        <h5 class="subtitle">
                            {{ translate('messages.total_delivered_orders') }}
                        </h5>
                        <img class="resturant-icon w--30" src="{{ asset('assets/admin/img/tick.png') }}" alt="img">
                    </div>
                </div>

                <!-- Collected Cash Card Example -->
                <div class="col-sm-6 mb-2 col-md-4">
                    <div class="resturant-card bg--3">
                        <h2 class="title">
                            {{ \App\CentralLogics\Helpers::format_currency($deliveryMan->wallet ? $deliveryMan->wallet->collected_cash : 0.0) }}
                        </h2>
                        <h5 class="subtitle">
                            {{ translate('messages.cash_in_hand') }}
                        </h5>
                        <img class="resturant-icon" src="{{ asset('/assets/admin/img/transactions/withdraw-amount.png') }}"
                            alt="transactions">
                    </div>
                </div>

                <!-- Total Earning Card Example -->
                <div class="col-sm-6 mb-2 col-md-4">
                    <div class="resturant-card bg--1">
                        <h2 class="title">
                            {{ \App\CentralLogics\Helpers::format_currency($deliveryMan->wallet ? $deliveryMan->wallet->total_earning : 0.0) }}
                        </h2>
                        <h5 class="subtitle">
                            {{ translate('messages.total_earning') }}
                        </h5>
                        <img class="resturant-icon" src="{{ asset('/assets/admin/img/transactions/pending.png') }}"
                            alt="transactions">
                    </div>
                </div>

                <!-- Total Earning Card Example -->

                <?php
                $balance = 0;
                if ($deliveryMan->wallet) {
                    $balance = $deliveryMan->wallet->total_earning - ($deliveryMan->wallet->total_withdrawn + $deliveryMan->wallet->pending_withdraw + $deliveryMan->wallet->collected_cash);
                }
                
                ?>
                @if ($deliveryMan->earning)

                    @if ($balance > 0)
                        <div class="col-sm-6 mb-2 col-md-4">
                            <div class="resturant-card bg--1">
                                <h2 class="title">
                                    {{ \App\CentralLogics\Helpers::format_currency(abs($balance)) }}
                                </h2>
                                <h5 class="subtitle">
                                    {{ translate('messages.Withdraw_Able_Balance') }}
                                </h5>
                                <img class="resturant-icon" src="{{ asset('/assets/admin/img/transactions/pending.png') }}"
                                    alt="transactions">
                            </div>
                        </div>
                    @elseif($balance < 0)
                        <div class="col-sm-6 mb-2 col-md-4">
                            <div class="resturant-card bg--1">
                                <h2 class="title">
                                    {{ \App\CentralLogics\Helpers::format_currency(abs($deliveryMan->wallet->collected_cash)) }}
                                </h2>
                                <h5 class="subtitle">
                                    {{ translate('messages.Payable_Balance') }}
                                </h5>
                                <img class="resturant-icon" src="{{ asset('/assets/admin/img/transactions/pending.png') }}"
                                    alt="transactions">
                            </div>
                        </div>
                    @else
                        <div class="col-sm-6 mb-2 col-md-4">
                            <div class="resturant-card bg--1">
                                <h2 class="title">
                                    {{ \App\CentralLogics\Helpers::format_currency(0) }}
                                </h2>
                                <h5 class="subtitle">
                                    {{ translate('messages.Balance') }}
                                </h5>
                                <img class="resturant-icon" src="{{ asset('/assets/admin/img/transactions/pending.png') }}"
                                    alt="transactions">
                            </div>
                        </div>
                    @endif


                    <div class="col-sm-6 mb-2 col-md-4">
                        <div class="resturant-card bg--1">
                            <h2 class="title">
                                {{ \App\CentralLogics\Helpers::format_currency($deliveryMan->wallet ? $deliveryMan->wallet->total_withdrawn : 0.0) }}
                            </h2>
                            <h5 class="subtitle">
                                {{ translate('messages.Total_withdrawn') }}
                            </h5>
                            <img class="resturant-icon" src="{{ asset('/assets/admin/img/transactions/pending.png') }}"
                                alt="transactions">
                        </div>
                    </div>

                    <div class="col-sm-6 mb-2 col-md-4">
                        <div class="resturant-card bg--1">
                            <h2 class="title">
                                {{ \App\CentralLogics\Helpers::format_currency($deliveryMan->wallet ? $deliveryMan->wallet->pending_withdraw : 0.0) }}
                            </h2>
                            <h5 class="subtitle">
                                {{ translate('messages.Pending_withdraw') }}
                            </h5>
                            <img class="resturant-icon" src="{{ asset('/assets/admin/img/transactions/pending.png') }}"
                                alt="transactions">
                        </div>
                    </div>

                @endif
            </div>
    </div>
    </div>
    </div>
    @endif

    <!-- Card -->
    <div class="card mb-3">
        <div class="card-header py-2">
            <div class="search--button-wrapper">
                <h4 class="card-title">
                    {{ $deliveryMan['f_name'] . ' ' . $deliveryMan['l_name'] }}

                    (@if ($deliveryMan->zone)
                        {{ $deliveryMan->zone->name }}
                    @else
                        {{ translate('messages.zone_deleted') }}
                    @endif )
                    @if ($deliveryMan->application_status == 'approved')
                        @if ($deliveryMan['status'])
                            @if ($deliveryMan['active'])
                                <label class="badge badge-soft-primary">{{ translate('messages.online') }}</label>
                            @else
                                <label class="badge badge-soft-danger">{{ translate('messages.offline') }}</label>
                            @endif
                        @else
                            <span class="badge badge-danger">{{ translate('messages.suspended') }}</span>
                        @endif
                    @else
                        <label
                            class="badge badge-soft-{{ $deliveryMan->application_status == 'pending' ? 'info' : 'danger' }}">{{ translate('messages.' . $deliveryMan->application_status) }}</label>
                    @endif
                </h4>
                @if ($deliveryMan->application_status == 'approved')
                    <a href="javascript:"
                        class="btn font-medium request-alert {{ $deliveryMan->status ? 'btn--danger' : 'btn-success' }}"
                        data-url="{{ route('admin.users.delivery-man.status', [$deliveryMan['id'], $deliveryMan->status ? 0 : 1]) }}"
                        data-message="{{ $deliveryMan->status ? translate('messages.you_want_to_suspend_this_deliveryman') : translate('messages.you_want_to_unsuspend_this_deliveryman') }}">
                        {{ $deliveryMan->status ? translate('messages.suspend_this_delivery_man') : translate('messages.unsuspend_this_delivery_man') }}
                    </a>
                @endif
                <div class="hs-unfold">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ translate('messages.type') }}
                            ({{ $deliveryMan->earning ? translate('messages.freelancer') : translate('messages.salary_based') }})
                        </button>
                        <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item {{ $deliveryMan->earning ? 'active' : '' }} request-alert"
                                data-url="{{ route('admin.users.delivery-man.earning', [$deliveryMan['id'], 1]) }}"
                                data-message="{{ translate('messages.want_to_enable_earnings') }}"
                                href="javascript:">{{ translate('messages.freelancer') }}</a>
                            <a class="dropdown-item {{ $deliveryMan->earning ? '' : 'active' }} request-alert"
                                data-url="{{ route('admin.users.delivery-man.earning', [$deliveryMan['id'], 0]) }}"
                                data-message="{{ translate('messages.want_to_disable_earnings') }}"
                                href="javascript:">{{ translate('messages.salary_based') }}</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Body -->
        <div class="card-body">
            <div class="row gy-3 align-items-center">
                <div class="col-md-4">

                    <h2 class="title">{{ translate('Vehicle Information') }}</h2>
                    @if (isset($deliveryMan->vehicle))
                        <div>{{ translate('Vehicle_Type') }} : {{ $deliveryMan->vehicle->type }}</div>
                        <div>{{ translate('Vehicle_Extra_Charges') }} : {{ $deliveryMan->vehicle->extra_charges }}</div>
                        <div>{{ translate('Vehicle_minimum_coverage_area') }} :
                            {{ $deliveryMan->vehicle->starting_coverage_area }}</div>
                        <div>{{ translate('Vehicle_maximum_coverage_area') }} :
                            {{ $deliveryMan->vehicle->maximum_coverage_area }}</div>
                        <div><span class="te">{{ translate('vehicleModel') }}</span> :
                            {{ translate($deliveryMan->vehicleModel) }}</div>
                        <div><span class="te">{{ translate('messages.vehicleBrand') }}</span> :
                            {{ $deliveryMan->vehicleBrand }}</div>
                        <div><span class="te">{{ translate('messages.carNumber') }}</span> :
                            {{ $deliveryMan->carNumber }}</div>
                        <div><span class="te">{{ translate('messages.plateNumber') }}</span> :
                            {{ $deliveryMan->plateNumber }}</div>
                        <div><span class="te">{{ translate('messages.chassisNumber') }}</span> :
                            {{ $deliveryMan->chassisNumber }}</div>
                        <div><span class="te">{{ translate('messages.vehicleColor') }}</span> :
                            {{ $deliveryMan->vehicleColor }}</div>
                        <div><span class="te">{{ translate('messages.manufacturingYear') }}</span> :
                            {{ $deliveryMan->manufacturingYear }}</div>
                        <div><span class="te">{{ translate('messages.registrationType') }}</span> :
                            {{ $deliveryMan->registrationType }}</div>
                    @else
                        <div>{{ translate('No_vehicle_data_found') }}</div>
                        <div class="hs-unfold">
                            <div class="dropdown">
                                @php
                                    $isSelect = 0;
                                @endphp
                                @foreach (\App\Models\DMVehicle::where('status', 1)->get(['id', 'type']) as $v)
                                    @if ($v->id == $deliveryMan['vehicle_id'])
                                        @php
                                            $isSelect = 1;
                                        @endphp <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"> {{ $v->type }} </button>
                                    @endif
                                @endforeach
                                @if ($isSelect == 0)
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        {{ translate('messages.select_vehicle') }} </button>
                                @endif

                                <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                                    @foreach (\App\Models\DMVehicle::where('status', 1)->get(['id', 'type']) as $v)
                                        <a class="dropdown-item  request-alert"
                                            data-url="{{ route('admin.users.delivery-man.set-vehicle', [$deliveryMan['id'], $v->id]) }}"
                                            data-message="{{ $v->type }}"
                                            href="javascript:">{{ $v->type }}</a>
                                    @endforeach

                                    {{-- <a class="dropdown-item {{$deliveryMan->earning?'':'active'}} request-alert" data-url="{{route('admin.users.delivery-man.earning',[$deliveryMan['id'],0])}}" data-message="{{translate('messages.want_to_disable_earnings')}}"
                                            href="javascript:">{{translate('messages.salary_based')}}</a> --}}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <img class="avatar avatar-xxl avatar-4by3 mr-4 img--120 onerror-image"
                            data-onerror-image="{{ asset('assets/admin/img/160x160/img1.jpg') }}"
                            src="{{ \App\CentralLogics\Helpers::onerror_image_helper($deliveryMan['image'], asset('storage/delivery-man/') . '/' . $deliveryMan['image'], asset('assets/admin/img/160x160/img1.jpg'), 'delivery-man/') }}"
                            alt="Image Description">
                        <div class="d-block">
                            <div class="rating--review">
                                <h1 class="title">
                                    {{ count($deliveryMan->rating) > 0 ? number_format($deliveryMan->rating[0]->average, 1) : 0 }}<span
                                        class="out-of">/5</span></h1>
                                @if (count($deliveryMan->rating) > 0)
                                    @if ($deliveryMan->rating[0]->average == 5)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                        </div>
                                    @elseif ($deliveryMan->rating[0]->average < 5 && $deliveryMan->rating[0]->average > 4.5)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-half"></i></span>
                                        </div>
                                    @elseif ($deliveryMan->rating[0]->average < 4.5 && $deliveryMan->rating[0]->average > 4)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                    @elseif ($deliveryMan->rating[0]->average < 4 && $deliveryMan->rating[0]->average > 3)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                    @elseif ($deliveryMan->rating[0]->average < 3 && $deliveryMan->rating[0]->average > 2)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                    @elseif ($deliveryMan->rating[0]->average < 2 && $deliveryMan->rating[0]->average > 1)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                    @elseif ($deliveryMan->rating[0]->average < 1 && $deliveryMan->rating[0]->average > 0)
                                        <div class="rating">
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                    @elseif ($deliveryMan->rating[0]->average == 1)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                    @elseif ($deliveryMan->rating[0]->average == 0)
                                        <div class="rating">
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                    @endif
                                @endif
                                <div class="info">
                                    {{-- <span class="mr-3">{{$deliveryMan->rating->count()}} {{translate('messages.rating')}}</span> --}}
                                    <span>{{ $deliveryMan->reviews->count() }} {{ translate('messages.reviews') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <ul class="list-unstyled list-unstyled-py-2 mb-0 rating--review-right py-3">

                        @php($total = $deliveryMan->reviews->count())
                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($five = \App\CentralLogics\Helpers::dm_rating_count($deliveryMan['id'], 5))
                            <span class="progress-name mr-3">{{ translate('excellent') }}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $total == 0 ? 0 : ($five / $total) * 100 }}%;"
                                    aria-valuenow="{{ $total == 0 ? 0 : ($five / $total) * 100 }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="ml-3">{{ $five }}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($four = \App\CentralLogics\Helpers::dm_rating_count($deliveryMan['id'], 4))
                            <span class="progress-name mr-3">{{ translate('good') }}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $total == 0 ? 0 : ($four / $total) * 100 }}%;"
                                    aria-valuenow="{{ $total == 0 ? 0 : ($four / $total) * 100 }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="ml-3">{{ $four }}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($three = \App\CentralLogics\Helpers::dm_rating_count($deliveryMan['id'], 3))
                            <span class="progress-name mr-3">{{ translate('average') }}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $total == 0 ? 0 : ($three / $total) * 100 }}%;"
                                    aria-valuenow="{{ $total == 0 ? 0 : ($three / $total) * 100 }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="ml-3">{{ $three }}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($two = \App\CentralLogics\Helpers::dm_rating_count($deliveryMan['id'], 2))
                            <span class="progress-name mr-3">{{ translate('below_average') }}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $total == 0 ? 0 : ($two / $total) * 100 }}%;"
                                    aria-valuenow="{{ $total == 0 ? 0 : ($two / $total) * 100 }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="ml-3">{{ $two }}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($one = \App\CentralLogics\Helpers::dm_rating_count($deliveryMan['id'], 1))
                            <span class="progress-name mr-3">{{ translate('poor') }}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $total == 0 ? 0 : ($one / $total) * 100 }}%;"
                                    aria-valuenow="{{ $total == 0 ? 0 : ($one / $total) * 100 }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="ml-3">{{ $one }}</span>
                        </li>
                        <!-- End Review Ratings -->
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Body -->
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">
                <i class="tio-user"></i>
                <span>{{ translate('Identity Documents') }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="row gy-3 align-items-center">
                <div class="card-body">
                    <div class="pl-3"><span class="te">{{ translate('Identity_Type') }}</span> :
                        {{ translate($deliveryMan->identity_type) }}</div>
                    <div class="pl-3"><span class="te">{{ translate('messages.identification_number') }}</span> :
                        {{ $deliveryMan->identity_number }}</div>


                </div>
                <div class="card-body">

                    <div class="pl-3"><span class="te">{{ translate('messages.national') }}</span> :
                        {{ $deliveryMan->national }}</div>
                    <div class="pl-3"><span class="te">{{ translate('messages.dateOfBirth') }}</span> :
                        {{ $deliveryMan->dateOfBirth }}</div>
                    <div class="pl-3"><span class="te">{{ translate('messages.registrationType') }}</span> :
                        {{ $deliveryMan->dateOfBirth }}</div>

                </div>
            </div>

            <div class="row g-3">
                @foreach (json_decode($deliveryMan->identity_image) as $key => $img)
                    <div class="col-auto">
                        <button class="btn w-100" data-toggle="modal" data-target="#image-{{ $key }}">
                            <div class="gallary-card">
                                <img data-onerror-image="{{ asset('/assets/admin/img/900x400/img1.jpg') }}"
                                    src="{{ \App\CentralLogics\Helpers::onerror_image_helper($img, asset('storage/delivery-man/') . '/' . $img, asset('assets/admin/img/900x400/img1.jpg'), 'delivery-man/') }}"
                                    class="w-100 onerror-image">
                            </div>
                        </button>
                        <div class="modal fade" id="image-{{ $key }}" tabindex="-1" role="dialog"
                            aria-labelledby="myModlabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModlabel">
                                            {{ translate('messages.Identity_Image') }}</h4>
                                        <button type="button" class="close" data-dismiss="modal"><span
                                                aria-hidden="true">&times;</span><span
                                                class="sr-only">{{ translate('messages.Close') }}</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <img data-onerror-image="{{ asset('/assets/admin/img/900x400/img1.jpg') }}"
                                            src="{{ \App\CentralLogics\Helpers::onerror_image_helper($img, asset('storage/delivery-man/') . '/' . $img, asset('assets/admin/img/900x400/img1.jpg'), 'delivery-man/') }}"
                                            class="w-100 onerror-image">
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Card -->
    <div class="card">
        <!-- Header -->
        <div class="card-header py-2 border-0">
            <h5 class="card-header-title">
                {{ translate('messages.review_list') }}
            </h5>
            <div class="search--button-wrapper justify-content-end">
                <!-- Unfold -->
                <div class="hs-unfold mr-2">
                    <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"
                        data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                        <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                    </a>

                    <div id="usersExportDropdown"
                        class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                        <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                        <a id="export-excel" class="dropdown-item"
                            href="{{ route('admin.users.delivery-man.review-export', ['type' => 'excel', 'id' => $deliveryMan->id, request()->getQueryString()]) }}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{ asset('assets/admin') }}/svg/components/excel.svg" alt="Image Description">
                            {{ translate('messages.excel') }}
                        </a>
                        <a id="export-csv" class="dropdown-item"
                            href="{{ route('admin.users.delivery-man.review-export', ['type' => 'csv', 'id' => $deliveryMan->id, request()->getQueryString()]) }}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{ asset('assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                alt="Image Description">
                            .{{ translate('messages.csv') }}
                        </a>
                    </div>
                </div>
                <!-- End Unfold -->
            </div>
        </div>
        <!-- End Header -->
        <!-- Table -->
        <div class="card-body p-0">
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table"
                    data-hs-datatables-options='{
                        "columnDefs": [{
                            "targets": [0, 3, 6],
                            "orderable": false
                        }],
                        "order": [],
                        "info": {
                        "totalQty": "#datatableWithPaginationInfoTotalQty"
                        },
                        "search": "#datatableSearch",
                        "entries": "#datatableEntries",
                        "pageLength": 25,
                        "isResponsive": false,
                        "isShowPaging": false,
                        "pagination": "datatablePagination"
                    }'>
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0">{{ translate('messages.reviewer') }}</th>
                            <th class="border-0">{{ translate('messages.order_id') }}</th>
                            <th class="border-0">{{ translate('messages.reviews') }}</th>
                            <th class="border-0">{{ translate('messages.date') }}</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($reviews as $review)
                            <tr>
                                <td>
                                    @if ($review->customer)
                                        <a class="d-flex align-items-center"
                                            href="{{ route('admin.customer.view', [$review['user_id']]) }}">
                                            <div class="avatar avatar-circle">
                                                <img class="avatar-img" width="75" height="75"
                                                    src="{{ asset('storage/profile/') }}/{{ $review->customer ? $review->customer->image : '' }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="ml-3">
                                                <span
                                                    class="d-block h5 text-hover-primary mb-0">{{ $review->customer ? $review->customer['f_name'] . ' ' . $review->customer['l_name'] : '' }}
                                                    <i class="tio-verified text-primary" data-toggle="tooltip"
                                                        data-placement="top" title="Verified Customer"></i></span>
                                                <span
                                                    class="d-block font-size-sm text-body">{{ $review->customer ? $review->customer->email : '' }}</span>
                                            </div>
                                        </a>
                                    @else
                                        {{ translate('messages.customer_not_found') }}
                                    @endif
                                </td>
                                <td>
                                    <a
                                        href="{{ route('admin.order.all-details', ['id' => $review->order_id]) }}">{{ $review->order_id }}</a>
                                </td>
                                <td>
                                    <div class="text-wrap w-18rem">
                                        <div class="d-flex">
                                            <label class="badge badge-soft-info">
                                                {{ $review->rating }} <i class="tio-star"></i>
                                            </label>
                                        </div>

                                        <p>
                                            {{ $review['comment'] }}
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    {{ date('d M Y ' . config('timeformat'), strtotime($review['created_at'])) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->
            @if (count($reviews) !== 0)
                <hr>
            @endif
            <div class="page-area">
                {!! $reviews->links() !!}
            </div>
            @if (count($reviews) === 0)
                <div class="empty--data">
                    <img src="{{ asset('/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                    <h5>
                        {{ translate('no_data_found') }}
                    </h5>
                </div>
            @endif
        </div>
    </div>
    <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
        "use strict";
        $('.request-alert').on('click', function() {
            let url = $(this).data('url');
            let message = $(this).data('message');
            request_alert(url, message);
        })

        function request_alert(url, message) {
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
    </script>
@endpush
