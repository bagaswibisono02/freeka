<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PurpleZone</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #8B5FBF;
            --primary-dark: #6B3FA0;
            --primary-light: #B39DDB;
            --secondary: #FF5722;
            --accent: #E91E63;
            --success: #4CAF50;
            --warning: #FF9800;
            --danger: #F44336;
            --text: #333333;
            --text-light: #666666;
            --bg-light: #F8F9FA;
            --white: #FFFFFF;
            --border: #E0E0E0;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text);
            overflow-x: hidden;
            display: flex;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: var(--primary-dark);
            color: var(--white);
            height: 100vh;
            position: fixed;
            transition: var(--transition);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: var(--transition);
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .menu-item:hover,
        .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid var(--secondary);
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.1);
        }

        .submenu.active {
            max-height: 300px;
        }

        .submenu-item {
            padding: 10px 20px 10px 50px;
            transition: var(--transition);
        }

        .submenu-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            transition: var(--transition);
        }

        /* Top Header */
        .top-header {
            background: var(--white);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .toggle-sidebar {
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--primary);
        }

        .search-admin {
            display: flex;
            align-items: center;
            background: var(--bg-light);
            border-radius: 4px;
            padding: 8px 15px;
            width: 300px;
        }

        .search-admin input {
            border: none;
            background: transparent;
            outline: none;
            margin-left: 10px;
            width: 100%;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: bold;
        }

        .notification {
            position: relative;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: var(--white);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 30px;
        }

        .page-title {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title h1 {
            font-size: 1.8rem;
            color: var(--primary);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            background: var(--success);
            color: var(--white);
        }

        .btn-warning {
            background: var(--warning);
            color: var(--white);
        }

        .btn-danger {
            background: var(--danger);
            color: var(--white);
        }

        /* Stats Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
        }

        .stat-info h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-header h3 {
            font-size: 1.2rem;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        /* Recent Orders */
        .recent-orders {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background: var(--bg-light);
            font-weight: 600;
        }

        tr:hover {
            background: var(--bg-light);
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-pending {
            background: #FFF3CD;
            color: #856404;
        }

        .status-processing {
            background: #CCE5FF;
            color: #004085;
        }

        .status-shipped {
            background: #D4EDDA;
            color: #155724;
        }

        .status-delivered {
            background: #D1ECF1;
            color: #0C5460;
        }

        .status-cancelled {
            background: #F8D7DA;
            color: #721C24;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: var(--transition);
        }

        /* Products Management */
        .products-management {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            gap: 10px;
        }

        .filter-select {
            padding: 8px 15px;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: var(--white);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--white);
            border-radius: 8px;
            width: 500px;
            max-width: 90%;
            padding: 30px;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 4px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .charts-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }

            .sidebar-header h2,
            .menu-item span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .stats-cards {
                grid-template-columns: 1fr;
            }

            .search-admin {
                width: 200px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .toggle-sidebar.active~.main-content {
                margin-left: 250px;
            }

            .top-header {
                padding: 15px;
            }

            .search-admin {
                display: none;
            }

            .admin-name {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-store"></i>
            <h2>PurpleZone Admin</h2>
        </div>

        <div class="sidebar-menu">
            <div class="menu-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </div>

            <div class="menu-item" id="products-menu">
                <i class="fas fa-box"></i>
                <span>Manajemen Produk</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </div>
            <div class="submenu" id="products-submenu">
                <ul>
                    <div class="submenu-item"><a href="/admin/produk">Semua Produk</a></div>
                    <div class="submenu-item"><a href="/admin/produk/create">Tambah Produk</a></div>
                    <div class="submenu-item"><a href="/admin/categories">Kategori</a></div>
                    <div class="submenu-item"><a href="/admin/produk/stok">Stok</a></div>
                </ul>
            </div>


            <div class="menu-item" id="orders-menu">
                <i class="fas fa-shopping-cart"></i>
                <span>Pesanan</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </div>
            <div class="submenu" id="orders-submenu">
                <div class="submenu-item">Semua Pesanan</div>
                <div class="submenu-item">Pesanan Baru</div>
                <div class="submenu-item">Pesanan Diproses</div>
                <div class="submenu-item">Riwayat Pesanan</div>
            </div>

            <div class="menu-item" id="shipping-menu">
                <i class="fas fa-shipping-fast"></i>
                <span>Pengiriman & Resi</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </div>
            <div class="submenu" id="shipping-submenu">
                <div class="submenu-item">Semua Pengiriman</div>
                <div class="submenu-item">Input Resi</div>
                <div class="submenu-item">Lacak Pengiriman</div>
                <div class="submenu-item">Laporan Pengiriman</div>
            </div>

            <div class="menu-item">
                <i class="fas fa-users"></i>
                <span>Pelanggan</span>
            </div>

            <div class="menu-item">
                <i class="fas fa-chart-bar"></i>
                <span>Laporan</span>
            </div>

            <div class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </div>

            <div class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </div>
        </div>
    </aside>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="toggle-sidebar">
                <i class="fas fa-bars"></i>
            </div>

            <div class="search-admin">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari...">
            </div>

            <div class="admin-profile">
                <div class="notification">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>
                </div>
                <div class="admin-avatar">AD</div>
                <div class="admin-name">Admin Dashboard</div>
            </div>
        </header>


        @yield('body')
    </div>
    <script>
        // Toggle Sidebar
        document.querySelector('.toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
            this.classList.toggle('active');
        });

        // Toggle Submenus
        document.querySelectorAll('.menu-item').forEach(item => {
            if (item.querySelector('.fa-chevron-down')) {
                item.addEventListener('click', function() {
                    const submenuId = this.id.replace('-menu', '-submenu');
                    document.getElementById(submenuId).classList.toggle('active');
                    this.querySelector('.fa-chevron-down').classList.toggle('fa-chevron-up');
                });
            }
        });
    </script>

</body>

</html>
