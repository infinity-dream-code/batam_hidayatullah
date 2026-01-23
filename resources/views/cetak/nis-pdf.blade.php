<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DATA PEMBAYARAN SISWA</title>
    <style>
        @page {
            margin: 20mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
        }
        
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .date-range {
            font-size: 12px;
            margin-bottom: 15px;
        }
        
        .separator {
            border-top: 3px double #000;
            margin: 15px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: left;
            font-size: 9px;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('batam.png') }}" class="logo" alt="Logo">
        <div class="separator"></div>
        <div class="title">DATA PEMBAYARAN SISWA</div>
        <div class="date-range">
            {{ $filters['dari_tanggal'] ?? '0000-00-00' }} s.d {{ $filters['sampai_tanggal'] ?? '0000-00-00' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Masuk</th>
                <th rowspan="2">Kelompok</th>
                <th rowspan="2">NIS</th>
                <th rowspan="2">Nama</th>
                @php
                    $uniqueYears = collect($data)->pluck('tahun_angkatan')->unique()->values();
                    $uniqueKodes = collect($data)->pluck('kode')->unique()->values();
                @endphp
                @foreach($uniqueYears as $year)
                    <th colspan="{{ $uniqueKodes->count() + 1 }}">{{ substr($year, 0, 4) }}</th>
                @endforeach
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                @foreach($uniqueYears as $year)
                    @foreach($uniqueKodes as $kode)
                        <th>{{ $kode }}</th>
                    @endforeach
                    <th>Total</th>
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
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">{{ $firstItem['tahun_angkatan'] ?? '-' }}</td>
                        <td class="text-center">{{ $firstItem['kelompok'] ?? '-' }}</td>
                        <td class="text-center">{{ $nis }}</td>
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
                                <td class="text-right">{{ $amount > 0 ? number_format($amount, 0, ',', ',') : '-' }}</td>
                            @endforeach
                            <td class="text-right total-row">{{ number_format($yearTotal, 0, ',', ',') }}</td>
                            @php $totalSiswa += $yearTotal; @endphp
                        @endforeach
                        
                        <td class="text-right total-row" style="background-color: #e0e0e0;"><strong>{{ number_format($totalSiswa, 0, ',', ',') }}</strong></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data</td>
                </tr>
            @endif
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>
