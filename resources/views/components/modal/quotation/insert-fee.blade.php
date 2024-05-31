<form action="{{ route('insert_fee.quotation', $quote->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-onboarding modal fade animate__animated" id="insertFee" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="onboarding-content mb-0">
                        <h4 class="onboarding-title text-body"> Insert Fee of {{ $quote->no_quote }}</h4>
                        <div class="onboarding-info mb-3">
                            {{ $quote->pic->client->company }}
                        </div>
                        <form>
                            <div class="row">
                                <div class="col-12  mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <p class="mb-2 repeater-title">Fee</p>
                                        <div class="input-group" data-price="1">
                                            <span class="input-group-text">Rp. </span>
                                            <input type="text" class="form-control invoice-item-price-label"
                                                id="priceLabel-1" data-id="1" name="harga"
                                                placeholder="Put Fee Here" data-type="currency" min="0"
                                                pattern="^[0-9]\d{0,2}(\.\d{3})*$" @focus="focused = true"
                                                @blur="focused = false" value="{{ old('price[]') }}">
                                            <input class="form-control invoice-item-price" type="number"
                                                name="fee" id="price-1" value="{{ old('price[]') }}" hidden>
                                        </div>
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
