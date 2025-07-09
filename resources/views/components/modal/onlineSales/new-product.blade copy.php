<form action="{{ route('upload-po.quotation') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-onboarding modal fade animate__animated" id="uploadPo" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="onboarding-content mb-0">
                        <h4 class="onboarding-title text-body"> Upload Link Product</h4>
                        <form>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control form-control-sm" type="text"
                                            placeholder="Put Link New Product Here ...." id="product" name="product[]"
                                            value="">
                                        <label for="product">Link 1</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control form-control-sm" type="text"
                                            placeholder="Put Link New Product Here ...." id="product" name="product[]"
                                            value="">
                                        <label for="product">Link 2</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control form-control-sm" type="text"
                                            placeholder="Put Link New Product Here ...." id="product" name="product[]"
                                            value="">
                                        <label for="product">Link 3</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control form-control-sm" type="text"
                                            placeholder="Put Link New Product Here ...." id="product" name="product[]"
                                            value="">
                                        <label for="product">Link 4</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control form-control-sm" type="text"
                                            placeholder="Put Link New Product Here ...." id="product" name="product[]"
                                            value="">
                                        <label for="product">Link 5</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
