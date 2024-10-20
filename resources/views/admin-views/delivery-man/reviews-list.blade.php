@extends('layouts.admin.app')

@section('title', translate('messages.Review List'))

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
                <span>{{ translate('messages.deliveryman_reviews') }}<span class="badge badge-soft-dark ml-2"
                        id="itemCount">{{ $reviews->total() }}</span></span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header py-2 border-0">
                        <span class="card-header-title"></span>
                        <div class="search--button-wrapper justify-content-end">
                            <form class="search-form">
                                {{-- @csrf --}}
                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input id="datatableSearch" name="search" type="search" class="form-control"
                                        placeholder="{{ translate('ex_:_search_delivery_man') }}"
                                        value="{{ request()->get('search') }}"
                                        aria-label="{{ translate('messages.search_here') }}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                            <!-- Unfold -->
                            <div class="hs-unfold mr-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40"
                                    href="javascript:;"
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
                                        href="{{ route('admin.users.delivery-man.reviews.export', ['type' => 'excel', request()->getQueryString()]) }}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('assets/admin') }}/svg/components/excel.svg"
                                            alt="Image Description">
                                        {{ translate('messages.excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item"
                                        href="{{ route('admin.users.delivery-man.reviews.export', ['type' => 'csv', request()->getQueryString()]) }}">
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
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging": false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">{{ translate('sl') }}</th>
                                    <th class="border-0">{{ translate('messages.deliveryman') }}</th>
                                    <th class="border-0">{{ translate('messages.customer') }}</th>
                                    <th class="border-0">{{ translate('messages.review') }}</th>
                                    <th class="border-0">{{ translate('messages.rating') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($reviews as $key => $review)
                                    @if (isset($review->delivery_man))
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <span class="d-block font-size-sm text-body">
                                                    <a
                                                        href="{{ route('admin.users.delivery-man.preview', [$review['delivery_man_id']]) }}">
                                                        {{ $review->delivery_man->f_name . ' ' . $review->delivery_man->l_name }}
                                                    </a>
                                                </span>
                                            </td>
                                            <td>
                                                @if ($review->customer)
                                                    <a href="{{ route('admin.users.customer.view', [$review->user_id]) }}">
                                                        {{ $review->customer ? $review->customer->f_name : '' }}
                                                        {{ $review->customer ? $review->customer->l_name : '' }}
                                                    </a>
                                                @else
                                                    {{ translate('messages.customer_not_found') }}
                                                @endif

                                            </td>
                                            <td>
                                                {{ $review->comment }}
                                            </td>
                                            <td>
                                                <label class="rating">
                                                    {{ $review->rating }} <i class="tio-star"></i>
                                                </label>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
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
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        "use strict";

        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

        });

        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.users.delivery-man.reviews.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.count);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
