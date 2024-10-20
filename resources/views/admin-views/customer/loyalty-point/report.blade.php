@extends('layouts.admin.app')

@section('title', translate('messages.customer_loyalty_point_report'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mr-3">
                <span class="page-header-icon">
                    <img src="{{ asset('assets/admin/img/customer-loyalty.png') }}" class="w--26" alt="">
                </span>
                <span>
                    {{ translate('messages.customer_loyalty_point_report') }}
                </span>
            </h1>
        </div>
        <!-- Page Header -->

        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title">
                    <span class="card-header-icon">
                        <i class="tio-filter-outlined"></i>
                    </span>
                    <span>{{ translate('messages.filter_options') }}</span>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.customer.loyalty-point.report') }}" method="get">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <input type="date" name="from" id="from_date" value="{{ request()->get('from') }}"
                                class="form-control" title="{{ translate('messages.from_date') }}">
                        </div>
                        <div class="col-sm-6">
                            <input type="date" name="to" id="to_date" value="{{ request()->get('to') }}"
                                class="form-control" title="{{ translate('messages.to_date') }}">
                        </div>
                        <div class="col-sm-6">
                            @php
                                $transaction_status = request()->get('transaction_type');
                            @endphp
                            <select name="transaction_type" id="" class="form-control"
                                title="{{ translate('messages.select_transaction_type') }}">
                                <option value="">{{ translate('messages.all') }}</option>
                                <option value="point_to_wallet"
                                    {{ isset($transaction_status) && $transaction_status == 'point_to_wallet' ? 'selected' : '' }}>
                                    {{ translate('messages.point_to_wallet') }}</option>
                                <option value="order_place"
                                    {{ isset($transaction_status) && $transaction_status == 'order_place' ? 'selected' : '' }}>
                                    {{ translate('messages.order_place') }}</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select id='customer' name="customer_id"
                                data-placeholder="{{ translate('messages.select_customer') }}"
                                class="js-data-example-ajax form-control"
                                title="{{ translate('messages.select_customer') }}">
                                @if (request()->get('customer_id') && ($customer_info = \App\Models\User::find(request()->get('customer_id'))))
                                    <option value="{{ $customer_info->id }}" selected>
                                        {{ $customer_info->f_name . ' ' . $customer_info->l_name }}({{ $customer_info->phone }})
                                    </option>
                                @endif

                            </select>
                        </div>
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="submit" class="btn btn--primary"><i
                                        class="tio-filter-list mr-1"></i>{{ translate('messages.filter') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title">
                    <span class="card-header-icon">
                        <i class="tio-document-text-outlined"></i>
                    </span>
                    <span>{{ translate('messages.summary') }}</span>
                </h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $credit = $data[0]->total_credit ?? 0;
                        $debit = $data[0]->total_debit ?? 0;
                        $balance = $credit - $debit;
                    @endphp
                    <!--Debit earned-->
                    <div class="col-md-4">
                        <div class="resturant-card dashboard--card card--bg-1">
                            <h4 class="title">{{ $debit }}</h4>
                            <span class="subtitle">
                                {{ translate('messages.debit') }}
                            </span>
                            <img class="resturant-icon" src="{{ asset('assets/admin/img/customer-loyality/1.png') }}"
                                alt="dashboard">
                        </div>
                    </div>
                    <!--Debit earned End-->
                    <!--credit earned-->
                    <div class="col-md-4">
                        <div class="resturant-card dashboard--card card--bg-2">
                            <h4 class="title">{{ $credit }}</h4>
                            <span class="subtitle">
                                {{ translate('messages.credit') }}
                            </span>
                            <img class="resturant-icon" src="{{ asset('assets/admin/img/customer-loyality/2.png') }}"
                                alt="dashboard">
                        </div>
                    </div>
                    <!--credit earned end-->
                    <!--balance earned-->
                    <div class="col-md-4">
                        <div class="resturant-card dashboard--card card--bg-3">
                            <h4 class="title">{{ $balance }}</h4>
                            <span class="subtitle">
                                {{ translate('messages.balance') }}
                            </span>
                            <img class="resturant-icon" src="{{ asset('assets/admin/img/customer-loyality/3.png') }}"
                                alt="dashboard">
                        </div>
                    </div>
                    <!--balance earned end-->
                </div>
            </div>

        </div>

        <!-- End Stats -->
        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header border-0">
                <h4 class="card-title">
                    <span class="card-header-icon">
                        <i class="tio-dollar-outlined"></i>
                    </span>
                    <span>{{ translate('messages.transactions') }}</span>
                </h4>
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
                            href="{{ route('admin.users.customer.loyalty-point.export', ['type' => 'excel', request()->getQueryString()]) }}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{ asset('assets/admin') }}/svg/components/excel.svg" alt="Image Description">
                            {{ translate('messages.excel') }}
                        </a>
                        <a id="export-csv" class="dropdown-item"
                            href="{{ route('admin.users.customer.loyalty-point.export', ['type' => 'csv', request()->getQueryString()]) }}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{ asset('assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                alt="Image Description">
                            .{{ translate('messages.csv') }}
                        </a>
                    </div>
                </div>
                <!-- End Unfold -->
            </div>
            <!-- End Header -->

            <!-- Body -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable" class="table table-thead-bordered table-align-middle card-table table-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{ translate('sl') }}</th>
                                <th class="border-0">{{ translate('messages.transaction_id') }}</th>
                                <th class="border-0">{{ translate('messages.Customer') }}</th>
                                <th class="border-0">{{ translate('messages.credit') }}</th>
                                <th class="border-0">{{ translate('messages.debit') }}</th>
                                <th class="border-0">{{ translate('messages.balance') }}</th>
                                <th class="border-0">{{ translate('messages.transaction_type') }}</th>
                                <th class="border-0">{{ translate('messages.reference') }}</th>
                                <th class="border-0">{{ translate('messages.created_at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $k => $wt)
                                <tr scope="row">
                                    <td>{{ $k + $transactions->firstItem() }}</td>
                                    <td>{{ $wt->transaction_id }}</td>
                                    <td><a
                                            href="{{ route('admin.users.customer.view', ['user_id' => $wt->user_id]) }}">{{ Str::limit($wt->user ? $wt->user->f_name . ' ' . $wt->user->l_name : translate('messages.not_found'), 20, '...') }}</a>
                                    </td>
                                    <td>{{ $wt->credit }}</td>
                                    <td>{{ $wt->debit }}</td>
                                    <td>{{ $wt->balance }}</td>
                                    <td>
                                        <span
                                            class="badge badge-soft-{{ $wt->transaction_type == 'point_to_wallet' ? 'success' : 'dark' }}">
                                            {{ translate('messages.' . $wt->transaction_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $wt->reference }}</td>
                                    <td>{{ date('Y/m/d ' . config('timeformat'), strtotime($wt->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Body -->
            @if (count($transactions) !== 0)
                <hr>
            @endif
            <div class="page-area">
                {!! $transactions->withQueryString()->links() !!}
            </div>
            @if (count($transactions) === 0)
                <div class="empty--data">
                    <img src="{{ asset('/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                    <h5>
                        {{ translate('no_data_found') }}
                    </h5>
                </div>
            @endif
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script')
@endpush

@push('script_2')
    <script src="{{ asset('assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('assets/admin') }}/vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js"></script>
    <script src="{{ asset('assets/admin') }}/js/hs.chartjs-matrix.js"></script>
    <script src="{{ asset('assets/admin') }}/js/view-pages/customer-loyalty-report.js"></script>
    <script>
        "use strict";
        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{ route('admin.users.customer.select-list') }}',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        all: true,
                        page: params.page
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    let $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
    </script>
@endpush
