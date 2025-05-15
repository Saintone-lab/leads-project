<form action="{{ @$product ? route('unit.update', @$product->id) : route('unit-global.store') }}" method="post"
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
                                <input type="text" id="sku" class="form-control" name="sku"
                                    placeholder="W XXX" value="{{ old('sku', @$product->sku ?? '') }}">
                                <label for="sku">SKU</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select invoice-item-info" id="unit"
                                    aria-label="Default select example" name="unit">
                                    <option disabled>----- Unit -----</option>
                                    <option value="AIR COMPRESSOR SCREW"
                                        {{ @$product->status == 'AIR COMPRESSOR SCREW' ? 'selected' : '' }}>AIR COMPRESSOR SCREW
                                    </option>
                                    <option value="REFRIGERANT AIR DRYER"
                                        {{ @$product->status == 'REFRIGERANT AIR DRYER' ? 'selected' : '' }}>
                                        REFRIGERANT AIR DRYER
                                    </option>
                                    <option value="FILTER HOUSING"
                                        {{ @$product->status == 'FILTER HOUSING' ? 'selected' : '' }}>FILTER HOUSING
                                    </option>
                                    <option value="AIR RECEIVER TANK"
                                        {{ @$product->status == 'AIR RECEIVER TANK' ? 'selected' : '' }}>
                                        AIR RECEIVER TANK
                                    </option>
                                    <option value="WATER CHILLER"
                                        {{ @$product->status == 'WATER CHILLER' ? 'selected' : '' }}>
                                        WATER CHILLER
                                    </option>
                                    <option value="DESICANT DRYER"
                                        {{ @$product->status == 'DESICANT DRYER' ? 'selected' : '' }}>
                                        DESICANT DRYER
                                    </option>
                                    <option value="BOOSTER COMPRESSOR"
                                        {{ @$product->status == 'BOOSTER COMPRESSOR' ? 'selected' : '' }}>
                                        BOOSTER COMPRESSOR
                                    </option>
                                    <option value="HIGH PRESSURE COMPRESSOR"
                                        {{ @$product->status == 'HIGH PRESSURE COMPRESSOR' ? 'selected' : '' }}>
                                        HIGH PRESSURE COMPRESSOR
                                    </option>
                                </select>
                                <label for="exampleFormControlSelect1">Unit</label>
                            </div>
                            {{-- <div class="form-floating form-floating-outline">
                                <div class="select2-primary">
                                    <select id="select2Primary" class="select2 form-select" name="unit[]" multiple>
                                        <option value="rental" {{ @$product->rental == '1' ? 'selected' : '' }}> Rental
                                        </option>
                                        <option value="second" {{ @$product->second == '1' ? 'selected' : '' }}> Second
                                            Unit </option>
                                        <option value="new" {{ @$product->new == '1' ? 'selected' : '' }}> New
                                            Unit </option>
                                    </select>
                                </div>
                                <label for="select2Primary">Unit</label>
                            </div> --}}
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="desc" class="form-control" name="desc"
                                    placeholder="Short Description" value="{{ old('desc', @$product->desc ?? '') }}">
                                <label for="desc">Short Description</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6 g-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="voltage" class="form-control" name="voltage"
                                    placeholder="Voltage" value="{{ old('voltage', @$product->voltage ?? '') }}">
                                <label for="voltage">Voltage</label>
                            </div>
                        </div>
                        <div class="col g-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="bar" class="form-control" name="bar"
                                    placeholder="Bar" value="{{ old('bar', @$product->bar ?? '') }}">
                                <label for="bar">Bar</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="power" class="form-control" name="power"
                                    placeholder="Power" value="{{ old('power', @$product->power ?? '') }}">
                                <label for="power">Power</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="air_cap" class="form-control" name="air_cap"
                                    placeholder="Air Capacity" value="{{ old('air_cap', @$product->air_cap ?? '') }}">
                                <label for="air_cap">Air Capacity</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="connect" class="form-control" name="connect"
                                    placeholder="Connection" value="{{ old('connect', @$product->connect ?? '') }}">
                                <label for="connect">Connection</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="dimension" class="form-control" name="dimension"
                                    placeholder="Dimension"
                                    value="{{ old('dimension', @$product->dimension ?? '') }}">
                                <label for="dimension">Dimension</label>
                            </div>
                        </div>
                        <div class="col g-2">
                            <div class="form-floating form-floating-outline input-group">
                                <input type="number" class="form-control" placeholder="Weight" min="0"
                                    name="weight"value="{{ old('weight', @$product->weight ?? '') }}">
                                <span class="input-group-text">Kg</span>
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
