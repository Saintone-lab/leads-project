@extends('layouts.sales.app')
@section('title', 'Create Quotation')
@section('content')
    @php
        $id = 1;
        $dataDetail = 0;
    @endphp
    <form action="{{ @$quotation ? route('quotation.update', $quotation->id) : route('quotation.store') }}" method="post"
        enctype="multipart/form-data">
        @csrf
        @if (@$quotation)
            @method('patch')
        @endif
        <div class="form-floating mb-3">
            <input type="text" class="form-control fw-bold fs-3" id="floatingInputFilled" aria-describedby="floatingInputFilledHelp"
                name="no_quote" value="{{ old('no_quote', @$quotation->no_quote ? $quotation->no_quote . '-REV-' : $formattedNumberQ . '-P/BDG/RJO-' . Auth::user()->code . '/' . $formattedMonthNow . '/' . \Carbon\Carbon::now()->year ) }}">
            <label for="floatingInputFilled">Number Quotation</label>
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
                        <div class="col-12 col-lg-3 mb-3">
                            <div class="form-floating form-floating-outline">
                                <select id="select2Basic" class="select2 form-select form-select-lg" data-allow-clear="true"
                                    name="id_pic">
                                    @foreach ($pic as $charge)
                                        <option value="{{ $charge->id }}"
                                            {{ @$quotation->id_pic == $charge->id ? 'selected' : '' }}>
                                            {{ $charge->name_pic }} | {{$charge->client->company}}</option>
                                    @endforeach
                                </select>
                                <label for="select2Basic">Client</label>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="form-floating form-floating-outline mb-4">
                                <input class="form-control" type="text" placeholder="Put Title Quotation Here ...."
                                    id="title-input" name="title" value="{{ old('title', @$quotation->title ?? '') }}">
                                <label for="title-input">Title Quotation</label>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="form-floating form-floating-outline mb-4">
                                <input class="form-control" type="date" id="estimatedDate"
                                    name="estimated_date{{ @$quotation->estimated_date ? '' : '_label' }}"
                                    value="{{ old('estimated_date', @$quotation->estimated_date ?? now()->format('Y-m-d')) }}"
                                    {{ @$quotation->estimated_date ? '' : 'disabled' }}>
                                @if (empty($quotation->estimated_date))
                                    <input type="date" name="estimated_date" id=""
                                        value="{{ now()->format('Y-m-d') }}" hidden>
                                @endif
                                <label for="estimatedDate">Quote Date</label>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="form-floating form-floating-outline mb-4">
                                <input class="form-control" type="date" id="expiredDate" name="expired_date"
                                    value="{{ old('expired_date', @$quotation->expired_date ?? '') }}">
                                <label for="expiredDate">Expired Date</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline mb-4">
                                <input class="form-control" type="text" placeholder="Put your No PR Here ...."
                                    id="no-pr-input" name="no_pr" {{ @$quotation ? '' : 'disabled' }}>
                                <label for="no-pr-input">No PR</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="Assigned" aria-label="Floating label select example"
                                    name="id_sales">
                                    <option disabled>-----Select Assigned-----</option>
                                    @foreach ($sales as $saless)
                                        <option value="{{ $saless->id }}"
                                            {{ $saless->id == Auth::user()->id ? 'selected' : '' }}>
                                            {{ $saless->name }}</option>
                                    @endforeach
                                </select>
                                <label for="Assigned">Assigned</label>
                            </div>
                        </div>
                    </div>
                    @if (@$dquotation)
                        @foreach ($dquotation as $quote)
                            @php
                                $id++;
                                $dataDetail++;
                            @endphp
                            <div class="mb-3" data-repeater-list="group-a">
                                <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item="">
                                    <div class="d-flex border rounded position-relative pe-0">
                                        <div class="row w-100 p-3">
                                            <div class="col-md-6 col-12 mb-md-0 mb-3">
                                                <label for="product" class="mb-2">Product</label>
                                                <input type="text" name="product[]" id="product"
                                                    class="form-control mb-3 product" placeholder="Example: Kaeser"
                                                    value="{{ old('product[]', $quote->product) }}">
                                                <textarea class="form-control" rows="2" placeholder="Detail Product. Example: Kaeser ASD" name="detail_product[]">{{ old('detail_product[]', $quote->detail_product) }}</textarea>
                                            </div>
                                            <div class="col-md-3 col-12 mb-md-0 mb-3">
                                                <p class="mb-2 repeater-title">Price</p>
                                                <div class="input-group" data-price="1">
                                                    <span class="input-group-text">Rp. </span>
                                                    <input type="text" class="form-control invoice-item-price-label"
                                                        id="price-label" data-id="1" min="12"
                                                        placeholder="Put Price Here" data-type="currency"
                                                        pattern="^[1-9]\d{0,2}(\.\d{3})*$"
                                                        value="{{ old('price[]', @$quote->price ? number_format(@$quote->price, 0, '', '.') : '') }}">
                                                    <input class="form-control invoice-item-price" type="number"
                                                        name="price[]" id="price-1"
                                                        value="{{ old('price[]', @$quote->price ?? '') }}" hidden>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-12 mb-md-0 mb-3">
                                                <p class="mb-2 repeater-title">Qty</p>
                                                <input type="number" class="form-control invoice-item-qty"
                                                    placeholder="Min 1" name="qty[]" id="qty-{{ $dataDetail }}"
                                                    data-id="{{ $dataDetail }}" min="1" max="50"
                                                    value="{{ old('qty[]', $quote->qty) }}">
                                            </div>
                                            <div class="col-md-1 col-12 mb-md-0 mb-3">
                                                <p class="mb-2 repeater-title">Discount</p>
                                                <input type="number" class="form-control invoice-item-disc"
                                                    placeholder="%" name="disc[]" id="disc-{{ $dataDetail }}"
                                                    data-id="{{ $dataDetail }}" min="1" max="50"
                                                    value="{{ old('disc[]', $quote->disc ?? '0') }}">
                                            </div>
                                            <div class="col-md-1 col-12 pe-0">
                                                <p class="mb-2 repeater-title">Amount</p>
                                                <p class="mb-0 amount-label" id="amount-label-1" data-id="1">
                                                    {{ old('amount[]', 'RP ' . number_format($quote->amount, 0, '', '.')) }}
                                                </p>
                                                <input type="number" class="form-control invoice-item-amount"
                                                    name="amount[]" id="amount-1" data-id="1" min="12"
                                                    value="{{ old('amount[]', $quote->amount) }}" hidden>
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
                        @endforeach
                    @else
                        <div class="mb-3" data-repeater-list="group-a">
                            <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item="">
                                <div class="d-flex border rounded position-relative pe-0">
                                    <div class="row w-100 p-3">
                                        <div class="col-md-6 col-12 mb-md-0 mb-3">
                                            <label for="product" class="mb-2">Product</label>
                                            <input type="text" name="product[]" id="product"
                                                class="form-control mb-3 product" placeholder="Put Your Part Number Here. Example: 6.641.13.1">
                                            <textarea class="form-control" rows="2" placeholder="Detail Product. Example: Kaeser ASD"
                                                name="detail_product[]"></textarea>
                                        </div>
                                        <div class="col-md-3 col-12 mb-md-0 mb-3">
                                            <p class="mb-2 repeater-title">Price</p>
                                            <div class="input-group" data-price="1">
                                                <span class="input-group-text">Rp. </span>
                                                <input type="text" class="form-control invoice-item-price-label"
                                                    id="price-label" data-id="1" min="12"
                                                    placeholder="Put Price Here" data-type="currency"
                                                    pattern="^[1-9]\d{0,2}(\.\d{3})*$" @focus="focused = true"
                                                    @blur="focused = false" value="{{ old('price[]') }}">
                                                <input class="form-control invoice-item-price" type="number"
                                                    name="price[]" id="price-1" value="{{ old('price[]') }}" hidden>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-12 mb-md-0 mb-3">
                                            <p class="mb-2 repeater-title">Qty</p>
                                            <input type="number" class="form-control invoice-item-qty"
                                                placeholder="Min 1" name="qty[]" id="qty-1" data-id="1"
                                                min="1" max="50" value="{{ old('qty[]') }}">
                                        </div>
                                        <div class="col-md-1 col-12 mb-md-0 mb-3">
                                            <p class="mb-2 repeater-title">Discount</p>
                                            <input type="number" class="form-control invoice-item-disc"
                                                placeholder="%" name="disc[]" id="disc-1" data-id="1"
                                                min="0" max="50" value="{{ old('disc[]', '0') }}">
                                        </div>
                                        <div class="col-md-1 col-12 pe-0">
                                            <p class="mb-2 repeater-title">Amount</p>
                                            <p class="mb-0 amount-label" id="amount-label-1" data-id="1">
                                                {{ old(strval('amount[]')) }}</p>
                                            <input type="number" class="form-control invoice-item-amount"
                                                name="amount[]" id="amount-1" data-id="1" min="12"
                                                value="{{ old('amount[]') }}" hidden>
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
                    @endif
                    <div class="row">
                        <div class="col-12 mb-2">
                            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light btn-add"
                                data-repeater-create="">
                                <i class="mdi mdi-plus me-1"></i> Add Item
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <h5 class="my-4">
                                Terms & Conditions :
                            </h5>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="validity">Validity Of Quotation</label>
                                <div class="col-sm-8">
                                    <input type="text" id="validity" class="form-control form-control-lg"
                                        name="validity"
                                        value="{{ old('validity', @$quotation->termncon[0]->validity ?? '1(one) Month After this Quotation Created') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="pricing">Price</label>
                                <div class="col-sm-8">
                                    <input type="text" id="pricing" class="form-control form-control-lg"
                                        name="pricing"
                                        value="{{ old('pricing', @$quotation->termncon[0]->pricing ?? 'Franco FACTORY ( BEKASI )') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="delivery">Delivery Process</label>
                                <div class="col-sm-8">
                                    <input type="text" id="delivery" class="form-control form-control-lg"
                                        value="{{ old('delivery_process', @$quotation->termncon[0]->delivery_process ?? 'Ready stock') }}"
                                        name="delivery_process">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="payment">Payment</label>
                                <div class="col-sm-8">
                                    <input type="text" id="payment" class="form-control form-control-lg"
                                        value="{{ old('payment', @$quotation->termncon[0]->payment ?? 'Cash Before Delivery') }}"
                                        name="payment">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="note">Note</label>
                                <div class="col-sm-8">
                                    <input type="text" id="note" class="form-control form-control-lg"
                                        value="{{ old('note', @$quotation->termncon[0]->note ?? '-') }}"
                                        name="note">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2"></div>
                        <div class="col-lg-4">
                            <div class="card shadow-none bg-light text-secondary border border-secondary mt-5 mb-3">
                                <div class="card-body ">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label text-sm-start" for="collapsible-pincode">Sub
                                            Total :</label>
                                        <div class="col-sm-8">
                                            <p class="mb-0 subtotal-label" id="subtotal-label" data-id="1">
                                                {{ old('subtotal', @$quotation->subtotal ? 'RP ' . number_format(@$quotation->subtotal, 0, '', '.') : '') }}
                                            </p>
                                            <input type="number" id="subtotal" class="form-control" name="subtotal"
                                                value="{{ old('subtotal', @$quotation->subtotal ?? '') }}" hidden>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-none bg-light text-secondary border border-secondary mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label text-sm-start" for="collapsible-tax">Tax
                                            :</label>
                                        <div class="col-sm-8">
                                            <select id="tax" class="form-select form-select-lg"
                                                style="background: none; border: none;" name="tax">
                                                <option disabled>-----Select Tax-----</option>
                                                <option value="0" {{ @$quotation->tax == '0' ? 'selected' : '' }}>
                                                    Without Tax</option>
                                                <option value="11" {{ @$quotation->tax == '11' ? 'selected' : '' }}>
                                                    <span> With PPN <small class="text-muted"> 11%</small> </span>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-none bg-light text-secondary border border-secondary mb-3">
                                <div class="card-body ">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label text-sm-start"
                                            for="collapsible-pincode">Shipping :</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-text" style="background: none; border: none;">Rp.
                                                </span>
                                                <input type="text" id="shipping-label" class="form-control"
                                                    placeholder="Shipping Cost Here....." data-type="currency"
                                                    style="background: none; border: none;"
                                                    pattern="^[0-9]\d{0,2}(\.\d{3})*$"
                                                    value="{{ old('shipping', @$quotation->shipping ? number_format(@$quotation->shipping, 0, '', '.') : '0') }}">
                                                <input type="number" name="shipping" id="shipping"
                                                    value="{{ old('shipping', @$quotation->shipping ?? '0') }}" hidden>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (@$quotation)
                                <div class="card shadow-none bg-light text-secondary border border-secondary mb-3">
                                    <div class="card-body ">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label text-sm-start"
                                                for="collapsible-pincode">Discount :</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-text"
                                                        style="background: none; border: none;">Rp.
                                                    </span>
                                                    <input type="text" id="diskon-label" class="form-control"
                                                        placeholder="Discount Here....." data-type="currency"
                                                        style="background: none; border: none;"
                                                        pattern="^[1-9]\d{0,2}(\.\d{3})*$"
                                                        value="{{ old('diskon', @$quotation->diskon ? number_format(@$quotation->diskon, 0, '', '.') : '0') }}">
                                                    <input type="number" name="diskon" id="diskon"
                                                        value="{{ old('diskon', @$quotation->diskon ?? '0') }}" hidden>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="card shadow-none bg-light text-secondary border border-secondary mb-3">
                                <div class="card-body ">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label text-sm-start"
                                            for="collapsible-pincode">Total
                                            :</label>
                                        <div class="col-sm-8">
                                            <p class="mb-0 harga-total-label" id="hargaTotalLabel" data-id="1">
                                                {{ old('harga_total', @$quotation->harga_total ? 'RP ' . number_format(@$quotation->harga_total, 0, '', '.') : '') }}
                                            </p>
                                            <input type="number" id="hargaTotal" class="form-control"
                                                name="harga_total"
                                                value="{{ old('harga_total', @$quotation->harga_total ?? '') }}" hidden>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="float-end">
                                <a href="{{ route('quotation.index') }}" type="button"
                                    class="btn btn-lg btn-outline-secondary">
                                    Back
                                </a>
                                <button :disabled="focused" type="submit" class="btn btn-lg btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
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
    <script src="{{ asset('assets') }}/includes/repeater/repeater-invoice.js"></script>
    <script src="{{ asset('assets') }}/js/app-invoice-add.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
