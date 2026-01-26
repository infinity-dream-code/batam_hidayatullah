@extends('layouts.dashboard')

@section('title', 'Rekap Pembayaran')

@section('content')
<style>
    /* MAIN CONTAINER - TIDAK SCROLL */
    .rekap-container {
        width: 100%;
        max-width: 100%;
    }
    
    /* FILTER BOX */
    .filter-box {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .filter-column {
        flex: 1;
        min-width: 280px;
    }
    
    
    .filter-buttons {
        flex: 0 0 auto;
        min-width: 160px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .filter-row {
        display: flex;
        margin-bottom: 0.5rem;
        align-items: center;
    }
    
    .filter-label {
        background: #6c5ce7;
        color: white;
        padding: 0.5rem 0.75rem;
        font-weight: 600;
        font-size: 0.875rem;
        text-align: center;
        flex: 0 0 150px;
    }
    
    .filter-input {
        flex: 1;
        padding: 0.4rem 0.75rem;
        border: 1px solid #dee2e6;
        font-size: 0.875rem;
        outline: none;
    }
    
    .filter-input:focus {
        border-color: #6c5ce7;
    }
    
    
    .btn-action {
        background: #6c757d;
        color: white;
        border: none;
        padding: 0.625rem 1rem;
        border-radius: 4px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }
    
    .btn-action:hover {
        background: #5a6268;
    }
    
    /* Dropdown Menu */
    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        min-width: 180px;
        z-index: 1000;
        margin-top: 5px;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-menu a {
        display: block;
        padding: 10px 16px;
        color: #333;
        text-decoration: none;
        transition: all 0.2s;
        border-bottom: 1px solid #f0f0f0;
    }

    .dropdown-menu a:last-child {
        border-bottom: none;
    }

    .dropdown-menu a:hover {
        background: #f8f9fa;
        color: #6b5ce7;
    }

    .dropdown-menu a i {
        margin-right: 8px;
        width: 16px;
    }
    
    /* TABLE WRAPPER - INI YANG SCROLL */
    .table-scroll-wrapper {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        -webkit-overflow-scrolling: touch;
    }
    
    /* NO CUSTOM SCROLLBAR COLOR */
    
    /* TABLE */
    .payment-table {
        width: 1600px;
        border-collapse: collapse;
        margin: 0;
    }
    
    .payment-table thead {
        background: #6c5ce7;
        color: white;
    }
    
    .payment-table thead th {
        padding: 0.875rem 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        text-align: left;
        white-space: nowrap;
    }
    
    .payment-table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
        border-bottom: 1px solid #f0f0f0;
        white-space: nowrap;
    }
    
    .payment-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .payment-table tbody tr.selected {
        background: #e3f2fd;
    }
    
    .payment-table tfoot td {
        background: #f8f9fa;
        font-weight: 700;
        padding: 1rem 0.5rem;
        border-top: 2px solid #6c5ce7;
    }
    
    .amount-cell {
        text-align: right;
        font-weight: 600;
    }
    
    .total-amount {
        color: #6c5ce7;
        font-size: 1rem;
    }
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
        .filter-box {
            padding: 1rem;
        }
        .filter-column {
            min-width: 100%;
        }
        .filter-label {
            font-size: 0.75rem;
            flex: 0 0 120px;
        }
        .filter-input {
            font-size: 0.75rem;
        }
        .payment-table thead th {
            font-size: 0.75rem;
            padding: 0.5rem 0.4rem;
        }
        .payment-table tbody td {
            font-size: 0.75rem;
            padding: 0.5rem 0.4rem;
        }
    }
</style>

