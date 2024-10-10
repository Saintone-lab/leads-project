<form action="{{ @$product ? route('product.update', @$product->id) : route('product.store') }}" method="post"
    enctype="multipart/form-data">
    {{-- {{ csrf_token() }} --}}
    @csrf

    @if (@$product)
        @method('patch')
    @endif
    <div class="modal animate__animated animate__fadeIn"
        id="{{ @$product ? 'updateProduct-' . @$product->id : 'createProduct' }}" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel5">
                        {{ @$product ? 'Update Unit' . @$product->commodity : 'Create Unit' }}
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
                                <input type="text" id="commodity" class="form-control" name="commodity"
                                    placeholder="W XXX" value="{{ old('commodity', @$product->commodity ?? '') }}">
                                <label for="commodity">Commodity</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="dimension" class="form-control" name="dimension"
                                    placeholder="xxx x xxx x xxx x"
                                    value="{{ old('dimension', @$product->dimension ?? '') }}">
                                <label for="dimension">Dismension</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6 col-md-3 mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select invoice-item-info" id="status"
                                    aria-label="Default select example" name="status">
                                    <option disabled>----- Info Status -----</option>
                                    <option value="Ready" {{ @$product->status == 'Ready' ? 'selected' : '' }}>Ready
                                    </option>
                                    <option value="On Rental" {{ @$product->status == 'On Rental' ? 'selected' : '' }}>On Rental
                                    </option>
                                    <option value="Sold" {{ @$product->status == 'Sold' ? 'selected' : '' }}>Sold
                                    </option>
                                </select>
                                <label for="exampleFormControlSelect1">Status</label>
                            </div>
                            <input type="text" id="unit" class="form-control" name="unit"
                            value="{{ old('unit', @$product->unit ?? 'Unit') }}" hidden>
                            <input type="text" id="type" class="form-control" name="type"
                            value="{{ old('type', @$product->type ?? 'Unit') }}" hidden>
                            <input type="text" id="description" class="form-control" name="description"
                            value="{{ old('description', @$product->description ?? 'Unit') }}" hidden>
                        </div>
                        <div class="col-6 col-md-3 mb-2 ">
                            <div class="input-group h-100">
                                <input type="number" name="weight" class="form-control" placeholder="Put Weight Here" aria-label="Put Weight Here" aria-describedby="basic-addon43" value="{{@$product->weight ?? ''}}">
                                <span class="input-group-text" id="basic-addon43">gr</span>
                              </div>
                        </div>
                        <div class="col-21 col-md-6 mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="category" aria-label="Default select example"
                                    name="category">
                                    <option disabled>----- Choose Category -----</option>
                                    <option value="Consumable Part"
                                        {{ @$product->category == 'Consumable Part' ? 'selected' : '' }}>
                                        Consumable Part
                                    </option>
                                    <option value="Non Consumable Part"
                                        {{ @$product->category == 'Non Consumable Part' ? 'selected' : '' }}>
                                        Non Consumable Part
                                    </option>
                                </select>
                                <label for="selectSource">Category</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="selectG/O" aria-label="Default select example"
                                    name="go">
                                    <option disabled>----- Choose G/O -----</option>
                                    <option value="Genuine" {{ @$product->go == 'Genuine' ? 'selected' : '' }}>
                                        Genuine
                                    </option>
                                    <option value="Replacement" {{ @$product->go == 'Replacement' ? 'selected' : '' }}>
                                        Replacement
                                    </option>
                                </select>
                                <label for="selectG/O">Genuine/Replacement
                                </label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="detail_desc" class="form-control" name="detail_desc"
                                    placeholder="Short Description"
                                    value="{{ old('detail_desc', @$product->detail_desc ?? '') }}">
                                <label for="detail_desc">Short Descroption</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col g-2">
                            <div class="form-floating form-floating-outline mb-4">
                                <textarea class="form-control h-px-100" name="note" id="noteTextarea1"
                                    placeholder="Contoh: Jl Taman Kopo Indah 5 Kota...">{{ old('note', @$product->note ?? '') }}</textarea>
                                <label for="noteTextarea1">note</label>
                            </div>
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
</form>
