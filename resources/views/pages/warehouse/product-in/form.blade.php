@extends('layouts.sales.app')
@section('title', 'Product In')
@section('content')
    <div class="form-floating mb-3">
        <input type="text" class="form-control form-control-lg fw-bold fs-3" id="floatingInputFilled"
            placeholder="xxx/xx/xx/xxxx xxxx" aria-describedby="floatingInputFilledHelp">
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
                                id="supplier-input" name="supplier"
                                value="{{ old('supplier', @$productIn->supplier ?? '') }}">
                            <label for="supplier-input">Suplier</label>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="form-floating form-floating-outline mb-4">
                            <input class="form-control" type="date" id="Date" name="date" {{-- {{ @$productIn->date ? '' : '_label' }}  naikin nanti --}}
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
                                        <select id="select2Basic" class="select2 form-select" data-allow-clear="true"
                                            name="id_pic">
                                            <option> WR 940 </option>
                                            <option> WR 946 </option>
                                            <option> W 712 </option>
                                            <option> W 714 </option>
                                        </select>
                                        <label for="select2Basic">Commodity</label>
                                    </div>
                                    <textarea class="form-control" rows="2" placeholder="Detail Product. Example: Kaeser ASD" name="detail_product[]"></textarea>
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
                                        <input type="text" class="form-control invoice-item-price-label" id="price-label"
                                            data-id="1" min="12" placeholder="Put Price Here" data-type="currency"
                                            pattern="^[1-9]\d{0,2}(\.\d{3})*$" @focus="focused = true"
                                            @blur="focused = false" value="{{ old('price[]') }}">
                                        <input class="form-control invoice-item-price" type="number" name="price[]"
                                            id="price-1" value="{{ old('price[]') }}" hidden>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12 pe-0">
                                    <p class="mb-2 repeater-title">Amount</p>
                                    <p class="mb-0 amount-label" id="amount-label-1" data-id="1">
                                        {{ old(strval('amount[]')) }}</p>
                                    <input type="number" class="form-control invoice-item-amount" name="amount[]"
                                        id="amount-1" data-id="1" min="12" value="{{ old('amount[]') }}" hidden>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                <i class="mdi mdi-close cursor-pointer bg-danger text-white btn-del"
                                    data-repeater-delete=""></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-2">
                        <button type="button" class="btn btn-sm btn-primary waves-effect waves-light btn-add"
                            data-repeater-create="">
                            <i class="mdi mdi-plus me-1"></i> Add Item
                        </button>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-8"></div>
                    <div class="col-lg-4 col-12">
                        <h5 class="my-2">
                            Note
                        </h5>
                        <textarea class="form-control h-px-100" rows="2" placeholder="Write your note here...."
                            name="note"></textarea>
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
@endsection
