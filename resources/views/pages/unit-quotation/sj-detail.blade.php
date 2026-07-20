@extends('layouts.sales.app')
@section('title', 'Surat Jalan ' . $unitQuote->no_quote)
@section('content')
    <div class="row invoice-preview">
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    {{-- Header perusahaan --}}
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column mb-4">
                        <div class="mb-xl-0 pb-1">
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <img src="{{ asset('/asset') }}/logo/Reftech-Log.png" alt="" width="120">
                            </div>
                            <p class="mb-1 fw-bolder">PT Reftech Jaya Optima</p>
                            <div style="font-size:10px;">
                                <p class="mb-1">Taman Kopo Indah V, Ruko Sommerville No. 31</p>
                                <p class="mb-1">Bandung – Jawa Barat 40218</p>
                                <p class="mb-1">
                                    <i class="mdi mdi-phone-outline me-1"></i>022 54417653
                                    &nbsp;<i class="mdi mdi-email-outline me-1"></i>admin@reftech.id
                                </p>
                            </div>
                        </div>
                        <div class="text-end">
                            <h1 class="fw-bold" style="color:blue;">Surat Jalan</h1>
                            <span class="fw-bolder">{{ $unitQuote->no_quote }}</span>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Info pengiriman --}}
                    @php $client = $unitQuote->client; @endphp
                    <div class="row mb-4">
                        <div class="col-6">
                            <p class="mb-1 fw-semibold" style="font-size:11px;color:#888;">DIKIRIM KE</p>
                            <p class="mb-0 fw-bold">{{ $client->company ?? '-' }}</p>
                            <p class="mb-0">{{ $unitQuote->pic->name_pic ?? '-' }}</p>
                            <p class="mb-0 text-muted" style="font-size:12px;">
                                {{ $client ? ($delivery->destination == '1' ? $client->address : $client->subAddress) : '-' }}
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-1 fw-semibold" style="font-size:11px;color:#888;">TANGGAL</p>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($delivery->date)->format('d M Y') }}</p>
                            <p class="mb-1 fw-semibold mt-2" style="font-size:11px;color:#888;">JENIS PENGIRIMAN</p>
                            <p class="mb-0">{{ ucfirst($delivery->type) }}</p>
                            <p class="mb-1 fw-semibold mt-2" style="font-size:11px;color:#888;">DIBUAT OLEH</p>
                            <p class="mb-0">{{ $unitQuote->sales->name ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Tabel item --}}
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" style="font-size:13px;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:5%">No.</th>
                                    <th>Nama Unit / Item</th>
                                    <th class="text-center" style="width:10%">Qty</th>
                                    <th style="width:12%">Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dDelivery as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="fw-semibold">{{ $item->desc }}</td>
                                        <td class="text-center">{{ $item->qty }}</td>
                                        <td>{{ $item->info_qty ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Tanda tangan --}}
                    <div class="row mt-5">
                        <div class="col-4 text-center">
                            <p class="mb-5" style="font-size:12px;">Pengirim</p>
                            <div style="border-top:1px solid #000;width:80%;margin:0 auto;"></div>
                            <p class="mt-1" style="font-size:11px;">&nbsp;</p>
                        </div>
                        <div class="col-4 text-center">
                            <p class="mb-5" style="font-size:12px;">Penerima</p>
                            <div style="border-top:1px solid #000;width:80%;margin:0 auto;"></div>
                            <p class="mt-1" style="font-size:11px;">&nbsp;</p>
                        </div>
                        <div class="col-4 text-center">
                            <p class="mb-5" style="font-size:12px;">Mengetahui</p>
                            <div style="border-top:1px solid #000;width:80%;margin:0 auto;"></div>
                            <p class="mt-1" style="font-size:11px;">&nbsp;</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar aksi --}}
        <div class="col-xl-3 col-md-4 col-12 invoice-actions">
            <div class="card">
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('print.delivery', $delivery->id) }}"
                        class="btn btn-primary waves-effect" target="_blank">
                        <i class="mdi mdi-printer-outline me-1"></i> Cetak
                    </a>
                    <a href="{{ route('unit-quotation.show', $unitQuote->id) }}" class="btn btn-outline-secondary waves-effect">
                        <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Quotation
                    </a>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <p class="mb-1 fw-semibold" style="font-size:11px;color:#888;">NO. QUOTATION</p>
                    <p class="mb-2 fw-bold">{{ $unitQuote->no_quote }}</p>
                    <p class="mb-1 fw-semibold" style="font-size:11px;color:#888;">STATUS</p>
                    <span class="badge bg-success">Barang Keluar</span>
                </div>
            </div>
        </div>
    </div>
@endsection
