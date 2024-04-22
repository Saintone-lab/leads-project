@extends('layouts.sales.app')
@section('title', 'Product In')
@section('content')
    <form action="{{ route('product-in.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" class="form-control form-control-lg fw-bold fs-3" id="floatingInputFilled"
                placeholder="xxx/xx/xx/xxxx xxxx" aria-describedby="floatingInputFilledHelp" name="invoice">
            <label for="floatingInputFilled">No Invoice</label>
            <span class="form-floating-focused"></span>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="form-invoice-repeater source-item">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-floating form-floating-outline mb-4">
                                <input class="form-control" type="text" placeholder="Put Supplier Quotation Here ...."
                                    id="supplier-input" name="suplier"
                                    value="{{ old('supplier', @$productIn->supplier ?? '') }}">
                                <label for="supplier-input">Suplier</label>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="form-floating form-floating-outline mb-4">
                                <input class="form-control" type="date" id="Date" name="date"
                                    {{-- {{ @$productIn->date ? '' : '_label' }}  naikin nanti --}}
                                    value="{{ old('date', @$productIn->date ?? now()->format('Y-m-d')) }}"
                                    {{-- {{ @$productIn->date ? '' : 'disabled' }} --}}>
                                @if (empty($productIn->date))
                                    <input type="date" name="estimated_date" id=""
                                        value="{{ now()->format('Y-m-d') }}" hidden>
                                @endif
                                <label for="Date">Date Transaction</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" data-repeater-list="group-a">
                        <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item="">
                            <div class="d-flex border rounded position-relative pe-0">
                                <div class="row w-100 p-3">
                                    <div class="col-md-6 col-12 mb-md-0 mb-3">
                                        <label for="product" class="mb-2">Product</label>
                                        <div class="form-floating form-floating-outline mb-2">
                                            <select id="replacement-dropdown-1"
                                                class="select2 form-select invoice-item-replacement" data-allow-clear="true"
                                                name="replacement[]" data-id="1">
                                                <option> ---- Choose Commodity || Replacement Here ---- </option>
                                                @foreach ($product as $products)
                                                    <option value="{{ $products->id }}"> {{ $products->product->commodity }} || {{ $products->replacement }} 
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="replacement-dropdown">Commodity || Replacement</label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Qty</p>
                                        <input type="number" class="form-control mb-3 invoice-item-qty" placeholder="Min 1"
                                            name="qty[]" id="qty-1" data-id="1" min="1"
                                            value="{{ old('qty[]') }}">
                                    </div>
                                    <div class="col-md-3 col-12 mb-md-0 mb-3">
                                        <p class="mb-2 repeater-title">Price</p>
                                        <div class="input-group" data-price="1">
                                            <span class="input-group-text">Rp. </span>
                                            <input type="text" class="form-control invoice-item-price-label"
                                                id="price-label" data-id="1" min="12" placeholder="Put Price Here"
                                                data-type="currency" pattern="^[1-9]\d{0,2}(\.\d{3})*$"
                                                @focus="focused = true" @blur="focused = false"
                                                value="{{ old('price[]') }}">
                                            <input class="form-control invoice-item-price" type="number" name="price[]"
                                                id="price-1" value="{{ old('price[]') }}" hidden>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12 pe-0">
                                        <p class="mb-2 repeater-title">Amount</p>
                                        <p class="mb-0 amount-label" id="amount-label-1" data-id="1">
                                            {{ old(strval('amount[]')) }}</p>
                                        <input type="number" class="form-control invoice-item-amount" name="amount[]"
                                            id="amount-1" data-id="1" min="12" value="{{ old('amount[]') }}"
                                            hidden>
                                    </div>
                                </div>
                                <div
                                    class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                    <i class="mdi mdi-close cursor-pointer bg-danger text-white btn-del"
                                        data-repeater-delete=""></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light btn-add"
                                data-repeater-create="">
                                <i class="mdi mdi-plus me-1"></i> Add Item
                            </button>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-8"></div>
                        <div class="col-lg-4 col-12">
                            <h5 class="my-2">
                                Shipping
                            </h5>
                            <div class="input-group" data-shipping="1">
                                <span class="input-group-text">Rp. </span>
                                <input type="text" class="form-control invoice-item-shipping-label"
                                    id="shipping-label" data-id="1" min="0" placeholder="Put shipping Here"
                                    data-type="currency" pattern="^[0-9]\d{0,2}(\.\d{3})*$" @focus="focused = true"
                                    @blur="focused = false" value="{{ old('shipping') }}">
                                <input class="form-control invoice-item-shipping" type="number" name="shipping"
                                    id="shipping" value="{{ old('shipping') }}" hidden>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-8"></div>
                        <div class="col-lg-4 col-12">
                            <h5 class="my-2">
                                Total Price
                            </h5>
                            <div class="input-group" data-total="1">
                                <span class="input-group-text">Rp. </span>
                                <p class="form-control invoice-item-total-label h-px-25 mb-0" id="total-label"> Total
                                    Price Here </p>
                                <input class="form-control invoice-item-total" type="number" name="total"
                                    id="total" value="{{ old('total') }}" hidden>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-8"></div>
                        <div class="col-lg-4 col-12">
                            <h5 class="my-2">
                                Note
                            </h5>
                            <textarea class="form-control h-px-100" rows="2" placeholder="Write your note here...." name="note">-</textarea>
                        </div>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('quotation.index') }}" type="button"
                            class="btn btn-lg btn-outline-secondary w-px-120">
                            Back
                        </a>
                        <button :disabled="focused" type="submit" class="btn btn-lg btn-primary w-px-120">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('assets') }}/includes/repeater/jquery-repeater-invoice.js"></script>
    <script src="{{ asset('assets') }}/includes/repeater/repeater-invoice-productIn.js"></script>
    <script src="{{ asset('assets') }}/js/app-invoice-add.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
