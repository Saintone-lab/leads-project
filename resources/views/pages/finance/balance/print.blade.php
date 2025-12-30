@extends('layouts.sales.app')
@section('title', 'Print Opname')
<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="text-center">
            <h3>PT. REFTECH JAYA OPTIMA</h3>
            <h2 class="text-danger">Neraca (Standar)</h2>
            <h4>Dari {{ $startString }} ke {{ $endString }}</h4>
        </div>
        <hr>
        <div class="mb-2">
            @php
                $totalLancar = $piutang + $asset + $ppnMas;
                $totalTetap = $totalFixed - $grandTotalPenyusutan;
                $totalAktiva = $totalLancar + $totalTetap;
            @endphp
            <table class="table table-borderless m-0" style="width: 100%">
                <thead class="table-light border-top">
                    <tr>
                        <th>Description</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody class="text-dark">
                    <tr>
                        <td colspan="2" class="fw-medium">Aktiva</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium"> Aktiva Lancar</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> Bank</td>
                        <td class="fw-medium">TBA</td>
                    </tr>
                    <tr>
                        <td> BCA IDR</td>
                        <td>TBA</td>
                    </tr>
                    <tr>
                        <td> Capital Cabang Palembang</td>
                        <td>TBA</td>
                    </tr>
                    <tr>
                        <td> Modal Cabang Palembang</td>
                        <td>TBA</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Jumlah Kas dan Bank</td>
                        <td class="fw-medium border-top">TBA</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Piutang Dagang</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> Piutang Usaha</td>
                        <td class="fw-medium">{{ number_format($piutang, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td> Piutang Usaha</td>
                        <td>{{ number_format($piutang, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Jumlah Piutang Dagang</td>
                        <td class="fw-medium border-top">{{ number_format($piutang, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Persediaan</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> Persediaan Barang Dagang</td>
                        <td class="fw-medium">{{ number_format($asset, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td> Persediaan Barang Dagang</td>
                        <td>{{ number_format($asset, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Jumlah Persediaan</td>
                        <td class="fw-medium border-top">{{ number_format($asset, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Aktiva Lancar Lainnya</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> PPN Masukan</td>
                        <td class="fw-medium">{{ number_format($ppnMas, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> Jumlah Aktiva Lancar Lainnya</td>
                        <td class="fw-medium border-top">{{ number_format($ppnMas, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Jumlah Aktiva Lancar</td>
                        <td class="fw-medium border-top">{{ number_format($totalLancar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold">Aktiva Tetap</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Nilai Histori</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> Aset Tetap</td>
                        <td class="fw-medium border-top">{{ number_format($totalFixed, 0, ',', '.') }}</td>
                    </tr>
                    @foreach ($fixedAsset as $item)
                        <tr>
                            <td>{{ $item->type }}</td>
                            <td>{{ number_format($item->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="fw-medium">Jumlah Nilai Histori</td>
                        <td class="fw-medium border-top">{{ number_format($totalFixed ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Akumulasi Penyusutan</td>
                    </tr>
                    <tr>
                        <td class="fw-medium"> Akumulasi Penyusutan</td>
                        <td class="fw-medium border-top">{{ number_format($grandTotalPenyusutan, 0, ',', '.') }}</td>
                    </tr>
                    @foreach ($penyusutan as $item)
                        <tr>
                            <td>Akum. Penys. {{ $item['type'] }}</td>
                            <td class="text-danger"> - {{ number_format($item['total_penyusutan'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="fw-medium">Jumlah Akumulasi Penyusutan</td>
                        <td class="text-danger border-top">{{ number_format($grandTotalPenyusutan ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Jumlah Aktiva Tetap</td>
                        <td class="fw-medium border-top">{{ number_format($totalTetap ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">OTHER ASSETS</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Jumlah OTHER ASSETS</td>
                        <td class="fw-medium border-top">{{ number_format(0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Jumlah Aktiva</td>
                        <td class="fw-medium border-top">{{ number_format($totalAktiva, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Kewajiban dan Ekuitas</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Kewajiban</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Kewajiban Lancar</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Hutang Dagang</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Jumlah Hutang Dagang</td>
                        <td class="fw-medium border-top">{{ number_format(0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Kewajiban Lancar Lain</td>
                    </tr>
                    <tr>
                        <td> PPN Keluaran</td>
                        <td>{{ number_format($ppnKel, 0, ',', '.') }}</td>
                    </tr>
                    <td class="fw-medium">Jumlah Kewajiban Lancar Lain</td>
                    <td class="fw-medium border-top">{{ number_format($ppnKel ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    </tr>
                    <td class="fw-medium">Jumlah Kewajiban Lancar</td>
                    <td class="fw-medium border-top">{{ number_format($ppnKel ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Kewajiban Jangka Panjang</td>
                    </tr>
                    </tr>
                    <td class="fw-medium">Jumlah Kewajiban Jangka Panjang</td>
                    <td class="fw-medium border-top">{{ number_format(0, 0, ',', '.') }}</td>
                    </tr>
                    </tr>
                    <td class="fw-medium">Jumlah Kewajiban</td>
                    <td class="fw-medium border-top">{{ number_format($ppnKel ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-medium">Ekuitas</td>
                    </tr>
                    @php
                        if (@$month) {
                            $ekuitas = 250000000 + $labaTahunTahun - $prive - $labaBulanIni;
                            $totalekuitas = $ekuitas + $labaBulanIni;
                            $sebelumnya = $labaTahunTahun - $labaBulanIni;
                        } else {
                            $ekuitas = 250000000 + $labaTahunTahun - $prive - $labaTahunIni;
                            $totalekuitas = $ekuitas + $labaTahunIni;
                            $sebelumnya = $labaTahunTahun - $labaTahunIni;
                        }
                        $ekujiban = $totalekuitas + $ppnKel;
                    @endphp
                    <tr>
                        <td class="fw-medium">Ekuitas</td>
                        <td class="fw-medium">{{ number_format($ekuitas, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Modal</td>
                        <td>{{ number_format(250000000, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Laba Ditahan</td>
                        <td>{{ number_format($labaTahunLalu, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Prive</td>
                        <td class="text-danger">- {{ number_format($prive, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Laba Tahun Sebelumnya</td>
                        <td>{{ number_format($sebelumnya, 0, ',', '.') }}</td>
                        {{-- <td>{{ number_format($sebelumnya, 0, ',', '.') }}</td> --}}
                    </tr>
                    <tr>
                        <td>OPENING BALANCE EQUITY</td>
                        <td>{{ number_format(0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Laba {{ @$month ? 'Bulan' : 'Tahun' }} Ini</td>
                        <td>{{ number_format(@$month ? $labaBulanIni : $labaTahunIni, 0, ',', '.') }}</td>
                    </tr>
                    </tr>
                    <td class="fw-medium">Jumlah Ekuitas</td>
                    <td class="fw-medium border-top">{{ number_format($totalekuitas ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    </tr>
                    <td class="fw-medium">Jumlah Ekuitas Dan Kewajiban</td>
                    <td class="fw-medium border-top">{{ number_format($ekujiban ?? 0, 0, ',', '.') }}</td>
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
