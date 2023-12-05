@extends('layouts.sales.app')
@section('title', 'Create Quotation')
@section('content')
    <h3 class="fw-semibold py-3 mb-4">
        #RJO-XI-2023-105
    </h3>
    <hr>
    <div class="card">
        <div class="card-body">
            <form action="" class="form-repeater source-item" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12 col-lg-3 mb-3">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select" id="Customers" aria-label="Floating label select example">
                                <option>-----Select Customers-----</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <label for="Customers">Customers</label>
                        </div>
                    </div>
                    <div class="col-lg-3"></div>
                    <div class="col-6 col-lg-3">
                        <div class="form-floating form-floating-outline mb-4">
                            <input class="form-control" type="date" id="html5-date-input">
                            <label for="html5-date-input">Date</label>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="form-floating form-floating-outline mb-4">
                            <input class="form-control" type="date" id="html5-date-input">
                            <label for="html5-date-input">Date</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <input class="form-control" type="text" placeholder="Materialize" id="html5-text-input">
                            <label for="html5-text-input">Address</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <input class="form-control" type="text" placeholder="Materialize" id="html5-text-input">
                            <label for="html5-text-input">Assigned</label>
                        </div>
                    </div>
                </div>
                {{-- <div data-repeater-list="group-a">
                    <div data-repeater-item="">
                        <div class="row">
                            <div class=" col-lg-6 col-xl-3 col-12 mb-0">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="form-repeater-1-1" class="form-control"
                                        placeholder="john.doe">
                                    <label for="form-repeater-1-1">Username</label>
                                </div>
                            </div>
                            <div class=" col-lg-6 col-xl-3 col-12 mb-0">
                                <div class="form-floating form-floating-outline">
                                    <input type="password" id="form-repeater-1-2" class="form-control"
                                        placeholder="············">
                                    <label for="form-repeater-1-2">Password</label>
                                </div>
                            </div>
                            <div class=" col-lg-6 col-xl-2 col-12 mb-0">
                                <div class="form-floating form-floating-outline">
                                    <select id="form-repeater-1-3" class="form-select">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <label for="form-repeater-1-3">Gender</label>
                                </div>
                            </div>
                            <div class=" col-lg-6 col-xl-2 col-12 mb-0">
                                <div class="form-floating form-floating-outline">
                                    <select id="form-repeater-1-4" class="form-select">
                                        <option value="Designer">Designer</option>
                                        <option value="Developer">Developer</option>
                                        <option value="Tester">Tester</option>
                                        <option value="Manager">Manager</option>
                                    </select>
                                    <label for="form-repeater-1-4">Profession</label>
                                </div>
                            </div>
                            <div class=" col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                <button class="btn btn-label-danger waves-effect" data-repeater-delete="">
                                    <i class="mdi mdi-close me-1"></i>
                                    <span class="align-middle">Delete</span>
                                </button>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="mb-0">
                    <button class="btn btn-primary waves-effect waves-light" data-repeater-create="">
                        <i class="mdi mdi-plus me-1"></i>
                        <span class="align-middle">Add</span>
                    </button>
                </div> --}}
                <div class="mb-3" data-repeater-list="group-a">
                    <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item="">
                        <div class="d-flex border rounded position-relative pe-0">
                            <div class="row w-100 p-3">
                                <div class="col-md-6 col-12 mb-md-0 mb-3">
                                    <p class="mb-2 repeater-title">Item</p>
                                    <select class="form-select item-details mb-3">
                                        <option selected="" disabled="">Select Item</option>
                                        <option value="App Design">App Design</option>
                                        <option value="App Customization">App Customization</option>
                                        <option value="ABC Template">ABC Template</option>
                                        <option value="App Development">App Development</option>
                                    </select>
                                    <textarea class="form-control" rows="2" placeholder="Item Information" disabled></textarea>
                                </div>
                                <div class="col-md-3 col-12 mb-md-0 mb-3">
                                    <p class="mb-2 repeater-title">Cost</p>
                                    <input type="number" class="form-control invoice-item-price mb-2" placeholder="00"
                                        min="12" disabled>
                                    <div class="d-flex flex-column gap-2">
                                        <span>Discount & Tax:</span>
                                        <span>
                                            <span class="discount me-2">0%</span>
                                            <span class="tax-1 me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-original-title="Tax 1">0%</span>
                                                <span class="tax-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tax 2" aria-describedby="tooltip961399">0%</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12 mb-md-0 mb-3">
                                    <p class="mb-2 repeater-title">Qty</p>
                                    <input type="number" class="form-control invoice-item-qty" placeholder="1"
                                        min="1" max="50">
                                </div>
                                <div class="col-md-1 col-12 pe-0">
                                    <p class="mb-2 repeater-title">Price</p>
                                    <p class="mb-0">$24.00</p>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                <i class="mdi mdi-close cursor-pointer bg-danger text-white" data-repeater-delete=""></i>
                                <div class="dropdown">
                                    <i class="mdi mdi-cog-outline cursor-pointer more-options-dropdown" role="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                        aria-expanded="false">
                                    </i>
                                    <div class="dropdown-menu dropdown-menu-end w-px-300 p-3"
                                        aria-labelledby="dropdownMenuButton" style="">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="discountInput" class="form-label">Discount(%)</label>
                                                <input type="number" class="form-control" id="discountInput"
                                                    min="0" max="100">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="taxInput1" class="form-label">Tax 1</label>
                                                <select name="group-a[0][tax-1-input]" id="taxInput1"
                                                    class="form-select tax-select">
                                                    <option value="0%" selected="">0%</option>
                                                    <option value="1%">1%</option>
                                                    <option value="10%">10%</option>
                                                    <option value="18%">18%</option>
                                                    <option value="40%">40%</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="taxInput2" class="form-label">Tax 2</label>
                                                <select name="group-a[0][tax-2-input]" id="taxInput2"
                                                    class="form-select tax-select">
                                                    <option value="0%" selected="">0%</option>
                                                    <option value="1%">1%</option>
                                                    <option value="10%">10%</option>
                                                    <option value="18%">18%</option>
                                                    <option value="40%">40%</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="dropdown-divider my-3"></div>
                                        <button type="button"
                                            class="btn btn-label-primary btn-apply-changes waves-effect">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-2">
                        <button type="button" class="btn btn-sm btn-primary waves-effect waves-light"
                            data-repeater-create="">
                            <i class="mdi mdi-plus me-1"></i> Add Item
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <h5 class="my-3">
                            Terms & Conditions :
                        </h5>
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">1. Validity of Quotation : 1(one) Month since the date of Quotation
                                </p>
                                <p class="card-text">2. Price : Franco FACTORY</p>
                                <p class="card-text">3. Delivery Process : Item 1 & 3 Ready Stock, Item 2 Indent 7-10 dayas
                                    after PO received</p>
                                <p class="card-text">4. Payment : 50% DP, 50% Before delivery</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card shadow-none bg-secondary text-white border border-secondary my-3">
                            <div class="card-body">
                                <p class="card-text">Sub Total : <span class="float-end">RP. 20.650.000.-</span></p>
                            </div>
                        </div>
                        <div class="card shadow-none bg-secondary text-white border border-secondary mb-3">
                            <div class="card-body">
                                <p class="card-text m-auto">Shipping Cost : <span class="float-end">RP.
                                        20.650.000.-</span></p>
                            </div>
                        </div>
                        <div class="card shadow-none bg-secondary text-white border border-secondary mb-3">
                            <div class="card-body">
                                <p class="card-text m-auto">Total : <span class="float-end">RP. 20.650.000.-</span></p>
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
    <script src="{{ asset('assets') }}/vendor/libs/jquery-repeater/jquery-repeater.js"></script>
    <script src="{{ asset('assets') }}/js/forms-extras.js"></script>
    <script src="{{ asset('assets') }}/js/app-invoice-add.js"></script>
@endpush
