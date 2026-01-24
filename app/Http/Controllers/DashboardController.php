<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\JWTHelper;

class DashboardController extends Controller
{
    public function rekapPembayaran()
    {
        return view('dashboard.rekap-pembayaran');
    }

    public function getKelas()
    {
        try {
            $token = JWTHelper::generateToken([]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ])
                ->post('http://103.23.103.43/WS_CLIENT/Batam_Hidayatullah/index.php', [
                    'token' => $token,
                    'method' => 'getKelas'
                ]);

            if ($response->successful()) {
                $res = $response->json();
                if (($res['status'] ?? 0) === 200) {
                    return response()->json([
                        'success' => true,
                        'data' => $res['data'] ?? []
                    ]);
                }
            }

            return response()->json(['success' => false, 'data' => []]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'data' => []]);
        }
    }

    public function getAkun()
    {
        try {
            $token = JWTHelper::generateToken([]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ])
                ->post('http://103.23.103.43/WS_CLIENT/Batam_Hidayatullah/index.php', [
                    'token' => $token,
                    'method' => 'getAkun'
                ]);

            if ($response->successful()) {
                $res = $response->json();
                if (($res['status'] ?? 0) === 200) {
                    return response()->json([
                        'success' => true,
                        'data' => $res['data'] ?? []
                    ]);
                }
            }

            return response()->json(['success' => false, 'data' => []]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'data' => []]);
        }
    }

    public function fetchPembayaran(Request $request)
    {
        try {
            $payload = [];

            if ($request->filled('tahun_akademik')) {
                $payload['tahun_akademik'] = $request->tahun_akademik;
            }
            if ($request->filled('unit')) {
                $payload['unit'] = $request->unit;
            }
            if ($request->filled('kelas')) {
                $payload['kelas'] = $request->kelas;
            }
            if ($request->filled('kelompok')) {
                $payload['kelompok'] = $request->kelompok;
            }
            if ($request->filled('tahun_angkatan')) {
                $payload['tahun_angkatan'] = $request->tahun_angkatan;
            }
            if ($request->filled('nis')) {
                $payload['nis'] = $request->nis;
            }
            if ($request->filled('dari_tanggal')) {
                $payload['tanggal_from'] = $request->dari_tanggal;
            }
            if ($request->filled('sampai_tanggal')) {
                $payload['tanggal_to'] = $request->sampai_tanggal;
            }
            if ($request->filled('akun')) {
                $payload['akun'] = $request->akun;
            }
            // Kode Rekening atau Bank - kirim sebagai 'bank' (FIDBANK)
            if ($request->filled('kode_rekening')) {
                $payload['bank'] = $request->kode_rekening;
            } elseif ($request->filled('bank')) {
                $payload['bank'] = $request->bank;
            }

            $token = JWTHelper::generateToken($payload);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ])
                ->post('http://103.23.103.43/WS_CLIENT/Batam_Hidayatullah/index.php', [
                    'token' => $token,
                    'method' => 'getReport'
                ]);

            if ($response->successful()) {
                $res = $response->json();
                if (($res['status'] ?? 0) === 200) {
                    return response()->json([
                        'success' => true,
                        'data' => $res['data'] ?? []
                    ]);
                }
            }

            return response()->json(['success' => false, 'data' => []], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function fetchDataForPrint(Request $request)
    {
        $payload = [];

        if ($request->filled('tahun_akademik')) {
            $payload['tahun_akademik'] = $request->tahun_akademik;
        }
        if ($request->filled('unit')) {
            $payload['unit'] = $request->unit;
        }
        if ($request->filled('kelas')) {
            $payload['kelas'] = $request->kelas;
        }
        if ($request->filled('kelompok')) {
            $payload['kelompok'] = $request->kelompok;
        }
        if ($request->filled('tahun_angkatan')) {
            $payload['tahun_angkatan'] = $request->tahun_angkatan;
        }
        if ($request->filled('nis')) {
            $payload['nis'] = $request->nis;
        }
        if ($request->filled('dari_tanggal')) {
            $payload['tanggal_from'] = $request->dari_tanggal;
        }
        if ($request->filled('sampai_tanggal')) {
            $payload['tanggal_to'] = $request->sampai_tanggal;
        }
        if ($request->filled('akun')) {
            $payload['akun'] = $request->akun;
        }
        // Kode Rekening atau Bank - kirim sebagai 'bank' (FIDBANK)
        if ($request->filled('kode_rekening')) {
            $payload['bank'] = $request->kode_rekening;
        } elseif ($request->filled('bank')) {
            $payload['bank'] = $request->bank;
        }

        $token = JWTHelper::generateToken($payload);

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])
            ->post('http://103.23.103.43/WS_CLIENT/Batam_Hidayatullah/index.php', [
                'token' => $token,
                'method' => 'getReport'
            ]);

        if ($response->successful()) {
            $res = $response->json();
            if (($res['status'] ?? 0) === 200) {
                return $res['data'] ?? [];
            }
        }

        return [];
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
}
