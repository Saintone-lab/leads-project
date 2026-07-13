@extends('layouts.sales.app')
@section('title', 'Audit Tools - ' . $audit->no_audit)
@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h4 class="fw-bold py-3 mb-0">
        <span class="text-muted fw-light">Audit Tools /</span> {{ $audit->no_audit }}
        @php
            $headerBadge = [
                'Draft' => 'bg-label-secondary',
                'Submitted' => 'bg-label-warning',
                'Verified' => 'bg-label-success',
                'Rejected' => 'bg-label-danger',
            ][$audit->status_submit] ?? 'bg-label-secondary';
        @endphp
        <span class="badge {{ $headerBadge }} align-middle">{{ $audit->status_submit }}</span>
    </h4>
    <p class="text-muted mb-4">
        Periode {{ $audit->period->tahun }} Semester {{ $audit->period->semester }} —
        {{ \Carbon\Carbon::parse($audit->period->tanggal_mulai)->format('d M') }} s/d
        {{ \Carbon\Carbon::parse($audit->period->tanggal_selesai)->format('d M Y') }}
        @if ($audit->submitted_at)
            <br>Disubmit: {{ \Carbon\Carbon::parse($audit->submitted_at)->format('d M Y H:i') }}
        @endif
    </p>

    <a href="{{ route('tool-audit.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="mdi mdi-arrow-left"></i> Kembali
    </a>

    <form action="{{ route('tool-audit.submit', $audit->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @foreach ($audit->items as $item)
            @php
                $tool = $item->fixedAsset;
                $master = $tool->toolsMaster ?? null;
                $kondisi = old("items.{$item->id}.kondisi", $item->kondisi);
            @endphp
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3 align-items-start">
                        <div class="col-md-2 text-center">
                            @if ($tool && $tool->foto_awal)
                                <img src="{{ asset($tool->foto_awal) }}" alt="foto awal"
                                    style="width:100%;max-width:100px;aspect-ratio:1/1;object-fit:cover;border-radius:6px;">
                                <div class="small text-muted mt-1">Foto Awal</div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1">{{ $master->nama_tools ?? '-' }}</h6>
                            <div class="text-muted small mb-2">Qty terdaftar: {{ $tool->qty }}</div>

                            @if ($editable)
                                <div class="form-floating form-floating-outline mb-2">
                                    <input type="number" class="form-control" name="items[{{ $item->id }}][qty_actual]"
                                        value="{{ old("items.{$item->id}.qty_actual", $item->qty_actual ?? $tool->qty) }}"
                                        min="0" required>
                                    <label>Qty Sekarang</label>
                                </div>
                            @else
                                <div class="text-muted small">Qty sekarang: {{ $item->qty_actual ?? '-' }}</div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            @if ($editable)
                                <div class="btn-group w-100 kondisi-group" data-item="{{ $item->id }}" role="group">
                                    <input type="radio" class="btn-check kondisi-radio" data-item="{{ $item->id }}"
                                        name="items[{{ $item->id }}][kondisi]" id="ada-{{ $item->id }}" value="Ada"
                                        {{ $kondisi == 'Ada' ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-success btn-sm" for="ada-{{ $item->id }}">Ada</label>

                                    <input type="radio" class="btn-check kondisi-radio" data-item="{{ $item->id }}"
                                        name="items[{{ $item->id }}][kondisi]" id="rusak-{{ $item->id }}" value="Rusak"
                                        {{ $kondisi == 'Rusak' ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-warning btn-sm" for="rusak-{{ $item->id }}">Rusak</label>

                                    <input type="radio" class="btn-check kondisi-radio" data-item="{{ $item->id }}"
                                        name="items[{{ $item->id }}][kondisi]" id="hilang-{{ $item->id }}" value="Hilang"
                                        {{ $kondisi == 'Hilang' ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-danger btn-sm" for="hilang-{{ $item->id }}">Hilang</label>
                                </div>

                                <div class="mt-2 alasan-wrap-{{ $item->id }}"
                                    style="display: {{ $kondisi == 'Rusak' ? 'block' : 'none' }};">
                                    <textarea class="form-control form-control-sm" name="items[{{ $item->id }}][alasan]"
                                        placeholder="Alasan kerusakan...">{{ old("items.{$item->id}.alasan", $item->alasan) }}</textarea>
                                </div>

                                <div class="mt-2 metode-wrap-{{ $item->id }}"
                                    style="display: {{ $kondisi == 'Hilang' ? 'block' : 'none' }};">
                                    <select class="form-select form-select-sm" name="items[{{ $item->id }}][metode_ganti]">
                                        <option value="">-- Metode Ganti --</option>
                                        <option value="Beli Sendiri"
                                            {{ old("items.{$item->id}.metode_ganti", $item->metode_ganti) == 'Beli Sendiri' ? 'selected' : '' }}>
                                            Beli Sendiri</option>
                                        <option value="Potong Bonus"
                                            {{ old("items.{$item->id}.metode_ganti", $item->metode_ganti) == 'Potong Bonus' ? 'selected' : '' }}>
                                            Potong Bonus</option>
                                    </select>
                                    @if ($master && ($master->foto_referensi || $master->link_pembelian))
                                        <div class="small mt-2 p-2 bg-label-info rounded">
                                            Referensi beli: {{ $master->nama_tools }}
                                            @if ($master->link_pembelian)
                                                — <a href="{{ $master->link_pembelian }}" target="_blank" rel="noopener">{{ $master->link_pembelian }}</a>
                                            @endif
                                            @if ($master->foto_referensi)
                                                <div class="mt-1">
                                                    <img src="{{ asset($master->foto_referensi) }}" alt="referensi"
                                                        style="max-width:80px;border-radius:4px;">
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @else
                                @php
                                    $badge = ['Ada' => 'bg-label-success', 'Rusak' => 'bg-label-warning', 'Hilang' => 'bg-label-danger'][$item->kondisi] ?? 'bg-label-secondary';
                                @endphp
                                <span class="badge {{ $badge }}">{{ $item->kondisi ?? 'Belum diisi' }}</span>
                                @if ($item->alasan)
                                    <div class="small text-muted mt-1">{{ $item->alasan }}</div>
                                @endif
                                @if ($item->metode_ganti)
                                    <div class="small text-muted mt-1">Ganti: {{ $item->metode_ganti }}</div>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-3 text-center">
                            @if ($item->foto_audit)
                                <img src="{{ asset($item->foto_audit) }}" alt="foto audit"
                                    style="width:100%;max-width:100px;aspect-ratio:1/1;object-fit:cover;border-radius:6px;">
                                <div class="small text-muted mt-1">Foto Audit</div>
                            @endif
                            @if ($editable)
                                <input type="file" class="form-control form-control-sm mt-2" accept="image/*"
                                    name="items[{{ $item->id }}][foto_audit]" {{ $item->foto_audit ? '' : 'required' }}>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if ($editable)
            <button type="submit" class="btn btn-primary mb-4">Submit Audit</button>
        @endif
    </form>
@endsection()

@push('page-script')
    <script>
        document.querySelectorAll('.kondisi-radio').forEach(function (radio) {
            radio.addEventListener('change', function () {
                var id = this.getAttribute('data-item');
                var alasanWrap = document.querySelector('.alasan-wrap-' + id);
                var metodeWrap = document.querySelector('.metode-wrap-' + id);
                if (alasanWrap) alasanWrap.style.display = this.value === 'Rusak' ? 'block' : 'none';
                if (metodeWrap) metodeWrap.style.display = this.value === 'Hilang' ? 'block' : 'none';
            });
        });
    </script>
@endpush
