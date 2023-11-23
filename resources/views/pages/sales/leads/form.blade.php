{{--  <div class="modal fade" id="createLeads" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel5">Create New Leads</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3">
                    <div class="col mb-3">
                        <label for="nameWithTitle" class="form-label">Company</label>
                        <input type="text" id="nameWithTitle" class="form-control" placeholder="Enter Name">
                    </div>
                    <div class="col mb-3">
                        <label for="nameWithTitle" class="form-label">Personal In Charge</label>
                        <input type="text" id="nameWithTitle" class="form-control" placeholder="Enter Name">
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col mb-0">
                        <label for="emailWithTitle" class="form-label">Email</label>
                        <input type="email" id="emailWithTitle" class="form-control" placeholder="xxxx@xxx.xx">
                    </div>
                    <div class="col mb-0">
                        <label for="dobWithTitle" class="form-label">DOB</label>
                        <input type="date" id="dobWithTitle" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>  --}}

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
                        <div class="form-floating form-floating-outline">
                            <input type="company" id="companyAnimation" class="form-control" placeholder="PT. xxxx xxx">
                            <label for="companyAnimation">Company</label>
                        </div>
                    </div>
                    <div class="col mb-2">
                        <div class="form-floating form-floating-outline">
                            <input type="pic" id="picAnimation" class="form-control" placeholder="Mr/Mss xxxx">
                            <label for="picAnimation">Personal In Charge</label>
                        </div>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col mb-2">
                        <div class="form-floating form-floating-outline">
                            <input type="phone" id="phoneAnimation" class="form-control" placeholder="081xxxxx">
                            <label for="phoneAnimation">Phone</label>
                        </div>
                    </div>
                    <div class="col mb-2">
                        <div class="form-floating form-floating-outline">
                            <input type="WA" id="WAAnimation" class="form-control" placeholder="081xxxxx">
                            <label for="WAAnimation">WhatsApp</label>
                        </div>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col mb-2">
                        <div class="form-floating form-floating-outline">
                            <input type="email" id="emailAnimation" class="form-control" placeholder="xxxx@xxx.xx">
                            <label for="emailAnimation">Email</label>
                        </div>
                    </div>
                    <div class="col mb-2">
                        <div class="form-floating form-floating-outline">
                            <input type="machine" id="machineAnimation" class="form-control" placeholder="Machine xxxx">
                            <label for="machineAnimation">Machine</label>
                        </div>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col mb-2">
                        <div class="form-floating form-floating-outline">
                            <input type="address" id="addressAnimation" class="form-control" placeholder="Contoh: Bandung">
                            <label for="addressAnimation">Address</label>
                        </div>
                    </div>
                    <div class="col mb-2">
                        <div class="form-floating form-floating-outline">
                            <input type="source" id="sourceAnimation" class="form-control" placeholder="Mr/Mss xxxx">
                            <label for="sourceAnimation">Source</label>
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
