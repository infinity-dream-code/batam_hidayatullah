<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\JWTHelper;

class DashboardController extends Controller
{
    private string $wsUrl = 'http://103.23.103.43/WS_CLIENT/Batam_Hidayatullah/index.php';

    public function rekapPembayaran()
    {
        return view('dashboard.rekap-pembayaran');
    }

    private function norm($v): string
    {
        $v = trim((string)$v);
        if ($v === '') return '';
        $lower = strtolower($v);

        $bad = [
            'all',
            'semua',
            'semua unit',
            'semua kelas',
            'semua kelompok',
            'semua kode rekening',
            'semua tahun',
            'semua tagihan',
        ];

        if (in_array($lower, $bad, true)) return '';
        if (str_starts_with($lower, 'semua ')) return '';
        return $v;
    }

    private function callWS(string $method, array $payload = []): array
    {
        $token = JWTHelper::generateToken($payload);

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($this->wsUrl, [
                'token' => $token,
                'method' => $method,
            ]);

        if (!$response->successful()) {
            return ['success' => false, 'data' => []];
        }

        $res = $response->json();
        if (($res['status'] ?? 0) !== 200) {
            return ['success' => false, 'data' => []];
        }

        return ['success' => true, 'data' => $res['data'] ?? []];
    }

    public function getKelas()
    {
        try {
            $out = $this->callWS('getKelas', []);
            return response()->json(['success' => $out['success'], 'data' => $out['data']]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'data' => []]);
        }
    }

    public function getAkun()
    {
        try {
            $out = $this->callWS('getAkun', []);
            return response()->json(['success' => $out['success'], 'data' => $out['data']]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'data' => []]);
        }
    }

    public function getTagihan()
    {
        try {
            $out = $this->callWS('getTagihan', []);
            return response()->json(['success' => $out['success'], 'data' => $out['data']]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'data' => []]);
        }
    }

    private function buildPayload(Request $request): array
    {
        $payload = [];

        $tahunAkademik = $this->norm($request->input('tahun_akademik'));
        $unit = $this->norm($request->input('unit'));
        $kelas = $this->norm($request->input('kelas'));
        $kelompok = $this->norm($request->input('kelompok'));
        $tahunAngkatan = $this->norm($request->input('tahun_angkatan'));
        $nis = $this->norm($request->input('nis'));
        $bank = $this->norm($request->input('bank'));
        $akun = $this->norm($request->input('kode_rekening') ?: $request->input('akun'));

        $tagihanValue = $this->norm($request->input('nama_tagihan') ?: $request->input('tagihan'));
        $tglFrom = $this->norm($request->input('dari_tanggal') ?: $request->input('tanggal_from'));
        $tglTo = $this->norm($request->input('sampai_tanggal') ?: $request->input('tanggal_to'));

        if ($tahunAkademik !== '') $payload['tahun_akademik'] = $tahunAkademik;
        if ($unit !== '') $payload['unit'] = $unit;
        if ($kelas !== '') $payload['kelas'] = $kelas;
        if ($kelompok !== '') $payload['kelompok'] = $kelompok;
        if ($tahunAngkatan !== '') $payload['tahun_angkatan'] = $tahunAngkatan;
        if ($nis !== '') $payload['nis'] = $nis;
        if ($tglFrom !== '') $payload['tanggal_from'] = $tglFrom;
        if ($tglTo !== '') $payload['tanggal_to'] = $tglTo;
        if ($akun !== '') {
            $payload['akun'] = $akun;
            $payload['kode_akun'] = $akun; // Fallback untuk WS
        }
        if ($bank !== '') $payload['bank'] = $bank;

        // Tagihan: kirim sebagai 'tagihan' dan 'kode_tagihan' untuk kompatibilitas dengan WS
        // WS akan menggunakan tagihan jika ada, atau kode_tagihan jika tagihan kosong
        if ($tagihanValue !== '') {
            $payload['tagihan'] = $tagihanValue;
            $payload['kode_tagihan'] = $tagihanValue; // Fallback untuk WS
        }

        return $payload;
    }

    public function fetchPembayaran(Request $request)
    {
        try {
            $payload = $this->buildPayload($request);
            $out = $this->callWS('getReport', $payload);

            if ($out['success']) {
                return response()->json(['success' => true, 'data' => $out['data']]);
            }

            return response()->json(['success' => false, 'data' => []], 500);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function fetchDataForPrint(Request $request): array
    {
        try {
            $payload = $this->buildPayload($request);
            $out = $this->callWS('getReport', $payload);
            return $out['success'] ? $out['data'] : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function cetakRekapPDF(Request $request)
    {
        $data = $this->fetchDataForPrint($request);
        $filters = $request->all();
        return view('cetak.rekap-pdf', compact('data', 'filters'));
    }

    public function cetakRekapExcel(Request $request)
    {
        $data = $this->fetchDataForPrint($request);
        $filters = $request->all();
        return view('cetak.rekap-excel', compact('data', 'filters'));
    }

    public function cetakNISPDF(Request $request)
    {
        $requestWithoutNIS = $request->duplicate();
        $requestWithoutNIS->merge(['nis' => '']);
        $data = $this->fetchDataForPrint($requestWithoutNIS);
        $filters = $request->all();
        return view('cetak.nis-pdf', compact('data', 'filters'));
    }

    public function cetakNISExcel(Request $request)
    {
        $requestWithoutNIS = $request->duplicate();
        $requestWithoutNIS->merge(['nis' => '']);
        $data = $this->fetchDataForPrint($requestWithoutNIS);
        $filters = $request->all();
        return view('cetak.nis-excel', compact('data', 'filters'));
    }
}
