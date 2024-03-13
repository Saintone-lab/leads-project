<form action="{{ route('product.equivalent', $product->id) }}" method="post" enctype="multipart/form-data">
    {{-- {{ csrf_token() }} --}}
    @csrf

    {{-- @if (@$product)
        @method('patch')
    @endif --}}
    <div class="modal animate__animated animate__fadeIn" id="createEquivalent-{{ $product->id }}" tabindex="-1"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel5"> Create Equivalent
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="fxp" class="form-control" name="fxp"
                                    placeholder="W XXX" value="{{ old('fxp_parts', @$product->fxp_parts ?? '') }}">
                                <label for="fxp">Fxp Parts</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="brand" class="form-control" name="brand"
                                    placeholder="xxx x xxx x xxx x" value="{{ old('brand', @$product->brand ?? '') }}">
                                <label for="brand">Brand</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="pn" class="form-control" name="pn"
                                    placeholder="xxxx@xxx.xx" value="{{ old('pn', @$product->pn ?? '') }}">
                                <label for="pn">Part Number</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="input-group form-floating form-floating-outline" data-price="1">
                                <span class="input-group-text">Rp. </span>
                                <input type="text" class="form-control invoice-item-price-label" id="price-label"
                                    data-id="1" min="12" placeholder="Put Price Here" data-type="currency"
                                    pattern="^[1-9]\d{0,2}(\.\d{3})*$" @focus="focused = true" @blur="focused = false"
                                    value="{{ old('price') }}">
                                <input class="form-control invoice-item-price" type="number" name="price"
                                    id="price" value="{{ old('price') }}" hidden>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary waves-effect"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
