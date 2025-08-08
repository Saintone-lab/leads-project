<form action="{{ route('convert-po.quotation', $quote->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-onboarding modal fade animate__animated" id="convertPo" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="onboarding-content mb-0">
                        <h4 class="onboarding-title text-body"> Convert To PO {{ $quote->no_quote }}</h4>
                        <div class="onboarding-info mb-3">
                            {{ $quote->pic->client->company }}
                        </div>
                        <form>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="date" id="po_date" name="po_date"
                                            value="{{ old('po_date', @$quote->po_date ?? now()->format('Y-m-d')) }}">
                                        <label for="po_date">Date PO</label>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="selectWeek" aria-label="Default select example"
                                            name="week">
                                            <option disabled>----- Choose Week -----</option>
                                            <option value="1">Week 1</option>
                                            <option value="2">Week 2</option>
                                            <option value="3">Week 3</option>
                                            <option value="4">Week 4</option>
                                            <option value="5">Week 5</option>
                                        </select>
                                        <label for="selectWeek">Week</label>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="type" name="type"
                                            value="{{ $quote->type == 'Sparepart'  && $quote->quote_for == 'Sparepart' ? 'Non Project' : 'Project' }}" disabled>
                                        <label for="po_date">Date PO</label>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="selectEkspidisi" aria-label="Default select example"
                                            name="ekspidisi">
                                            <option disabled>----- Choose Ekspidisi -----</option>
                                            <option value="1">JNT / JNE / Cargo</option>
                                            <option value="2">Send By Technian</option>
                                            <option value="3">Taken Directly</option>
                                            <option value="4">Others</option>
                                        </select>
                                        <label for="selectEkspidisi">Ekspidisi</label>
                                    </div>
                                </div>
                                <div class="col-12  mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <textarea name="note" id="" cols="30" class="form-control h-px-100" rows="10">-</textarea>
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