<div class="rekap-container">
    <!-- Filter Box -->
    <div class="filter-box">
        <!-- Left Column -->
        <div class="filter-column">
            <div class="filter-row">
                <div class="filter-label">Tahun Akademik</div>
                <select class="filter-input" id="tahun_akademik">
                    <option value="">Semua</option>
                    <option>2026/2027 - GENAP</option>
                    <option>2026/2027 - GANJIL</option>
                    <option>2025/2026 - GENAP</option>
                    <option>2025/2026 - GANJIL</option>
                    <option>2024/2025 - GENAP</option>
                    <option>2024/2025 - GANJIL</option>
                    <option>2023/2024 - GENAP</option>
                    <option>2023/2024 - GANJIL</option>
                    <option>2022/2023 - GENAP</option>
                    <option>2022/2023 - GANJIL</option>
                    <option>2021/2022 - GENAP</option>
                    <option>2021/2022 - GANJIL</option>
                    <option>2020/2021 - GENAP</option>
                    <option>2020/2021 - GANJIL</option>
                    <option>2019/2020 - GENAP</option>
                    <option>2019/2020 - GANJIL</option>
                    <option>2018/2019 - GENAP</option>
                    <option>2018/2019 - GANJIL</option>
                </select>
            </div>
            <div class="filter-row">
                <div class="filter-label">Unit</div>
                <select class="filter-input" id="unit">
                    <option value="">Semua Unit</option>
                </select>
            </div>
            <div class="filter-row">
                <div class="filter-label">Kelas</div>
                <select class="filter-input" id="kelas">
                    <option value="">Semua Kelas</option>
                </select>
            </div>
            <div class="filter-row">
                <div class="filter-label">Kelompok</div>
                <select class="filter-input" id="kelompok">
                    <option value="">Semua Kelompok</option>
                </select>
            </div>
            <div class="filter-row">
                <div class="filter-label">Tahun Angkatan</div>
                <select class="filter-input" id="tahun_angkatan">
                    <option value="">Semua</option>
                    <option>2026/2027 - GENAP</option>
                    <option>2026/2027 - GANJIL</option>
                    <option>2025/2026 - GENAP</option>
                    <option>2025/2026 - GANJIL</option>
                    <option>2024/2025 - GENAP</option>
                    <option>2024/2025 - GANJIL</option>
                    <option>2023/2024 - GENAP</option>
                    <option>2023/2024 - GANJIL</option>
                    <option>2022/2023 - GENAP</option>
                    <option>2022/2023 - GANJIL</option>
                    <option>2021/2022 - GENAP</option>
                    <option>2021/2022 - GANJIL</option>
                    <option>2020/2021 - GENAP</option>
                    <option>2020/2021 - GANJIL</option>
                    <option>2019/2020 - GENAP</option>
                    <option>2019/2020 - GANJIL</option>
                    <option>2018/2019 - GENAP</option>
                    <option>2018/2019 - GANJIL</option>
                </select>
            </div>
            <div class="filter-row">
                <div class="filter-label">NIS</div>
                <input type="text" class="filter-input" id="nis" placeholder="">
            </div>
        </div>

        <!-- Right Column -->
        <div class="filter-column">
            <div class="filter-row">
                <div class="filter-label">Dari Tanggal</div>
                <input type="date" class="filter-input" id="dari_tanggal">
            </div>
            <div class="filter-row">
                <div class="filter-label">Sampai Tanggal</div>
                <input type="date" class="filter-input" id="sampai_tanggal">
            </div>
            <div class="filter-row">
                <div class="filter-label">Bank</div>
                <select class="filter-input" id="bank">
                    <option value="">ALL</option>
                    <option value="1">ATM</option>
                    <option value="2">Teller</option>
                    <option value="3">IBANK</option>
                    <option value="4">EDC</option>
                    <option value="5">MOBILE</option>
                    <option value="1140000">Manual CASH</option>
                    <option value="1140001">Manual BMI</option>
                    <option value="1140002">Manual SALDO</option>
                    <option value="1140003">Transfer Bank Lain</option>
                    <option value="1140004">Transfer Bank BNI</option>
                    <option value="1140005">Transfer Bank BRI</option>
                    <option value="1200001">Loket Manual - Beasiswa</option>
                    <option value="1200002">Loket Manual - Potongan</option>
                    <option value="6">ALL BMI</option>
                </select>
            </div>
            <div class="filter-row">
                <div class="filter-label">Kode Rekening</div>
                <select class="filter-input" id="kode_rekening">
                    <option value="">Semua Kode Rekening</option>
                </select>
            </div>
            <div class="filter-row">
                <div class="filter-label">Nama Tagihan</div>
                <select class="filter-input" id="nama_tagihan">
                    <option value="">Semua Tagihan</option>
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="filter-buttons">
            <button class="btn-action" onclick="fetchData()" id="btnLihat">
                <i class="bi bi-search"></i> Lihat
            </button>
            
            <!-- Cetak Rekap Dropdown -->
            <div style="position: relative; display: inline-block;">
                <button class="btn-action" onclick="toggleDropdown('dropdownRekap')">
                    <i class="bi bi-printer"></i> Cetak Rekap <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
                </button>
                <div id="dropdownRekap" class="dropdown-menu">
                    <a href="#" onclick="cetakRekap('pdf'); return false;">
                        <i class="bi bi-file-pdf"></i> Export ke PDF
                    </a>
                    <a href="#" onclick="cetakRekap('excel'); return false;">
                        <i class="bi bi-file-excel"></i> Export ke Excel
                    </a>
                </div>
            </div>
            
            <!-- Cetak per NIS Dropdown -->
            <div style="position: relative; display: inline-block;">
                <button class="btn-action" onclick="toggleDropdown('dropdownNIS')">
                    <i class="bi bi-file-earmark-text"></i> Cetak per NIS <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
                </button>
                <div id="dropdownNIS" class="dropdown-menu">
                    <a href="#" onclick="cetakPerNIS('pdf'); return false;">
                        <i class="bi bi-file-pdf"></i> Export ke PDF
                    </a>
                    <a href="#" onclick="cetakPerNIS('excel'); return false;">
                        <i class="bi bi-file-excel"></i> Export ke Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loading" style="display: none; text-align: center; padding: 2rem;">
        <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #6c5ce7; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        <p style="margin-top: 1rem; color: #666;">Memuat data...</p>
    </div>
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <!-- Table Wrapper - HANYA INI YANG SCROLL -->
    <div class="table-scroll-wrapper" id="tableWrapper">
        <table class="payment-table" id="paymentTable">
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Kelas</th>
                    <th>Kelompok</th>
                    <th>Tahun Angkatan</th>
                    <th>Kode</th>
                    <th>Nama Tagihan</th>
                    <th>Jumlah</th>
                    <th>NamaPost</th>
                    <th>No Pend</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td colspan="12" style="text-align: center; padding: 2rem; color: #999;">
                        Klik tombol "Lihat" untuk memuat data pembayaran
                    </td>
                </tr>
            </tbody>
            <tfoot id="tableFoot">
                <tr>
                    <td colspan="6" class="total-amount">Total</td>
                    <td class="amount-cell total-amount" id="grandTotal">0</td>
                    <td colspan="5"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    // Fetch data dari API
    async function fetchData() {
        const btnLihat = document.getElementById('btnLihat');
        const loading = document.getElementById('loading');
        const tableWrapper = document.getElementById('tableWrapper');
        const tableBody = document.getElementById('tableBody');
        const grandTotalEl = document.getElementById('grandTotal');

        // Show loading
        btnLihat.disabled = true;
        btnLihat.innerHTML = '<i class="bi bi-hourglass-split"></i> Loading...';
        tableWrapper.style.display = 'none';
        loading.style.display = 'block';

        // Get filter values - sesuai dengan WS getReport
        const filters = {
            tahun_akademik: document.getElementById('tahun_akademik').value,
            unit: document.getElementById('unit').value,
            kelas: document.getElementById('kelas').value,
            kelompok: document.getElementById('kelompok').value,
            tahun_angkatan: document.getElementById('tahun_angkatan').value,
            nis: document.getElementById('nis').value,
            dari_tanggal: document.getElementById('dari_tanggal').value,
            sampai_tanggal: document.getElementById('sampai_tanggal').value,
            kode_rekening: document.getElementById('kode_rekening').value, // kirim sebagai 'akun' atau 'kode_akun' (dari getAkun)
            nama_tagihan: document.getElementById('nama_tagihan').value, // kirim sebagai 'tagihan' atau 'kode_tagihan' (dari getTagihan)
            bank: document.getElementById('bank').value, // kirim sebagai 'bank' (FIDBANK) - hardcode kode rekening
        };

        try {
            const response = await fetch('{{ route("api.fetch-pembayaran") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(filters)
            });

            const result = await response.json();

            console.log('API Response:', result); // Debug

            if (result.success && result.data) {
                // Handle if data is nested in another property
                const dataArray = Array.isArray(result.data) ? result.data : 
                                 (result.data.data ? result.data.data : []);
                
                console.log('Data Array:', dataArray); // Debug
                displayData(dataArray);
            } else {
                tableBody.innerHTML = '<tr><td colspan="12" style="text-align: center; padding: 2rem; color: #ff4757;">' + 
                    (result.message || 'Gagal memuat data') + '</td></tr>';
            }
        } catch (error) {
            console.error('Error:', error);
            tableBody.innerHTML = '<tr><td colspan="12" style="text-align: center; padding: 2rem; color: #ff4757;">Error: ' + error.message + '</td></tr>';
        } finally {
            // Hide loading
            btnLihat.disabled = false;
            btnLihat.innerHTML = '<i class="bi bi-search"></i> Lihat';
            loading.style.display = 'none';
            tableWrapper.style.display = 'block';
        }
    }

    // Display data in table
    function displayData(data) {
        const tableBody = document.getElementById('tableBody');
        const grandTotalEl = document.getElementById('grandTotal');
        
        console.log('=== Display Data Debug ===');
        console.log('Raw data:', data);
        console.log('Type:', typeof data);
        console.log('Is Array?:', Array.isArray(data));
        console.log('Constructor:', data?.constructor?.name);
        
        // Force convert to array if possible
        let dataArray = data;
        
        // If it's an object with numbered keys, convert to array
        if (typeof data === 'object' && data !== null && !Array.isArray(data)) {
            // Try to convert object to array
            dataArray = Object.values(data);
            console.log('Converted object to array:', dataArray);
        }
        
        // If still not array, show error
        if (!Array.isArray(dataArray)) {
            console.error('Cannot convert to array:', data);
            tableBody.innerHTML = '<tr><td colspan="12" style="text-align: center; padding: 2rem; color: #ff4757;">Format data tidak valid. Check console untuk detail.</td></tr>';
            grandTotalEl.textContent = '0';
            return;
        }
        
        if (dataArray.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="12" style="text-align: center; padding: 2rem; color: #999;">Tidak ada data pembayaran</td></tr>';
            grandTotalEl.textContent = '0';
            return;
        }

        let html = '';
        let grandTotal = 0;

        dataArray.forEach((payment, index) => {
            grandTotal += parseInt(payment.jumlah) || 0;
            html += `
                <tr class="${index === 0 ? 'selected' : ''}" onclick="selectRow(this)">
                    <td>${payment.unit || '-'}</td>
                    <td>${payment.kelas || '-'}</td>
                    <td>${payment.kelompok || '-'}</td>
                    <td>${payment.tahun_angkatan || '-'}</td>
                    <td>${payment.kode || '-'}</td>
                    <td>${payment.nama_tagihan || '-'}</td>
                    <td class="amount-cell">${formatNumber(payment.jumlah)}</td>
                    <td>${payment.namapost || '-'}</td>
                    <td>${payment.no_pend || '-'}</td>
                    <td>${payment.nis || '-'}</td>
                    <td>${payment.nama || '-'}</td>
                    <td>${payment.tanggal || '-'}</td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
        grandTotalEl.textContent = formatNumber(grandTotal);
    }

    // Format number
    function formatNumber(num) {
        return parseInt(num || 0).toLocaleString('id-ID');
    }

    // Select row
    function selectRow(row) {
        document.querySelectorAll('.payment-table tbody tr').forEach(r => {
            r.classList.remove('selected');
        });
        row.classList.add('selected');
    }

    // Toggle dropdown
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        
        // Close all other dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(d => {
            if (d.id !== id) d.classList.remove('show');
        });
        
        dropdown.classList.toggle('show');
    }

    // Close dropdowns when clicking outside
    window.addEventListener('click', function(e) {
        if (!e.target.matches('.btn-action') && !e.target.closest('.btn-action')) {
            document.querySelectorAll('.dropdown-menu').forEach(d => {
                d.classList.remove('show');
            });
        }
    });

    // Cetak Rekap function
    function cetakRekap(format) {
        const filters = getFilters();
        const queryString = new URLSearchParams(filters).toString();
        
        const url = format === 'pdf' 
            ? '{{ route("cetak.rekap.pdf") }}?' + queryString
            : '{{ route("cetak.rekap.excel") }}?' + queryString;
        
        window.open(url, '_blank');
    }

    // Cetak per NIS function - Tampilkan semua siswa
    function cetakPerNIS(format) {
        const filters = getFilters();
        
        // Hapus filter NIS untuk tampilkan semua siswa
        delete filters.nis;
        
        const queryString = new URLSearchParams(filters).toString();
        
        const url = format === 'pdf' 
            ? '{{ route("cetak.nis.pdf") }}?' + queryString
            : '{{ route("cetak.nis.excel") }}?' + queryString;
        
        window.open(url, '_blank');
    }

    // Load kelas dari API saat page load
    async function loadKelas() {
        try {
            console.log('Loading kelas data...');
            const response = await fetch('{{ route("api.get-kelas") }}');
            const result = await response.json();

            console.log('Kelas API Response:', result);

            if (result.success && result.data && Array.isArray(result.data) && result.data.length > 0) {
                const unitSelect = document.getElementById('unit');
                const kelasSelect = document.getElementById('kelas');
                const kelompokSelect = document.getElementById('kelompok');

                // Clear existing options (keep "Semua" option)
                unitSelect.innerHTML = '<option value="">Semua Unit</option>';
                kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
                kelompokSelect.innerHTML = '<option value="">Semua Kelompok</option>';

                // Get unique values - API getKelas return: {unit, kelas, kelompok}
                const uniqueUnits = [...new Set(result.data.map(item => item.unit).filter(Boolean))].sort();
                const uniqueKelas = [...new Set(result.data.map(item => item.kelas).filter(Boolean))].sort();
                const uniqueKelompok = [...new Set(result.data.map(item => item.kelompok).filter(Boolean))].sort();

                console.log('Unique Units:', uniqueUnits);
                console.log('Unique Kelas:', uniqueKelas);
                console.log('Unique Kelompok:', uniqueKelompok);

                // Populate Unit (unit = unit)
                uniqueUnits.forEach(unit => {
                    const option = document.createElement('option');
                    option.value = unit;
                    option.textContent = unit;
                    unitSelect.appendChild(option);
                });

                // Populate Kelas (kelas = kelas dari API getKelas)
                uniqueKelas.forEach(kelas => {
                    const option = document.createElement('option');
                    option.value = kelas;
                    option.textContent = kelas;
                    kelasSelect.appendChild(option);
                });

                // Populate Kelompok (kelompok = kelompok)
                uniqueKelompok.forEach(kelompok => {
                    const option = document.createElement('option');
                    option.value = kelompok;
                    option.textContent = kelompok;
                    kelompokSelect.appendChild(option);
                });

                console.log('Kelas data loaded successfully!');
            } else {
                console.warn('No kelas data received or empty');
            }
        } catch (error) {
            console.error('Error loading kelas:', error);
        }
    }

    // Load akun dari API saat page load - untuk populate Kode Rekening
    async function loadAkun() {
        try {
            console.log('Loading akun data...');
            const response = await fetch('{{ route("api.get-akun") }}');
            const result = await response.json();

            console.log('Akun API Response:', result);

            if (result.success && result.data && Array.isArray(result.data) && result.data.length > 0) {
                const kodeRekeningSelect = document.getElementById('kode_rekening');

                // Clear existing options (keep "Semua" option)
                kodeRekeningSelect.innerHTML = '<option value="">Semua Kode Rekening</option>';

                // Populate Kode Rekening - value = kode akun (101, 102, dll), text = kode
                result.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.kode || ''; // Kirim kode akun ke API sebagai 'akun' atau 'kode_akun'
                    option.textContent = item.kode || '-'; // Tampilkan kode di dropdown
                    kodeRekeningSelect.appendChild(option);
                });

                console.log('Akun data loaded successfully!');
            } else {
                console.warn('No akun data received or empty');
            }
        } catch (error) {
            console.error('Error loading akun:', error);
        }
    }

    // Load tagihan dari API saat page load - untuk populate Nama Tagihan
    async function loadTagihan() {
        try {
            console.log('Loading tagihan data...');
            const response = await fetch('{{ route("api.get-tagihan") }}');
            const result = await response.json();

            console.log('Tagihan API Response:', result);

            if (result.success && result.data && Array.isArray(result.data) && result.data.length > 0) {
                const namaTagihanSelect = document.getElementById('nama_tagihan');

                // Clear existing options (keep "Semua" option)
                namaTagihanSelect.innerHTML = '<option value="">Semua Tagihan</option>';

                // Populate Nama Tagihan - value = nama tagihan (untuk filter WS), text = nama tagihan
                result.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.nama || ''; // Kirim nama tagihan ke API sebagai 'tagihan' (WS bisa match dengan BILLNM)
                    option.textContent = item.nama || '-'; // Tampilkan nama tagihan di dropdown
                    namaTagihanSelect.appendChild(option);
                });

                console.log('Tagihan data loaded successfully!');
            } else {
                console.warn('No tagihan data received or empty');
            }
        } catch (error) {
            console.error('Error loading tagihan:', error);
        }
    }

    // Get filters helper - sesuai dengan WS getReport
    function getFilters() {
        return {
            tahun_akademik: document.getElementById('tahun_akademik').value,
            unit: document.getElementById('unit').value,
            kelas: document.getElementById('kelas').value,
            kelompok: document.getElementById('kelompok').value,
            tahun_angkatan: document.getElementById('tahun_angkatan').value,
            nis: document.getElementById('nis').value,
            dari_tanggal: document.getElementById('dari_tanggal').value,
            sampai_tanggal: document.getElementById('sampai_tanggal').value,
            kode_rekening: document.getElementById('kode_rekening').value, // kirim sebagai 'akun' atau 'kode_akun' (dari getAkun)
            nama_tagihan: document.getElementById('nama_tagihan').value, // kirim sebagai 'tagihan' atau 'kode_tagihan' (dari getTagihan)
            bank: document.getElementById('bank').value, // kirim sebagai 'bank' (FIDBANK) - hardcode kode rekening
        };
    }

    // Auto load kelas, akun, dan tagihan saat page load (setelah login/halaman rekap pembayaran)
    window.addEventListener('load', function() {
        loadKelas();
        loadAkun();
        loadTagihan();
    });
</script>
@endsection
