<form action="" method="post" enctype="multipart/form-data">
    {{-- {{ csrf_token() }} --}}
    @csrf
    <div class="modal animate__animated animate__zoomIn" id="createLeads" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel5">Create New Leads</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            {{-- <div class="form-floating form-floating-outline">
                        <div class="dropdown bootstrap-select w-100"><select id="selectpickerBasic"
                                class="selectpicker w-100" data-style="btn-default" tabindex="null">
                                <option>Rocky</option>
                                <option>Pulp Fiction</option>
                                <option>The Godfather</option>
                            </select><button type="button" tabindex="-1" class="btn dropdown-toggle btn-default"
                                data-bs-toggle="dropdown" role="combobox" aria-owns="bs-select-1"
                                aria-haspopup="listbox" aria-expanded="false" title="Rocky"
                                data-id="selectpickerBasic">
                                <div class="filter-option">
                                    <div class="filter-option-inner">
                                        <div class="filter-option-inner-inner">Rocky</div>
                                    </div>
                                </div>
                            </button>
                            <div class="dropdown-menu ">
                                <div class="inner show" role="listbox" id="bs-select-1" tabindex="-1">
                                    <ul class="dropdown-menu inner show" role="presentation"></ul>
                                </div>
                            </div>
                        </div>
                        <label for="selectpickerBasic">Basic</label>
                    </div> --}}
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="selectSales" aria-label="Default select example">
                                    <option disabled>----- Choose Sales -----</option>
                                    @foreach ($sales as $saless)
                                        <option value="{{ $saless->id }}">{{ $saless->name }}</option>
                                    @endforeach
                                </select>
                                <label for="selectSales">Sales</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="pic" id="picAnimation" class="form-control" placeholder="Mr/Mss xxxx">
                                <label for="picAnimation">Company</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="email" id="emailAnimation" class="form-control"
                                    placeholder="xxxx@xxx.xx">
                                <label for="emailAnimation">Email</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="phone" id="phoneAnimation" class="form-control" placeholder="081xxxxx">
                                <label for="phoneAnimation">Phone</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="websiteAnimation" class="form-control"
                                    placeholder="xxxxxxxxx.com">
                                <label for="websiteAnimation">Website</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="selectSource" aria-label="Default select example">
                                    <option disabled>----- Choose Source -----</option>
                                    <option value="IG">Instagram</option>
                                    <option value="LinkedIn">LinkedIn</option>
                                    <option value="Website">Website</option>
                                    <option value="Iklan">Iklan</option>
                                </select>
                                <label for="selectSource">Source</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="selectMobile" aria-label="Default select example">
                                    <option disabled>----- Choose Mobile -----</option>
                                    <option value="WA">WhatsApp</option>
                                    <option value="Phone Office">Phone Office</option>
                                </select>
                                <label for="selectMobile">Mobile</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="addressAnimation" class="form-control"
                                    placeholder="Contoh: Bandung">
                                <label for="addressAnimation">Address</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="selectMachine" aria-label="Default select example">
                                    <option disabled>----- Choose Machine -----</option>
                                    @foreach ($dcompressor as $compressor)
                                        <option value="{{ $compressor->id }}">{{ $compressor->serial_number }} ||
                                            {{ $compressor->compressor->compressor_brand }},Type
                                            {{ $compressor->compressor->series }} , {{ $compressor->hp }} HP</option>
                                    @endforeach
                                </select>
                                <label for="selectMachine">Machine</label>
                            </div>
                        </div>
                    </div>
                    <div class="divider divider-dark mx-3">
                        <div class="divider-text"><span class="fw-semibold">Personal In Charge</span></div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="nameAnimation" class="form-control"
                                    placeholder="xxxxxxx xxxxxxxx">
                                <label for="nameAnimation">Name</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="positionAnimation" class="form-control"
                                    placeholder="example: CEO">
                                <label for="positionAnimation">Position</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="emailPicAnimation" class="form-control"
                                    placeholder="xxxxxxxx@xxx.xx">
                                <label for="emailPicAnimation">Email PIC</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="phone" id="phonePicAnimation" class="form-control"
                                    placeholder="08xxxxxxxxxx">
                                <label for="phonePicAnimation">Phone PIC</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary waves-effect"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</form>


@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/bootstrap-select/bootstrap-select.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
@endpush