@endpush
@push('script')
    <script>
        $(() => {
            // Format Integer menjadi Currency ID Rupiah
            let formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });

            // Jquery Dependency
            // formatting  shipping
            $("#shipping-label").on({
                keyup: function() {
                    formatCurrencyShipping($(this));
                }
            });
            // Formatting Discount Quotation
            $("#diskon-label").on({
                keyup: function() {
                    formatCurrencyDiscount($(this));
                }
            });

            function formatNumber(n) {
                return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }

            function formatCurrencyShipping(input) {
                var input_val = input.val();

                // don't validate empty input
                if (input_val === "") {
                    return;
                }

                // original length
                var original_len = input_val.length;

                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;

                // send updated string to input
                input.val(input_val);
                var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                $('#shipping').val(nomorInt);
            }

            function formatCurrencyDiscount(input) {
                var input_val = input.val();

                // don't validate empty input
                if (input_val === "") {
                    return;
                }

                // original length
                var original_len = input_val.length;

                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;

                // send updated string to input
                input.val(input_val);
                var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                $('#diskon').val(nomorInt);
            }

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
                console.log(id);
                $(`#price-${id}`).val(nomorInt);
            });

            // Logic amount + Subtotal
            $('.invoice-item-price-label, .invoice-item-qty, .invoice-item-disc').on('keyup change', function(ev) {
                var id = $(this).data('id');
                var sTotal = 0,
                    row = 0;
                var amount = 0,
                    hasil = 0,
                    disc = isNaN(parseInt($(`#disc-${id}`).val())) ? 0 : parseInt($(`#disc-${id}`).val());
                hasil = $(`#price-${id}`).val() * $(`#qty-${id}`).val();
                amount = (hasil - (hasil * disc / 100));
                $(`#amount-${id}`).val(amount);
                $(`#amount-label-${id}`).html(`${formatter.format(amount)}`);
                $('.amount-label').each(() => {
                    row++;
                    sTotal += parseInt($(`#amount-${row}`).val())
                })
                console.log(sTotal + "<total row>" + row);
                $('#subtotal-label').html(`${formatter.format(sTotal)}`);
                $('#subtotal').val(sTotal);
            });

            // Logic Subtotal dan Amount Setelah Tambah Product
            $('.btn-add').on('click', () => {
                $(".invoice-item-price-label").on('keyup', function() {
                    var input = $(this)
                    var id = input.data('id');
                    var input_val = input.val();

                    // don't validate empty input
                    if (input_val === "") {
                        return;
                    }

                    // original length
                    var original_len = input_val.length;

                    // initial caret position 
                    var caret_pos = input.prop("selectionStart");

                    // add commas to number
                    // remove all non-digits
                    input_val = formatNumber(input_val);
                    input_val = input_val;

                    // send updated string to input
                    input.val(input_val);
                    var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                    console.log(id);
                    $(`#price-${id}`).val(nomorInt);

                    // put caret back in the right position
                    var updated_len = input_val.length;
                    caret_pos = updated_len - original_len + caret_pos;
                    input[0].setSelectionRange(caret_pos, caret_pos);
                });
                $('.invoice-item-price-label, .invoice-item-qty, .invoice-item-disc').on('keyup change',
                    function(ev) {
                        var id = $(this).data('id');
                        var sTotal = 0,
                            row = 0;
                        var amount = 0,
                            hasil = 0,
                            disc = isNaN(parseInt($(`#disc-${id}`).val())) ? 0 : parseInt($(
                                `#disc-${id}`).val());
                        hasil = $(`#price-${id}`).val() * $(`#qty-${id}`).val();
                        amount = (hasil - (hasil * disc / 100));
                        $(`#amount-${id}`).val(amount);
                        $(`#amount-label-${id}`).html(`${formatter.format(amount)}`);
                        $('.amount-label').each(() => {
                            row++;
                            sTotal += parseInt($(`#amount-${row}`).val())
                        })
                        console.log(sTotal + "<total row>" + row);
                        $('#subtotal-label').html(`${formatter.format(sTotal)}`);
                        $('#subtotal').val(sTotal);
                    });
                // Logic Harga Total Setelah Tambah Product
                $('#tax').on('change', () => {
                    var sTotal = isNaN(parseInt($('#subtotal').val())) ? 0 : parseInt($('#subtotal')
                        .val());
                    var shipping = isNaN(parseInt($('#shipping').val())) ? 0 : parseInt($(
                        '#shipping').val());
                    var tax = isNaN(parseInt($('#tax').val())) ? 0 : parseInt($('#tax').val());
                    var hTotal = parseInt(sTotal + (sTotal * tax / 100) + shipping);
                    $('#hargaTotalLabel').html(`${formatter.format(hTotal)}`);
                    $('#hargaTotal').val(hTotal);
                    console.log(tax);
                })
                $('#subtotal, #shipping, .invoice-item-price-label, #diskon-label, .invoice-item-qty, .invoice-item-disc')
                    .on(
                        'keyup change', () => {
                            var hTotal = 0;
                            var sTotal = isNaN(parseInt($('#subtotal').val())) ? 0 : parseInt($('#subtotal')
                                .val());
                            var shipping = isNaN(parseInt($('#shipping').val())) ? 0 : parseInt($(
                                '#shipping').val());
                            var discount = isNaN(parseInt($('#discount').val())) ? 0 : parseInt($(
                                '#discount').val());
                            var tax = isNaN(parseInt($('#tax').val())) ? 0 : parseInt($('#tax').val());
                            console.log(tax);
                            hTotal = parseInt(sTotal + (sTotal * tax / 100) + shipping);
                            $('#hargaTotalLabel').html(`${formatter.format(hTotal)}`);
                            $('#hargaTotal').val(hTotal);
                            console.log("Harga Total :" + hTotal);
                        });
            });

            // Logic Harga Total
            $('#subtotal, #tax, #shipping-label, #diskon-label, .invoice-item-price-label, .invoice-item-qty, .invoice-item-disc')
                .on('keyup change',
                    () => {
                        var hTotal = 0;
                        var sTotal = isNaN(parseInt($('#subtotal').val())) ? 0 : parseInt($('#subtotal').val());
                        var shipping = isNaN(parseInt($('#shipping').val())) ? 0 : parseInt($('#shipping').val());
                        var discount = isNaN(parseInt($('#diskon').val())) ? 0 : parseInt($('#diskon').val());
                        console.log(discount);
                        var tax = isNaN(parseInt($('#tax').val())) ? 0 : parseInt($('#tax').val());
                        console.log(tax);
                        hTotal = parseInt(sTotal + (sTotal * tax / 100) + shipping - discount);
                        $('#hargaTotalLabel').html(`${formatter.format(hTotal)}`);
                        $('#hargaTotal').val(hTotal);
                        console.log("Harga Total :" + hTotal);
                    });
        })
    </script>
@endpush
