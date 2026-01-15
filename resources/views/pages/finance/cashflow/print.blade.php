@extends('layouts.sales.app')
@section('title', 'Print Opname')
<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="text-center">
            <h3>PT. REFTECH JAYA OPTIMA</h3>
            <h2 class="text-danger">Arus Kas (Metode Langsung)</h2>
            <h4>Dari {{ $startString }} ke {{ $endString }}</h4>
        </div>
        <hr>
        <div class="mb-2">
            {{-- @php
                $totalLancar = $piutang + $asset + $ppnMas;
                $totalTetap = $totalFixed - $grandTotalPenyusutan;
                $totalAktiva = $totalLancar + $totalTetap;
            @endphp --}}
            <table class="table table-borderless m-0" style="width: 100%">
                <thead class="table-light border-top">
                    <tr>
                        <th>Description</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody class="text-dark">
                    <tr>
                        <td colspan="2" class="fw-medium">Arus Kas dari Aktivitas Operasi</td>
                    </tr>
                    <tr>
                        <td>Kas dari Penjualan</td>
                        <td>{{ number_format($quotation, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Pendapatan Lain Lain</td>
                        <td class="fw-medium">{{ number_format($income, 0, ',', '.') }}</td>
                    </tr>
                    @foreach ($pendapatan as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ number_format($item->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>Kas Untuk Pembelian</td>
                        <td>TBA</td>
                    </tr>
                    @foreach ($expensePerAccount as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td class="text-danger">-{{ number_format($item->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>
                            <h5>Biaya Lain Lain</h5>
                        </td>
                        <td>{{ number_format($item->outcome, 0, ',', '.') }}</td>
                    </tr>
                    @foreach ($biaya as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-danger">-{{ number_format($item->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>
                            <h5>Laba/Rugi Penghentian Aktiva Tetap</h5>
                        </td>
                        <td>TBA</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Laba(Rugi) Operasi sebelum berubah di Operasi Aktiva dan Kewajiban</td>
                        <td class="fw-medium border-top">TBA</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fs-4 fw-bolder">Berkurang(Bertambah) pada Operasi Aktiva</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Persediaan Barang Dagang</td>
                        <td class="fw-medium border-top">TBA</td>
                    </tr>
                    <tr>
                        <td>Screw Compressor 132 KW</td>
                        <td>TBA</td>
                    </tr>
                    <tr>
                        <td>Piutang Lain-lain IDR</td>
                        <td>{{ number_format($piutang, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>PPN Masukan</td>
                        <td>{{ number_format($ppnMas, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Transakasi Aktiva Tetap</td>
                        <td class="text-danger">TBA</td>
                    </tr>
                    <tr>
                        <td class="fw-bolder">Akumulasi Penyusutan</td>
                        <td class="text-danger">TBA</td>
                    </tr>
                    <tr>
                        <td>Akum. Peny. Mesin</td>
                        <td class="text-danger">TBA</td>
                    </tr>
                    <tr>
                        <td class="fw-bolder">Jumlah Berkurang(Bertambah) pada Operasi Aktiva</td>
                        <td class="text-danger border-top">TBA</td>
                    </tr>
                    <tr>
                        <td class="fw-bolder fs-5">Bertambah (berkurang) pada Operasi Kewajiban</td>
                    </tr>
                    <tr>
                        <td>PPN Keluaran</td>
                        <td class="border-top">-{{ number_format($ppnKel, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bolder">Jumlah Bertambah (berkurang) pada Operasi Kewajiban</td>
                        <td class="text-danger border-top">-{{ number_format($ppnKel, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bolder">Kas bersih (dipakai)/ dihasilkan oleh Aktivitas Operasi</td>
                        <td class="border-top">TBA</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold fs-4">Arus Kas dari Aktivitas Investasi</td>
                    </tr>
                    <tr>
                        <td class="fw-bolder">Akumulasi Penyusutan</td>
                        <td class="text-danger">TBA</td>
                    </tr>
                    <tr>
                        <td>Peralatan Kantor</td>
                        <td>TBA</td>
                    </tr>
                    <tr>
                        <td>Transaksi Aktiva Tetap</td>
                        <td class="text-danger">TBA</td>
                    </tr>
                    <tr>
                        <td>Tools</td>
                        <td class="text-danger">TBA</td>
                    </tr>
                    <tr>
                        <td>Mesin Compressor</td>
                        <td>TBA</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Kas bersih yg dihasilkan / (dipakai) oleh Aktivitas Investasi</td>
                        <td class="fw-medium border-top">TBA</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold fs-4">Arus Kas dari Aktivitas Pendanaan</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bolder fs-5">Ekuitas</td>
                    </tr>
                    <tr>
                        <td>Dividen</td>
                        <td class="text-danger">- {{ number_format($prive, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> Kas bersih yg dihasilkan dari / (dipakai) oleh Aktivitas Pendanaan</td>
                        <td class="fw-medium border-top text-danger">-TBA</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> Kas bersih dihasilkan oleh / (dipakai) di Period ini</td>
                        <td class="fw-medium border-top">TBA</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> Kas & Setara Kas pada Awal Periode</td>
                        <td class="fw-medium border-top">TBA</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> Kas & Setara Kas pada Akhir Periode</td>
                        <td class="fw-medium border-top">TBA</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice-print-income.css" />
    <link rel="stylesheet" href="style.css">
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/js/app-invoice-print.js"></script>
@endpush
