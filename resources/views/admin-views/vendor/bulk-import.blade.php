@extends('layouts.admin.app')

@section('title', translate('Store Bulk Import'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('assets/admin/img/resturant.png') }}" class="w--20" alt="">
                </span>
                <span>
                    {{ translate('messages.stores_bulk_import') }}
                </span>
            </h1>
        </div>
        <!-- Content Row -->
        <div class="card">
            <div class="card-body p-2">
                <div class="export-steps style-2">
                    <div class="export-steps-item">
                        <div class="inner">
                            <h5>{{ translate('STEP 1') }}</h5>
                            <p>
                                {{ translate('Download Excel File') }}
                            </p>
                        </div>
                    </div>
                    <div class="export-steps-item">
                        <div class="inner">
                            <h5>{{ translate('STEP 2') }}</h5>
                            <p>
                                {{ translate('Match Spread sheet data according to instruction') }}
                            </p>
                        </div>
                    </div>
                    <div class="export-steps-item">
                        <div class="inner">
                            <h5>{{ translate('STEP 3') }}</h5>
                            <p>
                                {{ translate('Validate data and complete import') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="jumbotron pt-1 mb-0 pb-4 bg-white">
                    <h3>{{ translate('messages.Instructions') }} : </h3>
                    <p>{{ translate('1. Download the format file and fill it with proper data.') }}</p>

                    <p>{{ translate('2. You can download the example file to understand how the data must be filled.') }}
                    </p>

                    <p>{{ translate('3. Once you have downloaded and filled the format file, upload it in the form below and submit.Make sure the phone numbers and email addresses are unique.') }}
                    </p>

                    <p>{{ translate('4. After uploading stores you need to edit them and set stores`s logo and cover.') }}
                    </p>

                    <p>{{ translate('5. You can get module id and  zone id from their list, please input the right ids.') }}
                    </p>

                    <p>{{ translate('6. For delivery time the format is "from-to type" for example: "30-40 min". Also you can use days or hours as type. Please be carefull about this format or leave this field empty.') }}
                    </p>

                    <p>{{ translate('7. You can upload your store images in store folder from gallery, and copy image`s path.') }}
                    </p>

                    <p>{{ translate('8. Default password for store is 12345678.') }}</p>

                    <p class="text-danger">
                        {{ translate('9. Latitude must be a number between -90 to 90 and Longitude must a number between -180 to 180. Otherwise it will create server error') }}
                    </p>

                </div>
                <div class="text-center pb-4">
                    <h3 class="mb-3 export--template-title">{{ translate('messages.Download Spreadsheet Template') }}</h3>
                    <div class="btn--container justify-content-center export--template-btns">
                        <a href="{{ asset('assets/stores_bulk_format.xlsx') }}" download=""
                            class="btn btn-dark">{{ translate('messages.Template with Existing Data') }}</a>
                        <a href="{{ asset('assets/stores_bulk_format_nodata.xlsx') }}" download=""
                            class="btn btn-dark">{{ translate('messages.Template without Data') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <form class="product-form" id="import_form" action="{{ route('admin.store.bulk-import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="button" id="btn_value">
                    <h4 class="mb-3">{{ translate('Import Stores File') }}</h4>
                    <div class="custom-file custom--file">
                        <input type="file" name="products_file" class="form-control" id="products_file">
                        <label class="custom-file-label"
                            for="products_file">{{ translate('messages.Choose File') }}</label>
                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button id="reset_btn" type="reset"
                            class="btn btn--reset">{{ translate('messages.Clear') }}</button>
                        <button type="submit" name="button" value="update"
                            class="btn btn--warning">{{ translate('messages.update') }}</button>
                        <button type="submit" name="button" value="import"
                            class="btn btn--primary">{{ translate('messages.Import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $('#reset_btn').click(function() {
            $('#bulk__import').val(null);
        })
    </script>
    <script>
        $(document).on("click", ":submit", function(e) {
            e.preventDefault();
            let data = $(this).val();
            myFunction(data)
        });


        function myFunction(data) {
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: "{{ translate('You_want_to_') }}" + data,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#btn_value').val(data);
                    $("#import_form").submit();
                }
            })
        }
    </script>
@endpush
