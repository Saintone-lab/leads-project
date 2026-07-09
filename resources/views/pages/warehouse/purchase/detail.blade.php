@extends('layouts.sales.app')
@section('title', 'Purchase Request')
@section('content')
    <div class="row invoice-preview">
        {{-- Invoice --}}
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">

            <div class="row mb-3">
                <div class="col-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">Sales</div>
                                <div class="col-8">: {{ $quotation->sales->name }}</div>
                                <div class="col-4">Flag</div>
                                <div class="col-8">: {{ $quotation->pic->client->info }}</div>
                                <div class="col-4">Client</div>
                                <div class="col-8">: {{ $quotation->pic->client->company }}</div>
                                <div class="col-4">PIC</div>
                                <div class="col-8">: {{ $quotation->pic->name_pic }}</div>
                                <div class="col-4">Address</div>
                                <div class="col-8">: {{ $quotation->pic->client->address }}</div>
                                @php
                                    switch ($pending->delivery) {
                                        case 1:
                                            $delivery = 'Kurir';
                                            break;
                                        case 2:
                                            $delivery = 'Teknisi';
                                            break;
                                        case 3:
                                            $delivery = 'Direct';
                                            break;
                                        case 4:
                                            $delivery = 'Other';
                                            break;
                                        default:
                                            $delivery = 'Error';
                                            break;
                                    }
                                    switch ($pending->charged) {
                                        case 1:
                                            $charged = 'Company';
                                            break;
                                        case 2:
                                            $charged = 'Customer';
                                            break;
                                        default:
                                            $charged = '';
                                            break;
                                    }
                                @endphp
                                {{-- <div class="col-4">Kurir</div>
                                <div class="col-8">
                                    : {{ $delivery }} {{ $pending->charged ? "($charged)" : '' }}
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">No Quotation</div>
                                @php
                                    if ($quotation->type == 'Sparepart') {
                                        $link = 'quotation.show';
                                    } elseif ($quotation->type == 'Overhaul') {
                                        $link = 'show-overhaul.quotation';
                                    } else {
                                        $link = 'show-service.quotation';
                                    }

                                @endphp
                                <div class="col-8">: <a class="text-dark cursor-pointer"
                                        href="{{ route($link, $quotation->id) }}">{{ $quotation->no_quote }}</a>
                                </div>
                                <div class="col-4">No Invoice</div>
                                <div class="col-8">:
                                    @if (@$invoice->no_invoice)
                                        <a class="text-dark cursor-pointer"
                                            href="{{ route('invoice.show', $invoice->id) }}">
                                            {{ $invoice->no_invoice }}
                                        </a>
                                    @else
                                        Belum ada invoice
                                    @endif
                                </div>
                                <div class="col-4">No SO</div>
                                <div class="col-8">: <a class="text-dark cursor-pointer"
                                        href="{{ route('pending-po.show', $pending->id) }}">
                                        {{ $pending->no_pending }}
                                    </a></div>
                                <div class="col-4">Payment Info</div>
                                <div class="col-8">:
                                    {{ $invoice ? ($invoice->status_p == 1 ? 'Payment Confirmed' : 'Unpaid') : 'Belum ada invoice' }}
                                </div>
                                <div class="col-4">PO Date</div>
                                <div class="col-8">: {{ \Carbon\Carbon::parse($quotation->po_date)->format('d-m-Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="fw-medium card-title mb-3">
                            Purchase Request
                        </h4>
                        <div class="tombol">
                            @if ($purchase->where('status', 0)->count() > 0)
                                <a href="#" class="btn btn-info d-grid w-100 waves-effect acc-all-purchase"
                                    data-id="{{ $pending->id }}">ACC All</a>
                            @elseif($purchase->where('status', 1)->count() > 0)
                                <a href="#" class="btn btn-twitter d-grid w-100 waves-effect delivery-all-purchase"
                                    data-id="{{ $pending->id }}">On
                                    Delivery All</a>
                            @endif
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No PR</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Note</th>
                                <th>Info Pengiriman</th>
                                @if (Auth::user()->role != 'Logistic')
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $no = 1;
                            @endphp
                            @forelse ($purchase as $pr)
                                @php
                                    // switch ($item->status) {
                                    //     case 1:
                                    //         $status = 'On Check';
                                    //         break;
                                    //     case 2:
                                    //         $status = 'Ready Stock';
                                    //         break;
                                    //     case 3:
                                    //         $status = 'Kurang';
                                    //         break;
                                    //     case 4:
                                    //         $status = 'Pre-Order';
                                    //         break;
                                    //     case 5:
                                    //         $status = 'Delivery Process';
                                    //         break;
                                    //     case 6:
                                    //         $status = 'Done';
                                    //         break;
                                    //     default:
                                    //         $status = 'Belum Di Cek';
                                    //         break;
                                    // }
                                @endphp
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $pr->no_pr ?? '-' }}</td>
                                    <td>
                                        @if ($pr->id_equivalent == '0')
                                            -
                                        @else
                                            @php
                                                $detPrice = $detQuotation->firstWhere('id_equivalent', $pr->id_equivalent);
                                            @endphp
                                            {{ $pr->equivalent->brand }} {{ $pr->equivalent->pn }}
                                            @if ($pr->equivalent->product)
                                                <span
                                                    class="badge {{ $pr->equivalent->product->go == 'Genuine' ? 'bg-label-success' : 'bg-label-warning' }}">
                                                    {{ $pr->equivalent->product->go }}
                                                </span>
                                            @endif
                                            <div class="mt-1">
                                                <span class="badge bg-success">
                                                    <i class="mdi mdi-tag-outline me-1"></i>{{ $detPrice ? 'RP ' . number_format($detPrice->price, 0, '', '.') : '-' }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    {{-- <td>
                                    <pre class="mb-0"
                                        style="font-size: 15px; font-family: 'Inter', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $item->detail_product }}</pre>
                                </td> --}}
                                    <td>{{ $pr->qty }} {{ $pr->equivalent->product->unit ?? '' }}</td>
                                    <td style="max-width: 220px;">
                                        @if ($pr->note && $pr->note != '-')
                                            <span class="text-muted fst-italic small">
                                                <i class="mdi mdi-note-text-outline me-1"></i>{{ $pr->note }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pr->status >= 2 && $pr->purchase_type)
                                            <div class="d-flex align-items-start gap-2">
                                                <div class="d-flex flex-column gap-1" style="font-size: 0.8rem;">
                                                    <span
                                                        class="badge {{ $pr->purchase_type == 'Lokal' ? 'bg-label-info' : 'bg-label-primary' }}">
                                                        {{ $pr->purchase_type }}
                                                    </span>
                                                    <span><i class="mdi mdi-truck-outline me-1"></i>{{ $pr->cargo }}</span>
                                                    <span><i class="mdi mdi-barcode me-1"></i>{{ $pr->no_resi ?: 'Belum ada resi' }}</span>
                                                    <span><i class="mdi mdi-calendar-outline me-1"></i>{{ \Carbon\Carbon::parse($pr->purchase_date)->format('d-m-Y') }}</span>
                                                </div>
                                                <a href="#" data-bs-toggle="tooltip" title="Edit Info Pengiriman"
                                                    class="btn btn-sm btn-icon btn-label-secondary waves-effect edit-delivery-info"
                                                    data-id="{{ $pr->id }}"
                                                    data-purchase-type="{{ $pr->purchase_type }}"
                                                    data-cargo="{{ $pr->cargo }}"
                                                    data-no-resi="{{ $pr->no_resi }}"
                                                    data-purchase-date="{{ $pr->purchase_date }}">
                                                    <i class="menu-icon tf-icons mdi mdi-16px mdi-pencil-outline m-0"></i>
                                                </a>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    @if (Auth::user()->role != 'Logistic')
                                        <td class="text-center">
                                            @if ($pr->status == 0)
                                                <a href="#" data-bs-toggle="tooltip" title="ACC"
                                                    class="btn btn-sm btn-icon btn-label-info waves-effect acc-purchase"
                                                    data-id="{{ $pr->id }}"
                                                    data-pending="{{ $pending->id }}">
                                                    <i class="menu-icon tf-icons mdi mdi-18px mdi-check-circle-outline m-0"></i>
                                                </a>
                                            @elseif($pr->status == 1)
                                                <a href="#" data-bs-toggle="tooltip" title="On Delivery"
                                                    class="btn btn-sm btn-icon btn-label-primary waves-effect delivery-purchase"
                                                    data-id="{{ $pr->id }}" data-pending="{{ $pending->id }}">
                                                    <i class="menu-icon tf-icons mdi mdi-18px mdi-truck-delivery-outline m-0"></i>
                                                </a>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                                @php
                                    $no++;
                                @endphp
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak Ada Purchase Request</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Diskusi --}}
            <div class="card mb-4" id="diskusi">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-forum-outline me-2"></i>Diskusi
                    </h5>
                </div>
                <div class="card-body">

                    {{-- Daftar pesan --}}
                    <div class="discussion-list mb-4" style="max-height: 450px; overflow-y: auto;">
                        @forelse($discussions as $disc)
                            @php
                                $isMe = $disc->id_user == Auth::id();
                            @endphp
                            <div class="d-flex gap-3 mb-4 {{ $isMe ? 'flex-row-reverse' : '' }}">
                                <div class="flex-shrink-0">
                                    <img src="{{ url('') . '/' . $disc->user->image }}"
                                        class="rounded-circle"
                                        style="width:38px;height:38px;object-fit:cover;"
                                        alt="{{ $disc->user->name }}">
                                </div>
                                <div style="max-width:75%">
                                    <div class="d-flex align-items-center gap-2 mb-1 {{ $isMe ? 'flex-row-reverse' : '' }}">
                                        <span class="fw-semibold small">{{ $disc->user->name }}</span>
                                        <span class="text-muted" style="font-size:11px;">
                                            {{ \Carbon\Carbon::parse($disc->created_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="p-2 px-3 rounded-3 discussion-bubble {{ $isMe ? 'bg-primary text-white ms-auto' : 'bg-label-secondary' }}"
                                        style="word-break:break-word;">
                                        @php
                                            $msg = e($disc->message);
                                            // Highlight @mention
                                            foreach ($disc->mentions as $m) {
                                                $msg = str_replace(
                                                    '@' . $m->user->name,
                                                    '<span class="fw-bold ' . ($isMe ? 'text-warning' : 'text-primary') . '">@' . e($m->user->name) . '</span>',
                                                    $msg
                                                );
                                            }
                                        @endphp
                                        {!! nl2br($msg) !!}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="mdi mdi-chat-outline mdi-48px d-block mb-2"></i>
                                Belum ada diskusi. Mulai percakapan sekarang.
                            </div>
                        @endforelse
                    </div>

                    {{-- Form kirim pesan --}}
                    <form action="{{ route('purchase-request.add-discussion', $pending->id) }}" method="POST" id="discussionForm">
                        @csrf
                        <div class="position-relative">
                            <textarea
                                name="message"
                                id="discussionMessage"
                                class="form-control"
                                rows="3"
                                placeholder="Tulis pesan... ketik @ untuk mention seseorang"
                                style="padding-right: 100px; resize:none;"
                                required></textarea>

                            {{-- Hidden inputs untuk mention --}}
                            <div id="mentionInputs"></div>

                            <button type="submit" class="btn btn-primary btn-sm position-absolute"
                                style="bottom:10px;right:10px;">
                                <i class="mdi mdi-send me-1"></i>Kirim
                            </button>
                        </div>

                        {{-- Mention dropdown --}}
                        <ul id="mentionDropdown"
                            class="list-group shadow"
                            style="display:none;position:absolute;z-index:999;min-width:220px;max-height:200px;overflow-y:auto;">
                        </ul>

                        {{-- Tag mention yang dipilih --}}
                        <div id="mentionTags" class="d-flex flex-wrap gap-1 mt-2"></div>
                    </form>
                </div>
            </div>
            {{-- End: Diskusi --}}

        </div>
        {{-- End: Invoice --}}
        {{-- Button Invocie --}}
        <div class="col-xl-3 col-md-4 col-12 invoice-actions">

            <div class="card mb-3">
                @php
                    $stat = $purchase->every(fn($p) => $p->status == 2) ? 1 : 0;
                @endphp
                <div class="card-body">
                    <a class="btn btn-primary d-grid w-100 mb-3 waves-effect {{ $stat == 1 ? '' : 'disabled' }}"
                        href="{{ $stat == 1 ? route('purchase-request.done-all', $pending->id) : '#' }}"
                        tabindex="{{ $stat == 1 ? '0' : '-1' }}" aria-disabled="{{ $stat == 1 ? 'false' : 'true' }}">
                        Cetak Product In
                    </a>
                    @if (Auth::user()->role != 'Logistic')
                        <a href="#" class="btn btn-outline-danger d-grid w-100 waves-effect delete-invoice mb-3"
                            data-id="{{ $quotation->id }}">Delete</a>
                    @endif
                    <button class="btn btn-outline-secondary d-grid w-100 mb-3 waves-effect" id="backButton">
                        Back
                    </button>
                </div>
            </div>
        </div>
        {{-- @endif --}}
    </div>
    {{-- End : Button Invoice --}}
    </div>

    {{-- Modal: Info Pengiriman (On Delivery) --}}
    <div class="modal fade" id="modalDeliveryInfo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deliveryInfoForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deliveryInfoModalTitle">Info Pengiriman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label d-block">Tipe Pembelian</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="purchase_type" id="purchaseTypeLokal"
                                    value="Lokal" required>
                                <label class="form-check-label" for="purchaseTypeLokal">Lokal</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="purchase_type" id="purchaseTypeImpor"
                                    value="Impor" required>
                                <label class="form-check-label" for="purchaseTypeImpor">Impor</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pembelian</label>
                            <input type="date" class="form-control" name="purchase_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cargo / Ekspedisi</label>
                            <input type="text" class="form-control" name="cargo" placeholder="Contoh: JNE, SiCepat, DHL"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Resi <span class="text-muted fw-normal">(opsional, bisa diisi belakangan)</span></label>
                            <input type="text" class="form-control" name="no_resi" placeholder="Nomor resi">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-twitter" id="deliveryInfoSubmitBtn">On Delivery</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End: Modal Info Pengiriman --}}
@endsection
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/dropzone/dropzone.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
    <style>
        .discussion-bubble { line-height: 1.5; }
        #mentionDropdown .list-group-item { cursor: pointer; padding: 6px 12px; }
        #mentionDropdown .list-group-item:hover { background: #f0f0f0; }
        #mentionDropdown .list-group-item img { width: 28px; height: 28px; object-fit: cover; }
        .mention-tag { background: #e7f1ff; color: #3b82f6; border: 1px solid #bfdbfe; border-radius: 999px; padding: 2px 10px; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; }
        .mention-tag .remove-mention { cursor: pointer; font-weight: bold; color: #6b7280; }
        .mention-tag .remove-mention:hover { color: #ef4444; }
    </style>
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/dropzone/dropzone.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
@endpush
@push('script')
    <script>
        // Scroll diskusi ke pesan terbaru
        (function () {
            var list = document.querySelector('.discussion-list');
            if (list) list.scrollTop = list.scrollHeight;
        })();

        // @mention logic
        var allUsers = @json($allUsers);
        var selectedMentions = {}; // id => name
        var mentionStartIndex = -1;

        var textarea = document.getElementById('discussionMessage');
        var dropdown = document.getElementById('mentionDropdown');
        var tagsEl = document.getElementById('mentionTags');
        var inputsEl = document.getElementById('mentionInputs');

        function renderDropdown(query) {
            var filtered = allUsers.filter(function (u) {
                return u.name.toLowerCase().indexOf(query.toLowerCase()) !== -1 && !selectedMentions[u.id];
            }).slice(0, 8);

            dropdown.innerHTML = '';
            if (!filtered.length) { dropdown.style.display = 'none'; return; }

            filtered.forEach(function (u) {
                var li = document.createElement('li');
                li.className = 'list-group-item d-flex align-items-center gap-2';
                li.innerHTML = '<img src="/' + (u.image || 'assets/img/avatars/1.png') + '" class="rounded-circle">' +
                    '<span>' + u.name + '</span>' +
                    '<small class="text-muted ms-auto">' + u.role + '</small>';
                li.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    selectMention(u);
                });
                dropdown.appendChild(li);
            });

            // Posisikan di bawah textarea
            var rect = textarea.getBoundingClientRect();
            dropdown.style.display = 'block';
            dropdown.style.top = (textarea.offsetTop + textarea.offsetHeight) + 'px';
            dropdown.style.left = textarea.offsetLeft + 'px';
        }

        function selectMention(user) {
            // Ganti teks @query dengan @name di textarea
            var val = textarea.value;
            var before = val.substring(0, mentionStartIndex);
            var after = val.substring(textarea.selectionStart);
            textarea.value = before + '@' + user.name + ' ' + after;
            textarea.focus();

            selectedMentions[user.id] = user.name;
            dropdown.style.display = 'none';
            mentionStartIndex = -1;
            renderTags();
        }

        function renderTags() {
            tagsEl.innerHTML = '';
            inputsEl.innerHTML = '';
            Object.keys(selectedMentions).forEach(function (id) {
                var span = document.createElement('span');
                span.className = 'mention-tag';
                span.innerHTML = '@' + selectedMentions[id] +
                    ' <span class="remove-mention" data-id="' + id + '">&times;</span>';
                tagsEl.appendChild(span);

                var inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'mentions[]';
                inp.value = id;
                inputsEl.appendChild(inp);
            });

            // Hapus mention dari tag
            tagsEl.querySelectorAll('.remove-mention').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    delete selectedMentions[this.dataset.id];
                    renderTags();
                });
            });
        }

        textarea.addEventListener('input', function () {
            var val = this.value;
            var pos = this.selectionStart;

            // Cari posisi @ terakhir sebelum kursor
            var atPos = -1;
            for (var i = pos - 1; i >= 0; i--) {
                if (val[i] === '@') { atPos = i; break; }
                if (val[i] === ' ' || val[i] === '\n') break;
            }

            if (atPos !== -1) {
                mentionStartIndex = atPos;
                var query = val.substring(atPos + 1, pos);
                renderDropdown(query);
            } else {
                dropdown.style.display = 'none';
                mentionStartIndex = -1;
            }
        });

        textarea.addEventListener('keydown', function (e) {
            if (dropdown.style.display === 'block') {
                if (e.key === 'Escape') dropdown.style.display = 'none';
            }
        });

        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target) && e.target !== textarea) {
                dropdown.style.display = 'none';
            }
        });

        // Validasi form sebelum submit
        document.getElementById('discussionForm').addEventListener('submit', function (e) {
            var msg = textarea.value.trim();
            if (!msg) { e.preventDefault(); textarea.focus(); }
        });

        $('#backButton').click(function() {
            window.history.back();
        });
        $(document).on('click', '.acc-purchase', function() {
            var id = $(this).data('id');
            var pending = $(this).data('pending');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to acc this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Acc it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('purchase-request') }}/acc/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'PATCH',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Acc succed!",
                                    text: "Your file has been acc.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/purchase-request/' +
                                        pending;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Acc!'
                                });
                            }
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-success waves-effect",
                        },
                    });
                }
            });
        });
        $(document).on('click', '.acc-all-purchase', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to acc this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Acc it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url(path: 'purchase-request') }}/acc-all/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'PATCH',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Acc succed!",
                                    text: "Your file has been acc.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/purchase-request/' +
                                        id;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Acc!'
                                });
                            }
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-success waves-effect",
                        },
                    });
                }
            });
        });
        var deliveryModalEl = document.getElementById('modalDeliveryInfo');
        var deliveryModal = new bootstrap.Modal(deliveryModalEl);
        var deliveryContext = {};

        $(document).on('click', '.delivery-purchase', function() {
            deliveryContext = {
                mode: 'single',
                id: $(this).data('id'),
                pending: $(this).data('pending'),
            };
            $('#deliveryInfoForm')[0].reset();
            $('#deliveryInfoModalTitle').text('Info Pengiriman');
            $('#deliveryInfoSubmitBtn').text('On Delivery');
            deliveryModal.show();
        });
        $(document).on('click', '.delivery-all-purchase', function() {
            deliveryContext = {
                mode: 'all',
                id: $(this).data('id'),
            };
            $('#deliveryInfoForm')[0].reset();
            $('#deliveryInfoModalTitle').text('Info Pengiriman');
            $('#deliveryInfoSubmitBtn').text('On Delivery');
            deliveryModal.show();
        });
        $(document).on('click', '.edit-delivery-info', function() {
            deliveryContext = {
                mode: 'edit',
                id: $(this).data('id'),
            };
            $('#deliveryInfoForm')[0].reset();
            var $form = $('#deliveryInfoForm');
            $('[name="purchase_type"][value="' + $(this).data('purchase-type') + '"]', $form).prop('checked', true);
            $('[name="cargo"]', $form).val($(this).data('cargo'));
            $('[name="no_resi"]', $form).val($(this).data('no-resi'));
            $('[name="purchase_date"]', $form).val($(this).data('purchase-date'));
            $('#deliveryInfoModalTitle').text('Edit Info Pengiriman');
            $('#deliveryInfoSubmitBtn').text('Simpan');
            deliveryModal.show();
        });

        $('#deliveryInfoForm').on('submit', function(e) {
            e.preventDefault();
            var purchaseType = $('[name="purchase_type"]:checked', this).val();
            var cargo = $('[name="cargo"]', this).val();
            var noResi = $('[name="no_resi"]', this).val();
            var purchaseDate = $('[name="purchase_date"]', this).val();

            var urlMap = {
                all: '{{ url('purchase-request') }}/delivery-all/' + deliveryContext.id,
                edit: '{{ url('purchase-request') }}/delivery-info/' + deliveryContext.id,
                single: '{{ url('purchase-request') }}/delivery/' + deliveryContext.id,
            };
            var url = urlMap[deliveryContext.mode] || urlMap.single;
            var redirectId = deliveryContext.mode === 'all' ? deliveryContext.id :
                (deliveryContext.mode === 'edit' ? '{{ $pending->id }}' : deliveryContext.pending);
            var successText = deliveryContext.mode === 'edit' ?
                'Info pengiriman berhasil diperbarui.' : 'Your file has been deliveried.';

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': {
                    '_method': 'PATCH',
                    '_token': '{{ csrf_token() }}',
                    'purchase_type': purchaseType,
                    'cargo': cargo,
                    'no_resi': noResi,
                    'purchase_date': purchaseDate,
                },
                success: function(response) {
                    if (response == 1) {
                        deliveryModal.hide();
                        Swal.fire({
                            icon: "success",
                            title: "Delivery succed!",
                            text: successText,
                            customClass: {
                                confirmButton: "btn btn-success waves-effect",
                            },
                        })
                        window.setTimeout(function() {
                            window.location.href = '/purchase-request/' + redirectId;
                        }, 2000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Data Failed to Delivery!'
                        });
                    }
                },
                error: function(xhr) {
                    var message = 'Data Failed to Delivery!';
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message
                    });
                }
            });
        });
    </script>
@endpush
