    @extends('layouts.sales.app')
    @section('title', 'Create Purchase Order')
    @section('content')
        <form id="formAuthentication" class="mb-3 fv-plugins-bootstrap5 fv-plugins-framework"
            action="{{ @$purchase ? route('purchase.update', $purchase->id) : route('purchase.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @if (@$purchase)
                @method('patch')
            @endif
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
                        <div class="row mb-3 align-items-end">
                            <div class="col-12 col-lg-3">
                                <div class="form-floating form-floating-outline mb-2">
                                    <input type="text" class="form-control fw-bold text-primary border-primary"
                                        id="no_po_display" name="no_po" required
                                        value="{{ old('no_po', @$purchase->no_po ?? $previewNoPo ?? '') }}">
                                    <label for="no_po_display">No PO</label>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="form-floating form-floating-outline mb-2">
                                    <input class="form-control" type="date" id="date" name="date" required
                                        value="{{ old('date', @$purchase->date ?? \Carbon\Carbon::today()->format('Y-m-d')) }}">
                                    <label for="date">Date</label>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-floating form-floating-outline mb-2 flex-grow-1">
                                        <select id="supplier-dropdown" class="select2 form-select invoice-item-supplier"
                                            data-allow-clear="true" name="supplier" data-id="1" required
                                            {{ Auth::user()->role == 'Logistic' ? 'disabled' : '' }}>
                                            <option value="">Pilih Supplier...</option>
                                            @foreach ($suppliers as $supp)
                                                <option value="{{ $supp->id }}" data-info="{{ $supp->info }}"
                                                    data-code="{{ $supp->code }}"
                                                    {{ @$purchase->id_supplier == $supp->id ? 'selected' : '' }}>
                                                    {{ $supp->supplier }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="supplier-dropdown">Supplier</label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-label-secondary mb-2"
                                        data-bs-toggle="modal" data-bs-target="#quickAddSupplierModal"
                                        {{ Auth::user()->role == 'Logistic' ? 'disabled' : '' }}>
                                        <i class="mdi mdi-domain me-1"></i>Supplier Baru
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-lg-3">
                                <div class="form-floating form-floating-outline mb-2">
                                    <input class="form-control" type="text" placeholder="Put Delivery Time Here ...."
                                        id="delivery" name="delivery"
                                        value="{{ old('delivery', @$purchase->delivery ?? '') }}">
                                    <label for="delivery">Delivery Time</label>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <div class="form-floating form-floating-outline mb-2">
                                    <input class="form-control" type="text" placeholder="Put ATTN Quotation Here ...."
                                        id="attn" name="attn" value="{{ old('attn', @$purchase->attn ?? '') }}">
                                    <label for="attn">ATTN</label>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <div class="form-floating form-floating-outline mb-2">
                                    <input class="form-control" type="text" placeholder="Put Mobile Here ...."
                                        id="mobile" name="mobile" value="{{ old('mobile', @$purchase->mobile ?? '') }}">
                                    <label for="mobile">Mobile</label>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <div class="form-floating form-floating-outline mb-2">
                                    <input class="form-control" type="text" placeholder="text Payment Here ...."
                                        id="payment" name="payment"
                                        value="{{ old('payment', @$purchase->payment ?? '') }}">
                                    <label for="payment">Payment</label>
                                </div>
                            </div>
                        </div>
                        @if (@$purchase)
                            <div class="mb-2" data-repeater-list="group-a">
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($dPurchase as $item)
                                    <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item="">
                                        <div class="d-flex border rounded position-relative pe-0">
                                            <div class="row w-100 p-3">
                                                <input type="hidden" class="invoice-item-detail-id" name="detail_id[]"
                                                    value="{{ $item->id }}">
                                                <div class="col-md col-12 mb-md-0">
                                                    <label for="product" class="mb-2">Product</label>
                                                    <textarea class="form-control invoice-item-detail-product" rows="2"
                                                        id="detailProduct-{{ $no }}" placeholder="Detail Product. Example: Kaeser ASD" name="product[]">{{ $item->product }}</textarea>
                                                </div>
                                                <div class="col-md-3 col-12 mb-md-0 mb-3">
                                                    <p class="mb-2 repeater-title">Price</p>
                                                    <div class="input-group mb-3" data-price="{{ $no }}">
                                                        <span class="input-group-text">Rp. </span>
                                                        <input type="text" class="form-control invoice-item-price-label"
                                                            id="priceLabel-{{ $no }}"
                                                            data-id="{{ $no }}" name="harga"
                                                            placeholder="Put Price Here" data-type="currency"
                                                            min="0" pattern="^[0-9]\d{0,2}(\.\d{3})*$"
                                                            value="{{ number_format($item->price, '0', ',', '.') }}">
                                                        <input class="form-control invoice-item-price" type="number"
                                                            name="price[]" id="price-{{ $no }}"
                                                            value="{{ old('price[]', $item->price) }}" hidden>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-12 mb-md-0 mb-3">
                                                    <p class="mb-2 repeater-title">Qty</p>
                                                    <input type="number" class="form-control mb-4 invoice-item-qty"
                                                        placeholder="Min 1" name="qty[]" id="qty-{{ $no }}"
                                                        data-id="{{ $no }}" min="1"
                                                        value="{{ $item->qty }}">
                                                </div>
                                                <div class="col-md-1 col-12 mb-md-0 mb-3">
                                                    <p class="mb-2 repeater-title">Info Qty</p>
                                                    <div class="form-floating form-floating-outline mb-4">
                                                        <select class="form-select invoice-item-info"
                                                            id="info-qty-{{ $no }}"
                                                            data-id="{{ $no }}"
                                                            aria-label="Default select example" name="info_qty[]">
                                                            <option disabled>---Info---</option>
                                                            <option value="Pcs"
                                                                {{ $item->info_qty == 'Pcs' ? 'selected' : '' }}>Pcs
                                                            </option>
                                                            <option value="Set"
                                                                {{ $item->info_qty == 'Set' ? 'selected' : '' }}>Set
                                                            </option>
                                                            <option value="Pail"
                                                                {{ $item->info_qty == 'Pail' ? 'selected' : '' }}>Pail
                                                            </option>
                                                            <option value="Unit"
                                                                {{ $item->info_qty == 'Unit' ? 'selected' : '' }}>Unit
                                                            </option>
                                                            <option value="Lot"
                                                                {{ $item->info_qty == 'Lot' ? 'selected' : '' }}>Lot
                                                            </option>
                                                            <option value="Meter"
                                                                {{ $item->info_qty == 'Meter' ? 'selected' : '' }}>Meter
                                                            </option>
                                                            <option value="Can"
                                                                {{ $item->info_qty == 'Can' ? 'selected' : '' }}>Can
                                                            </option>
                                                            <option value="Hari"
                                                                {{ $item->info_qty == 'Hari' ? 'selected' : '' }}>Hari
                                                            </option>
                                                            <option value="Bulan"
                                                                {{ $item->info_qty == 'Bulan' ? 'selected' : '' }}>Bulan
                                                            </option>
                                                            <option value="Kg"
                                                                {{ $item->info_qty == 'Kg' ? 'selected' : '' }}>Kg</option>
                                                            <option value="Tube"
                                                                {{ $item->info_qty == 'Tube' ? 'selected' : '' }}>Tube
                                                            </option>
                                                            <option value="Titik"
                                                                {{ $item->info_qty == 'Titik' ? 'selected' : '' }}>Titik
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-12 mb-md-0 mb-3">
                                                    <p class="mb-2 repeater-title">Disc (%)</p>
                                                    <div class="input-group mb-3" data-disc="{{$no}}">
                                                        <input type="text" class="form-control invoice-item-disc"
                                                            id="disc-{{ $no }}" data-id="{{ $no }}"
                                                            name="disc[]" placeholder="%"
                                                            value="{{ old('disc[]', $item->disc) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-12 pe-0">
                                                    <p class="mb-2 repeater-title">Amount</p>
                                                    <p class="mb-0 amount-label" id="amount-label-{{$no}}" data-id="{{$no}}">
                                                        {{ number_format($item->amount, 0, ',', '.') }}</p>
                                                    <input type="number" class="form-control invoice-item-amount"
                                                        name="amount[]" id="amount-{{ $no }}"
                                                        data-id="{{ $no }}"
                                                        value="{{ old('amount[]', $item->amount) }}" hidden>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                                <i class="mdi mdi-close cursor-pointer bg-danger text-white btn-del"
                                                    data-repeater-delete=""></i>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $no++;
                                    @endphp
                                @endforeach
                            </div>
                        @else
                            <div class="mb-2" data-repeater-list="group-a">
                                <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item="">
                                    <div class="d-flex border rounded position-relative pe-0">
                                        <div class="row w-100 p-3">
                                            <input type="hidden" class="invoice-item-detail-id" name="detail_id[]"
                                                value="">
                                            <div class="col-md col-12 mb-md-0">
                                                <label for="product" class="mb-2">Product</label>
                                                <textarea class="form-control invoice-item-detail-product" rows="2" id="detailProduct-1"
                                                    placeholder="Detail Product. Example: Kaeser ASD" name="product[]"></textarea>
                                            </div>
                                            <div class="col-md-3 col-12 mb-md-0 mb-3">
                                                <p class="mb-2 repeater-title">Price</p>
                                                <div class="input-group mb-3" data-price="1">
                                                    <span class="input-group-text">Rp. </span>
                                                    <input type="text" class="form-control invoice-item-price-label"
                                                        id="priceLabel-1" data-id="1" name="harga"
                                                        placeholder="Put Price Here" data-type="currency" min="0"
                                                        pattern="^[0-9]\d{0,2}(\.\d{3})*$"
                                                        value="{{ old('price[]') }}">
                                                    <input class="form-control invoice-item-price" type="number"
                                                        name="price[]" id="price-1" value="{{ old('price[]') }}"
                                                        hidden>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-12 mb-md-0 mb-3">
                                                <p class="mb-2 repeater-title">Qty</p>
                                                <input type="number" class="form-control mb-4 invoice-item-qty"
                                                    placeholder="Min 1" name="qty[]" id="qty-1" data-id="1"
                                                    min="1" value="{{ old('qty[]') }}">
                                            </div>
                                            <div class="col-md-1 col-12 mb-md-0 mb-3">
                                                <p class="mb-2 repeater-title">Info Qty</p>
                                                <div class="form-floating form-floating-outline mb-4">
                                                    <select class="form-select invoice-item-info" id="info-qty-1"
                                                        data-id="1" aria-label="Default select example"
                                                        name="info_qty[]">
                                                        <option disabled>---Info---</option>
                                                        <option value="Pcs">Pcs</option>
                                                        <option value="Set">Set</option>
                                                        <option value="Pail">Pail</option>
                                                        <option value="Unit">Unit</option>
                                                        <option value="Lot">Lot</option>
                                                        <option value="Meter">Meter</option>
                                                        <option value="Can">Can</option>
                                                        <option value="Hari">Hari</option>
                                                        <option value="Bulan">Bulan</option>
                                                        <option value="Kg">Kg</option>
                                                        <option value="Tube">Tube</option>
                                                        <option value="Titik">Titik</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-12 mb-md-0 mb-3">
                                                <p class="mb-2 repeater-title">Disc (%)</p>
                                                <div class="input-group mb-3" data-disc="1">
                                                    <input type="text" class="form-control invoice-item-disc"
                                                        id="disc-1" data-id="1" name="disc[]" placeholder="%"
                                                        value="{{ old('disc[]', 0) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-12 pe-0">
                                                <p class="mb-2 repeater-title">Amount</p>
                                                <p class="mb-0 amount-label" id="amount-label-1" data-id="1">
                                                    {{ old(strval('amount[]')) }}</p>
                                                <input type="number" class="form-control invoice-item-amount"
                                                    name="amount[]" id="amount-1" data-id="1"
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
                                    Note :
                                </h5>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label" for="note">Note</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control h-px-100" rows="2" placeholder="Write your note here...." name="note">{{ @$purchase->note }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4">
                                <div class="card border mt-5 mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center py-2">
                                            <span class="text-muted">Sub Total</span>
                                            <div class="text-end">
                                                <span class="fw-semibold subtotal-label" id="subtotal-label"
                                                    data-id="1">
                                                    {{ old('subtotal', @$purchase->subtotal ? 'RP ' . number_format(@$purchase->subtotal, 0, '', '.') : 'RP 0') }}
                                                </span>
                                                <input type="number" id="subtotal" name="subtotal"
                                                    value="{{ old('subtotal', @$purchase->subtotal ?? '') }}" hidden>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-2 border-top">
                                            <span class="text-muted">Discount</span>
                                            <div class="input-group input-group-sm w-auto">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" id="diskon-label" class="form-control text-end"
                                                    placeholder="0" data-type="currency"
                                                    pattern="^[0-9]\d{0,2}(\.\d{3})*$"
                                                    value="{{ old('diskon', @$purchase->diskon ? number_format(@$purchase->diskon, 0, '', '.') : '0') }}">
                                                <input type="number" name="diskon" id="diskon"
                                                    value="{{ old('diskon', @$purchase->diskon ?? '0') }}" hidden>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-2 border-top">
                                            <span class="text-muted">Tax (PPN 12%)</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-semibold tax-amount-label" id="taxAmountLabel">
                                                    @if (@$purchase && $purchase->vat == '11')
                                                        {{ 'RP ' . number_format(($purchase->subtotal - $purchase->diskon) * $purchase->vat / 100, 0, '', '.') }}
                                                    @endif
                                                </span>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="taxSwitch" {{ @$purchase->vat == '11' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            <input type="hidden" id="tax" name="tax"
                                                value="{{ old('tax', @$purchase->vat ?? '0') }}">
                                        </div>
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between align-items-center py-1">
                                            <span class="fw-bold">Total</span>
                                            <div class="text-end">
                                                <span class="fw-bold fs-4 text-primary harga-total-label"
                                                    id="hargaTotalLabel" data-id="1">
                                                    {{ old('harga_total', @$purchase->total ? 'RP ' . number_format(@$purchase->total, 0, '', '.') : 'RP 0') }}
                                                </span>
                                                <input type="number" id="hargaTotal" name="harga_total"
                                                    value="{{ old('harga_total', @$purchase->total ?? '') }}" hidden>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="float-end">
                                    <a href="{{ route('purchase.index') }}" type="button"
                                        class="btn btn-lg btn-outline-secondary">
                                        Back
                                    </a>
                                    <button type="submit" class="btn btn-lg btn-primary">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade" id="quickAddSupplierModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Supplier Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="qsCode" placeholder="SUP001">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="qsName" placeholder="PT Contoh Jaya">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Info <span class="text-danger">*</span></label>
                            <select class="form-select" id="qsInfo">
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="Lokal">Lokal</option>
                                <option value="Import">Import</option>
                            </select>
                        </div>
                        <div id="qsError" class="alert alert-danger d-none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="saveQuickAddSupplier">
                            <i class="mdi mdi-content-save-outline me-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('after-style')
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
    @endpush
    @push('after-script')
        <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
        <script src="{{ asset('assets') }}/includes/repeater/jquery-repeater-invoice.js"></script>
        <script src="{{ asset('assets') }}/js/app-invoice-add.js"></script>
    @endpush
    @push('page-script')
        <script src="{{ asset('assets') }}/includes/repeater/repeater-invoice.js"></script>
        <script src="{{ asset('assets') }}/includes/validator/quotation-validation.js"></script>
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

                // Tampilkan Badge - Code - Nama Supplier
                function renderSupplierOption(option) {
                    if (!option.id) {
                        return option.text;
                    }
                    var info = $(option.element).data('info');
                    var code = $(option.element).data('code');
                    var $wrapper = $('<span></span>');
                    if (info) {
                        var badgeClass = info === 'Lokal' ? 'bg-label-info' : 'bg-label-primary';
                        $wrapper.append($('<span></span>').addClass('badge ' + badgeClass).text(info));
                        $wrapper.append(' ');
                    }
                    if (code) {
                        $wrapper.append($('<span></span>').addClass('text-muted').text(code));
                        $wrapper.append(' ');
                    }
                    $wrapper.append(document.createTextNode(option.text));
                    return $wrapper;
                }
                var $supplierDropdown = $('#supplier-dropdown');
                if ($supplierDropdown.data('select2')) {
                    $supplierDropdown.select2('destroy');
                }
                $supplierDropdown.select2({
                    placeholder: 'Pilih Supplier...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $supplierDropdown.parent(),
                    templateResult: renderSupplierOption,
                    templateSelection: renderSupplierOption,
                    escapeMarkup: function(m) {
                        return m;
                    }
                });

                // Quick Add Supplier (AJAX, tanpa reload)
                $('#saveQuickAddSupplier').on('click', function() {
                    var code = $('#qsCode').val().trim();
                    var name = $('#qsName').val().trim();
                    var info = $('#qsInfo').val();

                    if (!code || !name || !info) {
                        $('#qsError').removeClass('d-none').text('Semua field wajib diisi.');
                        return;
                    }

                    $.ajax({
                        url: '{{ route('supplier.quick-store') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            code: code,
                            supplier: name,
                            info: info
                        },
                        success: function(res) {
                            if (res.success) {
                                var newSupplier = res.data;
                                var option = new Option(newSupplier.supplier, newSupplier.id, true, true);
                                $(option).attr('data-info', newSupplier.info);
                                $(option).attr('data-code', newSupplier.code);
                                $supplierDropdown.append(option).trigger('change');

                                $('#qsCode').val('');
                                $('#qsName').val('');
                                $('#qsInfo').val('');
                                $('#qsError').addClass('d-none').text('');
                                $('#quickAddSupplierModal').modal('hide');
                            }
                        },
                        error: function(xhr) {
                            var msg = xhr.responseJSON && xhr.responseJSON.message ?
                                xhr.responseJSON.message : 'Gagal menyimpan, coba lagi.';
                            $('#qsError').removeClass('d-none').text(msg);
                        }
                    });
                });

                function formatNumber(n) {
                    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                }

                function formatCurrencyDiscount(input) {
                    var input_val = input.val();

                    // don't validate empty input
                    if (input_val === "") {
                        return;
                    }

                    // add commas to number
                    // remove all non-digits
                    input_val = formatNumber(input_val);

                    // send updated string to input
                    input.val(input_val);
                    var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                    $('#diskon').val(nomorInt);
                }

                // Formatting Discount Quotation (delegated: works for existing + future rows)
                $(document).on('keyup', '#diskon-label', function() {
                    formatCurrencyDiscount($(this));
                });

                // Toggle Tax (PPN 12%) on/off
                $(document).on('change', '#taxSwitch', function() {
                    $('#tax').val($(this).is(':checked') ? 11 : 0).trigger('change');
                });

                $(document).on('keyup', '.invoice-item-price-label', function() {
                    var input = $(this)
                    var id = input.data('id');
                    var input_val = input.val();

                    // original length
                    var original_len = input_val.length;

                    // initial caret position
                    var caret_pos = input.prop("selectionStart");

                    // add commas to number
                    // remove all non-digits
                    input_val = formatNumber(input_val);

                    // send updated string to input
                    input.val(input_val);
                    var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                    $(`#price-${id}`).val(nomorInt);

                    // put caret back in the right position
                    var updated_len = input_val.length;
                    caret_pos = updated_len - original_len + caret_pos;
                    input[0].setSelectionRange(caret_pos, caret_pos);
                });

                // Logic amount + Subtotal
                $(document).on('keyup change click', '.invoice-item-price-label, .invoice-item-qty, .invoice-item-disc',
                    function(ev) {
                        // mengambil ID
                        var id = $(this).data('id');
                        // prepare data
                        var sTotal = 0,
                            row = 0,
                            amount = 0,
                            hasil = 0,
                            valHarga = $(`#price-${id}`).val(),
                            harga = Number(valHarga),
                            disc = isNaN(parseInt($(`#disc-${id}`).val())) ? 0 : parseInt($(`#disc-${id}`).val());
                        // menghitung hasil
                        hasil = harga * $(`#qty-${id}`).val();
                        // menghitung amount
                        amount = (hasil - (hasil * disc / 100));
                        // memasukan data amount dan subtotal
                        $(`#amount-${id}`).val(amount);
                        $(`#amount-label-${id}`).html(`${formatter.format(amount)}`);
                        $('.amount-label').each(() => {
                            row++;
                            sTotal += parseInt($(`#amount-${row}`).val())
                        });
                        $('#subtotal-label').html(`${formatter.format(sTotal)}`);
                        $('#subtotal').val(sTotal);
                    });

                // Logic Harga Total
                $(document).on('keyup change',
                    '#subtotal, #tax, #diskon-label, .invoice-item-price-label, .invoice-item-qty, .invoice-item-disc',
                    () => {
                        var noTax = 0;
                        var hTotal = 0;
                        var sTotal = isNaN(parseInt($('#subtotal').val())) ? 0 : parseInt($('#subtotal').val());
                        var discount = isNaN(parseInt($('#diskon').val())) ? 0 : parseInt($('#diskon').val());
                        var dTotal = sTotal - discount;
                        var tax = isNaN(parseInt($('#tax').val())) ? 0 : parseInt($('#tax').val());
                        var taxAmount = parseInt(dTotal * tax / 100);
                        hTotal = parseInt(dTotal + taxAmount);
                        noTax = parseInt(dTotal);
                        $('#taxAmountLabel').html(tax > 0 ? formatter.format(taxAmount) : '');
                        $('#hargaTotalLabel').html(`${formatter.format(hTotal)}`);
                        $('#hargaTotal').val(hTotal);
                        $('#totalNoTax').val(noTax);
                    });
            })
        </script>
    @endpush
