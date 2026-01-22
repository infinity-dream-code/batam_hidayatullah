<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>REKAP PEMBAYARAN SISWA</title>
    <style>
        @page {
            margin: 20mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
            padding: 8px;
            text-align: left;
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
        
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('batam.png') }}" class="logo" alt="Logo">
        <div class="separator"></div>
        <div class="title">REKAP PEMBAYARAN SISWA</div>
        <div class="date-range">
            {{ $filters['dari_tanggal'] ?? '0000-00-00' }} s.d {{ $filters['sampai_tanggal'] ?? '0000-00-00' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Thn</th>
                <th rowspan="2">Kode</th>
                <th rowspan="2">Nama</th>
                <th colspan="2">10R</th>
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                <th>REGULER</th>
                <th>Sum</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedData = collect($data)->groupBy('tahun_angkatan');
                $grandTotal = 0;
            @endphp

            @if(count($data) > 0)
                @foreach($groupedData as $tahun => $items)
                    @php
                        $yearTotal = 0;
                        $groupedByKode = $items->groupBy('kode');
                        $rowCount = $groupedByKode->count() + 1; // +1 untuk row Sum
                    @endphp

                    @foreach($groupedByKode as $kode => $kodeItems)
                        @php
                            $sumByKode = $kodeItems->sum('jumlah');
                            $yearTotal += $sumByKode;
                            $namaTagihan = $kodeItems->first()['nama_tagihan'] ?? '-';
                        @endphp
                        <tr>
                            @if($loop->first)
                                <td rowspan="{{ $rowCount }}" style="vertical-align: top;">{{ $tahun }}</td>
                            @endif
                            <td class="text-center">{{ $kode }}</td>
                            <td>{{ $namaTagihan }}</td>
                            <td class="text-right">{{ number_format($sumByKode, 0, ',', ',') }}</td>
                            <td class="text-right">{{ number_format($sumByKode, 0, ',', ',') }}</td>
                            <td class="text-right">{{ number_format($sumByKode, 0, ',', ',') }}</td>
                        </tr>
                    @endforeach

                    <tr class="total-row">
                        <td colspan="2" class="text-center"><strong>Sum</strong></td>
                        <td class="text-right"><strong>{{ number_format($yearTotal, 0, ',', ',') }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($yearTotal, 0, ',', ',') }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($yearTotal, 0, ',', ',') }}</strong></td>
                    </tr>

                    @php $grandTotal += $yearTotal; @endphp
                @endforeach

                <tr class="total-row" style="background-color: #e0e0e0;">
                    <td colspan="3" class="text-center"><strong>Total</strong></td>
                    <td class="text-right"><strong>{{ number_format($grandTotal, 0, ',', ',') }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($grandTotal, 0, ',', ',') }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($grandTotal, 0, ',', ',') }}</strong></td>
                </tr>
            @else
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <div>Batam, {{ date('d/m/Y') }}</div>
            <div class="signature-line">Administrasi</div>
        </div>
        <div class="signature">
            <div class="signature-line">Bagian Keuangan</div>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
