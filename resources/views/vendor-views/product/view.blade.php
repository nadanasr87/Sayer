@extends('layouts.vendor.app')

@section('title', translate('Item Preview'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between">
                <h1 class="page-header-title text-break">
                    <span class="page-header-icon">
                        <img src="{{ asset('assets/admin/img/items.png') }}" class="w--22" alt="">
                    </span>
                    <span>{{ $product['name'] }}</span>
                </h1>
                <a href="{{ route('vendor.item.edit', [$product['id']]) }}" class="btn btn--primary">
                    <i class="tio-edit"></i> {{ translate('messages.edit') }}
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="review--information-wrapper mb-3">
            <div class="card h-100">
                <!-- Body -->
                <div class="card-body">
                    <div class="row align-items-md-center">
                        <div class="col-lg-5 col-md-6 mb-3 mb-md-0">
                            <div class="d-flex flex-wrap align-items-center food--media">
                                <img class="avatar avatar-xxl avatar-4by3 mr-4 onerror-image"
                                    src="{{ \App\CentralLogics\Helpers::onerror_image_helper($product['image'], asset('storage/product/') . '/' . $product['image'], asset('assets/admin/img/160x160/img2.jpg'), 'product/') }}"
                                    data-onerror-image="{{ asset('assets/admin/img/160x160/img2.jpg') }}"
                                    alt="Image Description">
                                <div class="d-block">
                                    <div class="rating--review">
                                        <h1 class="title">{{ number_format($product->avg_rating, 1) }}<span
                                                class="out-of">/5</span></h1>
                                        @if ($product->avg_rating == 5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating < 5 && $product->avg_rating >= 4.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating < 4.5 && $product->avg_rating >= 4)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating < 4 && $product->avg_rating >= 3.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating < 3.5 && $product->avg_rating >= 3)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating < 3 && $product->avg_rating >= 2.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating < 2.5 && $product->avg_rating > 2)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating < 2 && $product->avg_rating >= 1.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating < 1.5 && $product->avg_rating > 1)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating < 1 && $product->avg_rating > 0)
                                            <div class="rating">
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating == 1)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                        @elseif ($product->avg_rating == 0)
                                            <div class="rating">
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                        @endif
                                        <div class="info">
                                            <span>{{ translate('messages.of') }} {{ $product->reviews->count() }}
                                                {{ translate('messages.reviews') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-6 mx-auto">
                            <ul class="list-unstyled list-unstyled-py-2 mb-0 rating--review-right py-3">
                                @php($total = $product->rating ? array_sum(json_decode($product->rating, true)) : 0)
                                <!-- Review Ratings -->
                                <li class="d-flex align-items-center font-size-sm">
                                    @php($five = $product->rating ? json_decode($product->rating, true)[5] : 0)
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
                                    @php($four = $product->rating ? json_decode($product->rating, true)[4] : 0)
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
                                    @php($three = $product->rating ? json_decode($product->rating, true)[3] : 0)
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
                                    @php($two = $product->rating ? json_decode($product->rating, true)[2] : 0)
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
                                    @php($one = $product->rating ? json_decode($product->rating, true)[1] : 0)
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
            </div>
        </div>
        <!-- End Card -->
        @if (\App\CentralLogics\Helpers::get_store_data()->reviews_section)
            <!-- Description Card Start -->
            <div class="card mb-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless table-thead-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th class="px-4 border-0">
                                        <h4 class="m-0 text-capitalize">{{ translate('short_description') }}</h4>
                                    </th>
                                    <th class="px-4 border-0">
                                        <h4 class="m-0 text-capitalize">{{ translate('price') }}</h4>
                                    </th>
                                    <th class="px-4 border-0">
                                        <h4 class="m-0 text-capitalize">{{ translate('variations') }}</h4>
                                    </th>
                                    @if (\App\CentralLogics\Helpers::get_store_data()->module->module_type == 'food')
                                        <th class="px-4 border-0">
                                            <h4 class="m-0 text-capitalize">{{ translate('addons') }}</h4>
                                        </th>
                                    @endif
                                    <th class="px-4 border-0">
                                        <h4 class="m-0 text-capitalize">{{ translate('tags') }}</h4>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 max-w--220px">
                                        <div class="">
                                            {!! $product['description'] !!}
                                        </div>
                                    </td>
                                    <td class="px-4">
                                        <span class="d-block mb-1">
                                            <span>{{ translate('messages.price') }} : </span>
                                            <strong>{{ \App\CentralLogics\Helpers::format_currency($product['price']) }}</strong>
                                        </span>
                                        <span class="d-block mb-1">
                                            <span>{{ translate('messages.discount') }} :</span>
                                            <strong>{{ \App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::discount_calculate($product, $product['price'])) }}</strong>
                                        </span>
                                        @if (config('module.' . $product->module->module_type)['item_available_time'])
                                            <span class="d-block mb-1">
                                                {{ translate('messages.available_time_starts') }} :
                                                <strong>{{ date(config('timeformat'), strtotime($product['available_time_starts'])) }}</strong>
                                            </span>
                                            <span class="d-block mb-1">
                                                {{ translate('messages.available_time_ends') }} :
                                                <strong>{{ date(config('timeformat'), strtotime($product['available_time_ends'])) }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4">
                                        @if ($product->module->module_type == 'food')
                                            @if ($product->food_variations && is_array(json_decode($product['food_variations'], true)))
                                                @foreach (json_decode($product->food_variations, true) as $variation)
                                                    @if (isset($variation['price']))
                                                        <span class="d-block mb-1 text-capitalize">
                                                            <strong>
                                                                {{ translate('please_update_the_food_variations.') }}
                                                            </strong>
                                                        </span>
                                                    @break

                                                @else
                                                    <span class="d-block text-capitalize">
                                                        <strong>
                                                            {{ $variation['name'] }} -
                                                        </strong>
                                                        @if ($variation['type'] == 'multi')
                                                            {{ translate('messages.multiple_select') }}
                                                        @elseif($variation['type'] == 'single')
                                                            {{ translate('messages.single_select') }}
                                                        @endif
                                                        @if ($variation['required'] == 'on')
                                                            - ({{ translate('messages.required') }})
                                                        @endif
                                                    </span>

                                                    @if ($variation['min'] != 0 && $variation['max'] != 0)
                                                        ({{ translate('messages.Min_select') }}:
                                                        {{ $variation['min'] }} -
                                                        {{ translate('messages.Max_select') }}:
                                                        {{ $variation['max'] }})
                                                    @endif

                                                    @if (isset($variation['values']))
                                                        @foreach ($variation['values'] as $value)
                                                            <span class="d-block text-capitalize">
                                                                &nbsp; &nbsp; {{ $value['label'] }} :
                                                                <strong>{{ \App\CentralLogics\Helpers::format_currency($value['optionPrice']) }}</strong>
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @else
                                        @if ($product->variations && is_array(json_decode($product['variations'], true)))
                                            @foreach (json_decode($product['variations'], true) as $variation)
                                                <span class="d-block mb-1 text-capitalize">
                                                    {{ $variation['type'] }} :
                                                    {{ \App\CentralLogics\Helpers::format_currency($variation['price']) }}
                                                </span>
                                            @endforeach
                                        @endif
                                </td>
    @endif
    @if (\App\CentralLogics\Helpers::get_store_data()->module->module_type == 'food')
        <td class="px-4">
            @if (config('module.' . $product->module->module_type)['add_on'])
                @foreach (\App\Models\AddOn::whereIn('id', json_decode($product['add_ons'], true))->get() as $addon)
                    <span class="d-block mb-1 text-capitalize">
                        {{ $addon['name'] }} : {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                    </span>
                @endforeach
            @endif
        </td>
    @endif
    @if ($product->tags)
        <td>
            @foreach ($product->tags as $c)
                {{ $c->tag . ',' }}
            @endforeach
        </td>
    @endif
    </tr>
    </tbody>
    </table>
</div>
</div>
</div>
<!-- Description Card End -->

<!-- Card -->
<div class="card">
    <div class="card-header py-2 border-0">
        <div class="search--button-wrapper">
            <h5 class="card-title">{{ translate('messages.reviewer_table_list') }}<span
                    class="badge badge-soft-dark ml-2" id="itemCount">{{ count($reviews) }}</span></h5>
        </div>
    </div>
    <!-- Table -->
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
                    <th class="border-0">{{ translate('messages.review') }}</th>
                    <th class="border-0">{{ translate('messages.date') }}</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($reviews as $review)
                    <tr>
                        <td>
                            @if ($review->customer)
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img onerror-image" width="75" height="75"
                                            data-onerror-image="{{ asset('assets/admin/img/160x160/img1.jpg') }}"
                                            src="{{ \App\CentralLogics\Helpers::onerror_image_helper($review->customer->image, asset('storage . '/' . $review->customer->image, asset('assets/admin/img/160x160/img1.jpg'), 'profile/') }}"
                                            alt="Image Description">
                                    </div>
                                    <div class="ml-3">
                                        <span
                                            class="d-block h5 text-hover-primary mb-0">{{ $review->customer['f_name'] . ' ' . $review->customer['l_name'] }}
                                            <i class="tio-verified text-primary" data-toggle="tooltip"
                                                data-placement="top" title="Verified Customer"></i></span>
                                        <span
                                            class="d-block font-size-sm text-body">{{ $review->customer->email }}</span>
                                    </div>
                                </div>
                            @else
                                {{ translate('messages.customer_not_found') }}
                            @endif

                        </td>
                        <td>
                            <div class="text-wrap w-18rem">
                                <div class="d-flex mb-2">
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

    <!-- Footer -->
    <div class="card-footer">
        <!-- Pagination -->
        <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
            <div class="col-12">
                {!! $reviews->links() !!}
            </div>
        </div>
        <!-- End Pagination -->
    </div>
    <!-- End Footer -->
</div>
<!-- End Card -->
@endif
</div>
@endsection
