@php
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Data_Pembayaran_NIS_' . ($filters['nis'] ?? 'All') . '_' . date('YmdHis') . '.xls"');
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DATA PEMBAYARAN SISWA</title>
</head>
<body>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <td colspan="20" align="center" style="font-size: 16px; font-weight: bold;">
                    DATA PEMBAYARAN SISWA
                </td>
            </tr>
            <tr>
                <td colspan="20" align="center">
                    {{ $filters['dari_tanggal'] ?? '0000-00-00' }} s.d {{ $filters['sampai_tanggal'] ?? '0000-00-00' }}
                </td>
            </tr>
            <tr><td colspan="20"></td></tr>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td rowspan="2">No</td>
                <td rowspan="2">Masuk</td>
                <td rowspan="2">Kelas</td>
                <td rowspan="2">NIS</td>
                <td rowspan="2">Nama</td>
                @php
                    $uniqueYears = collect($data)->pluck('tahun_angkatan')->unique()->values();
                    $uniqueKodes = collect($data)->pluck('kode')->unique()->values();
                @endphp
                @foreach($uniqueYears as $year)
                    <td colspan="{{ $uniqueKodes->count() + 1 }}">{{ substr($year, 0, 4) }}</td>
                @endforeach
                <td rowspan="2">Total</td>
            </tr>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                @foreach($uniqueYears as $year)
                    @foreach($uniqueKodes as $kode)
                        <td>{{ $kode }}</td>
                    @endforeach
                    <td>Total</td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $groupedByNIS = collect($data)->groupBy('nis');
                $no = 1;
            @endphp

            @if(count($data) > 0)
                @foreach($groupedByNIS as $nis => $items)
                    @php
                        $firstItem = $items->first();
                        $totalSiswa = 0;
                    @endphp
                    <tr>
                        <td align="center">{{ $no++ }}</td>
                        <td align="center">{{ $firstItem['tahun_angkatan'] ?? '-' }}</td>
                        <td align="center">{{ $firstItem['kelompok'] ?? '-' }}</td>
                        <td align="center">{{ $nis }}</td>
                        <td>{{ $firstItem['nama'] ?? '-' }}</td>
                        
                        @foreach($uniqueYears as $year)
                            @php
                                $yearItems = $items->where('tahun_angkatan', $year);
                                $yearTotal = 0;
                            @endphp
                            @foreach($uniqueKodes as $kode)
                                @php
                                    $amount = $yearItems->where('kode', $kode)->sum('jumlah');
                                    $yearTotal += $amount;
                                @endphp
                                <td align="right">{{ $amount > 0 ? number_format($amount, 0, ',', ',') : '-' }}</td>
                            @endforeach
                            <td align="right" style="background-color: #f9f9f9; font-weight: bold;">{{ number_format($yearTotal, 0, ',', ',') }}</td>
                            @php $totalSiswa += $yearTotal; @endphp
                        @endforeach
                        
                        <td align="right" style="background-color: #e0e0e0; font-weight: bold;">{{ number_format($totalSiswa, 0, ',', ',') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="20" align="center">Tidak ada data</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
