@extends('layouts.admin.app')

@section('title', translate('messages.deliverymen'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('assets/admin/img/delivery-man.png') }}" class="w--26" alt="">
                </span>
                <span>{{ translate('messages.deliveryman') }}</span>
            </h1>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">
                        {{ translate('messages.withdraw_able_balance') }}<span class="badge badge-soft-dark ml-2"
                            id="itemCount">{{ $deliveryMen->total() }}</span>
                    </h5>


                    <!-- Unfold -->
                    <div class="hs-unfold mr-2">
                        <form method="post" action="{{ route('admin.transactions.payoutPaymentsEarningList') }}"
                            class="">
                            @csrf

                            <div class="btn--container mt-3 justify-content-end">
                                {{-- <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button> --}}
                                <button type="submit"
                                    class="btn btn--primary">{{ translate('messages.payout_all_balance') }}</button>
                            </div>
                        </form>

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
                            "paging":false
                        }'>
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0 text-capitalize">{{ translate('sl') }}</th>
                            <th class="border-0 text-capitalize">{{ translate('messages.name') }}</th>
                            <th class="border-0 text-capitalize">{{ translate('messages.contact_info') }}</th>
                            <th class="border-0 text-capitalize">{{ translate('messages.withdraw_able_balance') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        @foreach ($deliveryMen as $key => $dm)
                            @if ($dm->wallet)
                                @if (
                                    $dm->wallet->total_earning -
                                        ($dm->wallet->total_withdrawn + $dm->wallet->pending_withdraw + $dm->wallet->collected_cash) >
                                        0)
                                    <tr>
                                        <td>{{ $key + $deliveryMen->firstItem() }}</td>
                                        <td>
                                            <a class="table-rest-info"
                                                href="{{ route('admin.users.delivery-man.preview', [$dm['id']]) }}">
                                                <img class="onerror-image"
                                                    data-onerror-image="{{ asset('assets/admin/img/160x160/img1.jpg') }}"
                                                    src="{{ \App\CentralLogics\Helpers::onerror_image_helper($dm['image'], asset('storage/delivery-man/') . '/' . $dm['image'], asset('assets/admin/img/160x160/img1.jpg'), 'delivery-man/') }}"
                                                    alt="{{ $dm['f_name'] }} {{ $dm['l_name'] }}">
                                                <div class="info">
                                                    <h5 class="text-hover-primary mb-0">
                                                        {{ $dm['f_name'] . ' ' . $dm['l_name'] }}</h5>
                                                    <span class="d-block text-body">
                                                        <span class="rating">
                                                            <i class="tio-star"></i>
                                                            {{ count($dm->rating) > 0 ? number_format($dm->rating[0]->average, 1, '.', ' ') : 0 }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="deco-none" href="tel:{{ $dm['phone'] }}">{{ $dm['phone'] }}</a>
                                        </td>


                                        <td>
                                            <?php
                                            $balance = 0;
                                            if ($dm->wallet) {
                                                $balance = $dm->wallet->total_earning - ($dm->wallet->total_withdrawn + $dm->wallet->pending_withdraw + $dm->wallet->collected_cash);
                                            }
                                            
                                            ?>
                                            <a class="deco-none" href="tel:{{ $dm['phone'] }}">{{ $balance }}</a>
                                        </td>

                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
                @if (count($deliveryMen) !== 0)
                    <hr>
                @endif
                <div class="page-area">
                    {!! $deliveryMen->links() !!}
                </div>
                @if (count($deliveryMen) === 0)
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

@endsection

@push('script_2')
    <script>
        "use strict";
        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function() {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function() {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
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
                url: '{{ route('admin.users.delivery-man.search') }}',
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
