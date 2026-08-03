@extends('layouts.sales.app')
@section('title', 'Master Harga Jasa PM')
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forecast /</span> Master Harga Jasa PM</h4>

    @if(session('message'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Form Setup Harga Jasa PM -->
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Setup Jasa per Power (kW)</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('forecast.prices.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="powerSelect">Pilih Kapasitas Power (kW)</label>
                            <select class="form-select" id="powerSelect" name="power" required>
                                <option value="" disabled selected>-- Pilih Power --</option>
                                @foreach($availablePowers as $power)
                                    <option value="{{ $power }}">{{ $power }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">Atau ketik kustom power jika tidak ada di list.</div>
                            <input type="text" class="form-control mt-2" id="customPower" name="custom_power" placeholder="Ketik kustom, misal: 45 kW">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="price_pm1">Harga Jasa PM 1 (Air & Oil Filter)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="price_pm1" name="price_pm1" required min="0" placeholder="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="price_pm2">Harga Jasa PM 2 (+ Separator & Oli)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="price_pm2" name="price_pm2" required min="0" placeholder="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="price_pm3">Harga Jasa PM 3 (+ Carbon Remover)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="price_pm3" name="price_pm3" required min="0" placeholder="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="price_pm4">Harga Jasa PM 4 (+ Overhaul Service)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="price_pm4" name="price_pm4" required min="0" placeholder="0">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Harga Jasa</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Harga Jasa PM -->
        <div class="col-md-7">
            <div class="card">
                <h5 class="card-header">Daftar Harga Jasa Standar</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Power (kW)</th>
                                <th>PM 1</th>
                                <th>PM 2</th>
                                <th>PM 3</th>
                                <th>PM 4</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($prices as $price)
                            <tr>
                                <td><strong>{{ $price->power }}</strong></td>
                                <td>Rp {{ number_format($price->price_pm1, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($price->price_pm2, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($price->price_pm3, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($price->price_pm4, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary btn-edit" 
                                            data-power="{{ $price->power }}"
                                            data-pm1="{{ $price->price_pm1 }}"
                                            data-pm2="{{ $price->price_pm2 }}"
                                            data-pm3="{{ $price->price_pm3 }}"
                                            data-pm4="{{ $price->price_pm4 }}">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data harga jasa. Silakan tambahkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function() {
        // Sync custom input with dropdown selection
        $('#powerSelect').change(function() {
            $('#customPower').val('');
        });
        
        $('#customPower').keyup(function() {
            if($(this).val() !== '') {
                $('#powerSelect').val('');
            }
        });

        // Edit button click handler
        $('.btn-edit').click(function() {
            var power = $(this).data('power');
            var pm1 = $(this).data('pm1');
            var pm2 = $(this).data('pm2');
            var pm3 = $(this).data('pm3');
            var pm4 = $(this).data('pm4');

            // Set inputs
            $('#customPower').val(power);
            $('#powerSelect').val('');
            $('#price_pm1').val(pm1);
            $('#price_pm2').val(pm2);
            $('#price_pm3').val(pm3);
            $('#price_pm4').val(pm4);
            
            // Scroll to form
            $('html, body').animate({
                scrollTop: $(".card").first().offset().top - 20
            }, 300);
        });
    });
</script>
@endpush
@endsection
