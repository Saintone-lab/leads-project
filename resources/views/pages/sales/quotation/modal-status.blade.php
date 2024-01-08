<form action="{{ route('status.change.quotation', $quote->id) }}" method="patch" enctype="multipart/form-data">
    @csrf
    <div class="modal-onboarding modal fade animate__animated" id="changeStatus-{{ $quote->id }}" tabindex="-1"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">

                    <div class="onboarding-content mb-0">
                        <h4 class="onboarding-title text-body">{{ $quote->no_quote }}</h4>
                        <div class="onboarding-info mb-3">
                            {{ $quote->pic->client->company }}
                        </div>
                        <form>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-floating form-floating-outline mb-3">
                                        <select class="form-select" tabindex="0" id="roleEx3" name="status">
                                            <option value="20">Send WA/Email <small class="text-muted">20%</small></option>
                                            <option value="30">Inquiry Accepted <small class="text-muted">30%</small></option>
                                            <option value="40">Progress Follow Up <small class="text-muted">40%</small>
                                            </option>
                                            <option value="60">Negotiation/Revisi <small class="text-muted">60%</small>
                                            </option>
                                            <option value="80">Hot Prospect<small class="text-muted">80%</small>
                                            </option>
                                            <option value="100">Done PO <small class="text-muted">100%</small>
                                            </option>
                                            <option value="0">Loss <small class="text-muted">0%</small></option>
                                        </select>
                                        <label for="roleEx3">Status</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="note" class="form-control" name="note"
                                            placeholder="Put Note Here....."
                                            value="-">
                                        <label for="note">Note</label>
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
