@php
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Rekap_Pembayaran_' . date('YmdHis') . '.xls"');
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>REKAP PEMBAYARAN SISWA</title>
</head>
<body>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <td colspan="6" align="center" style="font-size: 16px; font-weight: bold;">
                    REKAP PEMBAYARAN SISWA
                </td>
            </tr>
            <tr>
                <td colspan="6" align="center">
                    {{ $filters['dari_tanggal'] ?? '0000-00-00' }} s.d {{ $filters['sampai_tanggal'] ?? '0000-00-00' }}
                </td>
            </tr>
            <tr><td colspan="6"></td></tr>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td>Thn</td>
                <td>Kode</td>
                <td>Nama</td>
                <td>REGULER</td>
                <td>Sum</td>
                <td>Total</td>
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
                        $rowCount = $groupedByKode->count() + 1;
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
                            <td align="center">{{ $kode }}</td>
                            <td>{{ $namaTagihan }}</td>
                            <td align="right">{{ number_format($sumByKode, 0, ',', ',') }}</td>
                            <td align="right">{{ number_format($sumByKode, 0, ',', ',') }}</td>
                            <td align="right">{{ number_format($sumByKode, 0, ',', ',') }}</td>
                        </tr>
                    @endforeach

                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td colspan="2" align="center">Sum</td>
                        <td align="right">{{ number_format($yearTotal, 0, ',', ',') }}</td>
                        <td align="right">{{ number_format($yearTotal, 0, ',', ',') }}</td>
                        <td align="right">{{ number_format($yearTotal, 0, ',', ',') }}</td>
                    </tr>

                    @php $grandTotal += $yearTotal; @endphp
                @endforeach

                <tr style="background-color: #e0e0e0; font-weight: bold;">
                    <td colspan="3" align="center">Total</td>
                    <td align="right">{{ number_format($grandTotal, 0, ',', ',') }}</td>
                    <td align="right">{{ number_format($grandTotal, 0, ',', ',') }}</td>
                    <td align="right">{{ number_format($grandTotal, 0, ',', ',') }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="6" align="center">Tidak ada data</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