@endpush
@push('script')
    <script>
        $(() => {
            var rep = 1;
            let formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });
            function formatNumber(n) {
                return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }

            function initializeSelect2Replacement() {
                $(`#replacement-dropdown-${rep}`).select2({
                    placeholder: ' ---- Choose Commodity || Replacement Here ---- ',
                    allowClear: true,
                    width: '100%',
                });
            }

            $(".invoice-item-shipping-label").on('keyup', function() {
                var input = $(this)
                var input_val = input.val();

                // original length
                var original_len = input_val.length;

                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;

                // send updated string to input
                input.val(input_val);
                var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                // console.log(id);
                $(`#shipping`).val(nomorInt);
            });

            $(".invoice-item-price-label").on('keyup', function() {
                var input = $(this)
                var id = input.data('id');
                var input_val = input.val();

                // original length
                var original_len = input_val.length;

                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;

                // send updated string to input
                input.val(input_val);
                var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                // console.log(id);
                $(`#price-${id}`).val(nomorInt);
            })

            $('.invoice-item-price-label, .invoice-item-qty').on('keyup change click', function(
                ev) {
                var id = $(this).data('id');
                var sTotal = 0,
                    row = 0;
                var amount = 0,
                    valHarga = $(`#price-${id}`).val(),
                    harga = Number(valHarga);
                amount = harga * $(`#qty-${id}`).val();
                $(`#amount-${id}`).val(amount);
                $(`#amount-label-${id}`).html(`${formatter.format(amount)}`);
            });

            // Logic Harga Total
            $('#shipping-label, .invoice-item-price-label, .invoice-item-qty')
                .on('keyup change',
                    () => {
                        var row = 0,
                            total = 0,
                            hTotal = 0,
                            shipping = isNaN(parseInt($('#shipping').val())) ? 0 : parseInt($('#shipping').val());
                        $('.amount-label').each(() => {
                            row++;
                            total += parseInt($(`#amount-${row}`).val())
                        });
                        hTotal = parseInt(total + shipping);
                        $('#total-label').html(`${formatter.format(hTotal)}`);
                        $('#total').val(hTotal);
                        console.log('Harga total: ' + hTotal);
                    });
            // Logic Subtotal dan Amount Setelah Tambah Product
            $('.btn-add').on('click', () => {
                $(".invoice-item-price-label").on('keyup', function() {
                    var input = $(this)
                    var id = input.data('id');
                    var input_val = input.val();

                    // original length
                    var original_len = input_val.length;

                    // add commas to number
                    // remove all non-digits
                    input_val = formatNumber(input_val);
                    input_val = input_val;

                    // send updated string to input
                    input.val(input_val);
                    var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                    // console.log(id);
                    $(`#price-${id}`).val(nomorInt);
                });

                $('.invoice-item-price-label, .invoice-item-qty').on('keyup change click', function(
                    ev) {
                    var id = $(this).data('id');
                    var sTotal = 0,
                        row = 0;
                    var amount = 0,
                        valHarga = $(`#price-${id}`).val(),
                        harga = Number(valHarga);
                    console.log("Harga nya adalah : " + harga);
                    amount = harga * $(`#qty-${id}`).val();
                    $(`#amount-${id}`).val(amount);
                    $(`#amount-label-${id}`).html(`${formatter.format(amount)}`);
                });

                $('#shipping-label, .invoice-item-price-label, .invoice-item-qty')
                    .on('keyup change',
                        () => {
                            var row = 0,
                                total = 0,
                                hTotal = 0,
                                shipping = isNaN(parseInt($('#shipping').val())) ? 0 : parseInt($(
                                    '#shipping').val());
                            $('.amount-label').each(() => {
                                row++;
                                total += parseInt($(`#amount-${row}`).val())
                            });
                            console.log('Ini Total: ' +total);
                            hTotal = parseInt(total + shipping);
                            $('#total-label').html(`${formatter.format(hTotal)}`);
                            $('#total').val(hTotal);
                            console.log('Harga total: ' + hTotal);
                        });
                        rep ++;
                initializeSelect2Replacement();
            })
        });
    </script>
@endpush
