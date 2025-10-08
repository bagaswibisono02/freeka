@extends('layouts.sidebar')
@section('body')
  
    <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="page-title">
                <h1>Dashboard Admin</h1>
                <button class="btn btn-primary" id="add-product-btn">
                    <i class="fas fa-plus"></i> Tambah Produk
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--primary);">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3>1,254</h3>
                        <p>Total Pesanan</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--success);">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3>568</h3>
                        <p>Produk Terjual</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--warning);">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="stat-info">
                        <h3>189</h3>
                        <p>Dalam Pengiriman</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--accent);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Rp 125Jt</h3>
                        <p>Pendapatan Bulan Ini</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-section">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Statistik Penjualan</h3>
                        <select class="filter-select">
                            <option>Bulan Ini</option>
                            <option>3 Bulan Terakhir</option>
                            <option>Tahun Ini</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Kategori Terlaris</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="recent-orders">
                <div class="chart-header">
                    <h3>Pesanan Terbaru</h3>
                    <a href="#">Lihat Semua</a>
                </div>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#ORD-7842</td>
                                <td>Budi Santoso</td>
                                <td>12 Nov 2023</td>
                                <td>Rp 1.250.000</td>
                                <td><span class="status status-processing">Diproses</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn btn-primary"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn btn-success"><i class="fas fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-7841</td>
                                <td>Sari Dewi</td>
                                <td>12 Nov 2023</td>
                                <td>Rp 850.000</td>
                                <td><span class="status status-shipped">Dikirim</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn btn-primary"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn btn-warning"><i class="fas fa-truck"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-7840</td>
                                <td>Andi Pratama</td>
                                <td>11 Nov 2023</td>
                                <td>Rp 2.150.000</td>
                                <td><span class="status status-pending">Menunggu</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn btn-primary"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn btn-success"><i class="fas fa-check"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-7839</td>
                                <td>Rina Handayani</td>
                                <td>11 Nov 2023</td>
                                <td>Rp 950.000</td>
                                <td><span class="status status-delivered">Sampai</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn btn-primary"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn btn-success"><i class="fas fa-print"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-7838</td>
                                <td>Joko Widodo</td>
                                <td>10 Nov 2023</td>
                                <td>Rp 1.750.000</td>
                                <td><span class="status status-cancelled">Dibatalkan</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn btn-primary"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn btn-danger"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Products Management -->
            <div class="products-management">
                <div class="chart-header">
                    <h3>Manajemen Produk</h3>
                    <div class="filters">
                        <div class="filter-group">
                            <select class="filter-select">
                                <option>Semua Kategori</option>
                                <option>Elektronik</option>
                                <option>Fashion</option>
                                <option>Rumah Tangga</option>
                            </select>
                            <select class="filter-select">
                                <option>Status Stok</option>
                                <option>Tersedia</option>
                                <option>Habis</option>
                                <option>Pre-Order</option>
                            </select>
                        </div>
                        <button class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=50&q=80" alt="Headphone" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        <div>
                                            <div>Headphone Wireless Premium</div>
                                            <div style="font-size: 0.8rem; color: var(--text-light);">SKU: HP-001</div>
                                        </div>
                                    </div>
                                </td>
                                <td>Elektronik</td>
                                <td>Rp 1.299.000</td>
                                <td>45</td>
                                <td><span class="status status-delivered">Aktif</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn btn-primary"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn btn-success"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn btn-danger"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <!-- More product rows would go here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <!-- Add Product Modal -->
    <div class="modal" id="add-product-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Produk Baru</h3>
                <button class="close-modal">&times;</button>
            </div>
            
            <form id="add-product-form">
                <div class="form-group">
                    <label for="product-name">Nama Produk</label>
                    <input type="text" id="product-name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="product-category">Kategori</label>
                    <select id="product-category" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <option value="elektronik">Elektronik</option>
                        <option value="fashion">Fashion</option>
                        <option value="rumah-tangga">Rumah Tangga</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="product-price">Harga</label>
                    <input type="number" id="product-price" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="product-stock">Stok</label>
                    <input type="number" id="product-stock" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="product-description">Deskripsi</label>
                    <textarea id="product-description" class="form-control" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="product-image">Gambar Produk</label>
                    <input type="file" id="product-image" class="form-control">
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-danger close-modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    <script>
 

        // Modal Functionality
        const addProductBtn = document.getElementById('add-product-btn');
        const addProductModal = document.getElementById('add-product-modal');
        const closeModalBtns = document.querySelectorAll('.close-modal');

        addProductBtn.addEventListener('click', function() {
            addProductModal.style.display = 'flex';
        });

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                addProductModal.style.display = 'none';
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === addProductModal) {
                addProductModal.style.display = 'none';
            }
        });

        // Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Penjualan (Juta Rupiah)',
                        data: [65, 59, 80, 81, 56, 55, 40, 50, 60, 70, 90, 95],
                        borderColor: '#8B5FBF',
                        backgroundColor: 'rgba(139, 95, 191, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryChart = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Elektronik', 'Fashion', 'Rumah Tangga', 'Kesehatan', 'Lainnya'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            '#8B5FBF',
                            '#FF5722',
                            '#4CAF50',
                            '#FF9800',
                            '#E91E63'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });

        // Form Submission
        document.getElementById('add-product-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // In a real application, you would send the form data to the server here
            alert('Produk berhasil ditambahkan!');
            document.getElementById('add-product-modal').style.display = 'none';
            this.reset();
        });
    </script>
@endsection