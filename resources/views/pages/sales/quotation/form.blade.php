@extends('layouts.sales.app')
@section('title', 'Create Quotation')
@section('content')
    <div class="form-floating mb-3">
        <input type="text" class="form-control fw-bold fs-3" id="floatingInputFilled" placeholder="John Doe"
            aria-describedby="floatingInputFilledHelp" name="no_quote" value="#RJO-XI-2023-105" disabled>
        <span class="form-floating-focused"></span>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="" class="form-invoice-repeater source-item" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12 col-lg-3 mb-3">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select" id="Clients" aria-label="Floating label select example"
                                name="id_client">
                                <option disabled>-----Select Clients-----</option>
                                @foreach ($client as $clients)
                                    <option value="{{ $clients->id }}">{{ $clients->company }}</option>
                                @endforeach
                            </select>
                            <label for="Clients">Clients</label>
                        </div>
                    </div>
                    <div class="col-lg-3"></div>
                    <div class="col-6 col-lg-3">
                        <div class="form-floating form-floating-outline mb-4">
                            <input class="form-control" type="date" id="expiredDate" name="expired_date">
                            <label for="expiredDate">Expired Date</label>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="form-floating form-floating-outline mb-4">
                            <input class="form-control" type="date" id="folupDate" name="folup_date">
                            <label for="folupDate">Follow Up Date</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <input class="form-control" type="text" placeholder="Materialize" id="html5-text-input">
                            <label for="html5-text-input">Address</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select" id="Assigned" aria-label="Floating label select example"
                                name="id_sales">
                                <option disabled>-----Select Assigned-----</option>
                                @foreach ($sales as $saless)
                                    <option value="{{ $saless->id }}">{{ $saless->name }}</option>
                                @endforeach
                            </select>
                            <label for="Assigned">Assigned</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3" data-repeater-list="group-a">
                    <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item="">
                        <div class="d-flex border rounded position-relative pe-0">
                            <div class="row w-100 p-3">
                                <div class="col-md-6 col-12 mb-md-0 mb-3">
                                    <label for="product" class="mb-2">Product</label>
                                    <input type="text" name="product" id="product" class="form-control mb-3 product"
                                        placeholder="Example: Kaeser">
                                    <textarea class="form-control" rows="2" placeholder="Detail Product. Example: Kaeser ASD" name="detail_product"></textarea>
                                </div>
                                <div class="col-md-3 col-12 mb-md-0 mb-3">
                                    <p class="mb-2 repeater-title">Price</p>
                                    <div class="input-group" data-price="1">
                                        <span class="input-group-text">Rp. </span>
                                        <input type="number" class="form-control invoice-item-price" name="price"
                                            id="price-1" data-id="1" min="12" placeholder="Put Price Here">
                                    </div>
                                </div>
                                <div class="col-md-1 col-12 mb-md-0 mb-3">
                                    <p class="mb-2 repeater-title">Qty</p>
                                    <input type="number" class="form-control invoice-item-qty" placeholder="Min 1"
                                        name="qty" id="qty-1" data-id="1" min="1" max="50">
                                </div>
                                <div class="col-md-1 col-12 mb-md-0 mb-3">
                                    <p class="mb-2 repeater-title">Discount</p>
                                    <input type="number" class="form-control invoice-item-disc" placeholder="Min 1"
                                        name="disc" id="disc-1" data-id="1" min="1" max="50">
                                </div>
                                <div class="col-md-1 col-12 pe-0">
                                    <p class="mb-2 repeater-title">Amount</p>
                                    <p class="mb-0 amount-label" id="amount-label-1" data-id="1"></p>
                                    <input type="number" class="form-control invoice-item-amount" name="amount"
                                        id="amount-1" data-id="1" min="12" hidden>
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
                <div class="row">
                    <div class="col-lg-8">
                        <label for="exampleFormControlTextarea1">
                            <h5 class="my-3">
                                Terms & Conditions :
                            </h5>
                        </label>
                        <textarea class="form-control h-px-200" id="exampleFormControlTextarea1" placeholder="Terms And Conditions..."
                            name="termcon"></textarea>
                    </div>
                    <div class="col-lg-4">
                        <div class="card shadow-none bg-light text-secondary border border-secondary mt-5 mb-3">
                            <div class="card-body ">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label text-sm-start" for="collapsible-pincode">Sub
                                        Total :</label>
                                    <div class="col-sm-8">
                                        <p class="mb-0 subtotal-label" id="subtotal-label" data-id="1"></p>
                                        <input type="number" id="subtotal" class="form-control" hidden>
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
                                        <div class="input-group">
                                            <input type="number" id="tax" class="form-control text-end"
                                                placeholder="Put Tax Here..." style="background: none; border: none;"
                                                max="100">
                                            <span class="input-group-text"
                                                style="background: none; border: none;">%</span>
                                        </div>
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
                                            <input type="number" id="shipping" class="form-control"
                                                placeholder="658468" style="background: none; border: none;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-none bg-light text-secondary border border-secondary mb-3">
                            <div class="card-body ">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label text-sm-start" for="collapsible-pincode">Total
                                        :</label>
                                    <div class="col-sm-8">
                                        <p class="mb-0 harga-total-label" id="hargaTotalLabel" data-id="1"></p>
                                        <input type="number" id="hargaTotal" class="form-control" hidden>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="float-end">
                            <button type="button" class="btn btn-lg btn-outline-secondary">
                                Back
                            </button>
                            <button type="submit" class="btn btn-lg btn-primary">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('after-style')
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/includes/repeater/jquery-repeater-invoice.js"></script>
    <script src="{{ asset('assets') }}/includes/repeater/repeater-invoice.js"></script>
    <script src="{{ asset('assets') }}/js/app-invoice-add.js"></script>
