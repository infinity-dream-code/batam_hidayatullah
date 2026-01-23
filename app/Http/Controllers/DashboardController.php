<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\JWTHelper;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman rekap pembayaran
     */
    public function rekapPembayaran()
    {
        return view('dashboard.rekap-pembayaran');
    }

    /**
     * Get kelas dari API eksternal
     */
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
                $responseData = $response->json();
                if (isset($responseData['status']) && $responseData['status'] == 200) {
                    return response()->json([
                        'success' => true,
                        'data' => $responseData['data'] ?? []
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => []
            ]);
        }
    }

    /**
     * Fetch data pembayaran dari API eksternal
     */
    public function fetchPembayaran(Request $request)
    {
        try {
            // Generate JWT token
            $payload = [];
            
            // Tambahkan filter jika ada (sesuai dengan parameter WS)
            if ($request->filled('tahun_akademik')) {
                $payload['tahun_akademik'] = $request->tahun_akademik;
            }
            // Mapping: kelas filter = kelas di API getReport
            if ($request->filled('kelas')) {
                $payload['kelas'] = $request->kelas;
            }
            if ($request->filled('unit')) {
                $payload['unit'] = $request->unit;
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
            // Parameter tanggal sesuai dengan WS: tanggal_from & tanggal_to
            if ($request->filled('dari_tanggal')) {
                $payload['tanggal_from'] = $request->dari_tanggal; // Format: YYYY-MM-DD
            }
            if ($request->filled('sampai_tanggal')) {
                $payload['tanggal_to'] = $request->sampai_tanggal; // Format: YYYY-MM-DD
            }
            if ($request->filled('kode_rekening')) {
                $payload['kode_rekening'] = $request->kode_rekening;
            }
            if ($request->filled('bank')) {
                $payload['bank'] = $request->bank;
            }
            if ($request->filled('nama_tagihan')) {
                $payload['nama_tagihan'] = $request->nama_tagihan;
            }

            $token = JWTHelper::generateToken($payload);

            // Hit API eksternal - kirim JWT di body
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
                $responseData = $response->json();
                
                // WS return format: {"status": 200, "data": [...]}
                if (isset($responseData['status']) && $responseData['status'] == 200) {
                    $data = $responseData['data'] ?? [];
                    
                    return response()->json([
                        'success' => true,
                        'data' => $data
                    ]);
                } else {
                    $message = $responseData['message'] ?? 'Unknown error';
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data dari API. Status: ' . $response->status()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch data dari API untuk cetak
     */
    private function fetchDataForPrint(Request $request)
    {
        $payload = [];
        
        if ($request->filled('tahun_akademik')) {
            $payload['tahun_akademik'] = $request->tahun_akademik;
        }
        // Mapping: kelas (jenjang) = jenjang di API
        if ($request->filled('kelas')) {
            $payload['kelas'] = $request->kelas; // jenjang
        }
        if ($request->filled('unit')) {
            $payload['unit'] = $request->unit;
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
            $responseData = $response->json();
            if (isset($responseData['status']) && $responseData['status'] == 200) {
                return $responseData['data'] ?? [];
            }
        }

        return [];
    }

    /**
     * Cetak Rekap PDF
     */
    public function cetakRekapPDF(Request $request)
    {
        $data = $this->fetchDataForPrint($request);
        $filters = $request->all();
        
        return view('cetak.rekap-pdf', compact('data', 'filters'));
    }

    /**
     * Cetak Rekap Excel
     */
    public function cetakRekapExcel(Request $request)
    {
        $data = $this->fetchDataForPrint($request);
        $filters = $request->all();
        
        return view('cetak.rekap-excel', compact('data', 'filters'));
    }

    /**
     * Cetak per NIS PDF - Tampilkan semua siswa (tidak filter by NIS)
     */
    public function cetakNISPDF(Request $request)
    {
        // Hapus filter NIS untuk tampilkan semua siswa
        $requestWithoutNIS = $request->duplicate();
        $requestWithoutNIS->merge(['nis' => '']);
        
        $data = $this->fetchDataForPrint($requestWithoutNIS);
        $filters = $request->all();
        
        return view('cetak.nis-pdf', compact('data', 'filters'));
    }

    /**
     * Cetak per NIS Excel - Tampilkan semua siswa (tidak filter by NIS)
     */
    public function cetakNISExcel(Request $request)
    {
        // Hapus filter NIS untuk tampilkan semua siswa
        $requestWithoutNIS = $request->duplicate();
        $requestWithoutNIS->merge(['nis' => '']);
        
        $data = $this->fetchDataForPrint($requestWithoutNIS);
        $filters = $request->all();
        
        return view('cetak.nis-excel', compact('data', 'filters'));
    }
}

