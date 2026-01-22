<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dashboard</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        /* LAYOUT CONTAINER */
        .dashboard-layout {
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }
        
        /* SIDEBAR - FIXED */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #1e293b;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        /* HAMBURGER MENU */
        .hamburger-btn {
            display: none;
            position: fixed;
            top: 1.5rem;
            left: 1rem;
            z-index: 1001;
            width: 40px;
            height: 40px;
            background: #1e293b;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .hamburger-btn span {
            width: 20px;
            height: 2px;
            background: white;
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
            backdrop-filter: blur(2px);
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
        }
        
        .sidebar-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .sidebar-text h5 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .sidebar-text small {
            font-size: 0.8rem;
            opacity: 0.75;
        }
        
        .sidebar-menu {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            font-size: 0.95rem;
        }
        
        .menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border-left-color: #3b82f6;
        }
        
        .menu-item.active {
            background: rgba(59, 130, 246, 0.1);
            color: white;
            border-left-color: #3b82f6;
            font-weight: 500;
        }
        
        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            color: white;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            background: #1e293b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .user-details {
            flex: 1;
            min-width: 0;
        }
        
        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-role {
            font-size: 0.75rem;
            opacity: 0.75;
        }
        
        .btn-logout {
            width: 100%;
            padding: 0.625rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        /* MAIN CONTENT AREA */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            min-width: 0;
        }
        
        /* TOPBAR */
        .topbar {
            background: white;
            padding: 1.25rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            flex-shrink: 0;
        }
        
        .topbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .topbar-title h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }
        
        .topbar-title p {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }
        
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        /* CONTENT AREA - SCROLLABLE */
        .content-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 2rem;
        }
        
        /* NO CUSTOM SCROLLBAR - USE DEFAULT */
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hamburger-btn {
                display: flex;
            }
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                z-index: 1000;
                transform: translateX(-100%);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .sidebar-overlay.active {
                display: block;
                animation: fadeIn 0.3s;
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            .topbar {
                padding: 1rem 1.5rem 1rem 4.5rem;
            }
            .content-area {
                padding: 1rem;
            }
            .main-content {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Hamburger Button -->
    <button class="hamburger-btn" id="hamburgerBtn">
        <span></span>
        <span></span>
        <span></span>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar" style="z-index:99999999999;">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-icon">
                        <img src="{{ asset('batam.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; border-radius: 8px;">
                    </div>
                    <div class="sidebar-text">
                        <h5>Dashboard</h5>
                        <small>Batam Hidayatullah</small>
                    </div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('dashboard.rekap-pembayaran') }}" class="menu-item active">
                    <i class="bi bi-receipt"></i>
                    <span>Rekap Pembayaran</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="topbar">
                <div class="topbar-content">
                    <div class="topbar-title">
                        <h4>@yield('page-title', 'Dashboard')</h4>
                        <p>@yield('page-subtitle', 'Kelola data pembayaran siswa')</p>
                    </div>
                    <div class="topbar-user">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <div class="user-name" style="color: #1e293b;">{{ Auth::user()->name }}</div>
                            <div class="user-role" style="color: #6b7280;">Administrator</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Hamburger menu toggle
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.querySelector('.sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        hamburgerBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            hamburgerBtn.classList.remove('active');
            sidebar.classList.remove('active');
            this.classList.remove('active');
        });
    </script>
</body>
</html>