@endpush
@push('script')
    <script>
        $(() => {
            // Format Integer menjadi Currency ID Rupiah
            let formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });

            // PROBLEM : 1 tidak bisa mengambil per id

            // Logic amount + Subtotal
            $('.invoice-item-price, .invoice-item-qty, .invoice-item-disc').on('change', function(ev) {
                var id = $(this).data('id');
                console.log(id);
                var sTotal = 0,
                    row = 0;
                // id = $(`.invoice-item-price`).data("id");
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
            // ---------- OPSI A ----------
            // Walaupun ditambah data yabg di atasnya masih bisa di ubah price dan qty (belum dapat)

            // ---------- OPSI B ----------
            // Ketika sudah ditambah data yang diatasnya sudah takbisa ganti price

            // var id = 0;
            $('.btn-add').on('click', () => {
                $('.invoice-item-price, .invoice-item-qty, .invoice-item-disc').on('change', function(ev) {
                    var id = $(this).data('id');
                    console.log(id);
                    var sTotal = 0,
                        row = 0;
                    // id = $(`.invoice-item-price`).data("id");
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
            });
            // $('.btn-del').on('click', () => {
            //     id--;
            //     console.log(id);
            //     var sTotal = 0,
            //         row = 0;
            //     var amount = 0;
            //     amount = $(`#price-${id}`).val() * $(`#qty-${id}`).val();
            //     $(`#amount-${id}`).val(amount);
            //     $(`#amount-label-${id}`).html(`${formatter.format(amount)}`);
            //     $('.amount-label').each(() => {
            //         row++;
            //         sTotal += parseInt($(`#amount-${row}`).val());
            //     })
            //     console.log(sTotal + "<total row>" + row);
            //     $('#subtotal-label').html(`${formatter.format(sTotal)}`);
            //     $('#subtotal').val(sTotal);
            // });

            // Logic Harga Total
            $('#subtotal, #tax, #shipping').on('change', () => {
                var hTotal = 0;
                var sTotal = isNaN(parseInt($('#subtotal').val())) ? 0 : parseInt($('#subtotal').val());
                var tax = isNaN(parseInt($('#tax').val())) ? 0 : parseInt($('#tax').val());
                var shipping = isNaN(parseInt($('#shipping').val())) ? 0 : parseInt($('#shipping').val());
                hTotal = parseInt(sTotal + (sTotal * tax / 100) + shipping);
                $('#hargaTotalLabel').html(`${formatter.format(hTotal)}`);
                $('#hargaTotal').val(hTotal);
                console.log(hTotal);
            });
        })
    </script>
@endpush
