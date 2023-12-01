@extends('layouts.admin.app')

@section('title', translate('Food Preview'))

@push('css_or_js')
    <style>
        /* Add this CSS for zoom-in effect */
        .zoomed-in {
            cursor: zoom-out;
            transform: scale(2);
            /* Adjust the zoom level as needed */
            transition: transform 0.3s ease-in-out;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="page-header-title text-break">
                    {{ $product['name'] }}
                </h1>
                <a href="{{ route('admin.food.edit', [$product['id']]) }}" class="btn btn--primary">
                    <i class="tio-edit"></i> {{ translate('Edit Info') }}
                </a>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row review--information-wrapper g-2 mb-3">
            <div class="col-lg-9">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="row align-items-md-center">
                            <div class="col-md-6  mb-3 mb-md-0">
                                <div class="d-flex flex-wrap align-items-center food--media">


                                    <div style="font-weight: 400;font-size: 16px;line-height: 30px;color: #242A30;">
                                        <span style="margin-inline-end:5px;">
                                            <i class="tio-user"></i> <a href="tel:{{ $product->phone }}"
                                                style="text-decoration: none; color:  #4ef212;">{{ translate('messages.phone') }}:
                                                {{ $product->phone }}</a>
                                        </span>
                                        <label class="input-label"><span class="out-of"></span></label>

                                        <span style="margin-inline-end:5px;">
                                            <i class="tio-user"></i> <a href="mailto:{{ $product->email }}"
                                                style="text-decoration: none; color:  #1282f2;">{{ translate('messages.email') }}:
                                                {{ $product->email }}</a>
                                        </span>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card h-100">

                    <div class="card-body d-flex flex-column justify-content-center">
                        @if ($product->restaurant)
                            <a class="resturant--information-single"
                                href="{{ route('admin.restaurant.view', $product->restaurant_id) }}"
                                title="{{ $product->restaurant['name'] }}">
                                <img class="avatar-img initial-54"
                                    onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                    src="{{ asset('storage/app/public/restaurant/' . $product->restaurant->logo) }}"
                                    alt="Image Description">
                                <div class="text-center">
                                    <h5 class="text-capitalize text--title font-semibold text-hover-primary d-block mb-1">
                                        {{ $product->restaurant['name'] }}
                                    </h5>
                                    {{-- <span class="text--title">
                                        <i class="tio-poi"></i> {{ $product->restaurant['address'] }}
                                    </span> --}}
                                </div>
                            </a>
                        @else
                            <div class="badge badge-soft-danger py-2">{{ translate('messages.restaurant') }}
                                {{ translate('messages.deleted') }}</div>
                        @endif
                    </div>


                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="px-4 w-80px">
                                    <h4 class="m-0">{{ translate('Short Description') }}</h4>
                                </th>
                                <th class="px-4 w-120px">
                                    <h4 class="m-0">{{ translate('messages.images') }}</h4>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4">
                                    <div>
                                        {!! $product['description'] !!}
                                    </div>
                                </td>
                                <td class="px-4">
                                    @foreach (json_decode($product->images, true) as $element)
                                        <img class="initial-52 object--cover border--dashed preview-image "
                                            style="width: 80px;
                                                                                cursor: pointer;

                                    height: 80px;"
                                            src="{{ asset('storage/app/public/product/' . $element) }}"
                                            alt="place image" />
                                    @endforeach


                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- End Body -->
        </div>
        <!-- End Card -->

        <!-- Card -->

    </div>
    <!-- The Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Image container inside the modal -->
                    <center>
                        <div id="imagePreview"></div>
                    </center>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function status_form_alert(id, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#' + id).submit()
                }
            })
        }
        $(document).ready(function() {
            // Click event handler for previewing images in the modal
            $('.preview-image').click(function() {
                var imageUrl = $(this).attr('src');
                $('#imagePreview').html('<img src="' + imageUrl +
                    '" class="img-fluid" alt="Preview Image">');
                $('#imageModal').modal('show');
            });
            var isZoomedIn = false;

            $('#imagePreview').on('dblclick', 'img', function() {
                isZoomedIn = !isZoomedIn;

                if (isZoomedIn) {
                    $(this).addClass('zoomed-in');
                } else {
                    $(this).removeClass('zoomed-in');
                }
            });
        });
    </script>
@endpush
