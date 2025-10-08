@extends('layouts.sidebar')
@section('body')
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
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Section */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-title h1 {
            font-size: 1.8rem;
            color: var(--primary);
        }

        .page-title i {
            font-size: 2rem;
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

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        /* Filters Section */
        .filters-section {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .filters-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 200px;
        }

        .filter-group label {
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .filter-select, .filter-input {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: var(--white);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        /* Products Grid/List View */
        .view-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
        }

        .view-btn {
            padding: 8px 12px;
            border: 1px solid var(--border);
            background: var(--white);
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
        }

        .view-btn.active {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        .search-box {
            display: flex;
            align-items: center;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 8px 12px;
            width: 300px;
        }

        .search-box input {
            border: none;
            outline: none;
            margin-left: 8px;
            width: 100%;
        }

        /* Products Grid View */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .product-card {
            background: var(--white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid var(--border);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 200px;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .product-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-new {
            background: var(--success);
            color: var(--white);
        }

        .badge-low-stock {
            background: var(--warning);
            color: var(--white);
        }

        .badge-out-of-stock {
            background: var(--danger);
            color: var(--white);
        }

        .product-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            opacity: 0;
            transition: var(--transition);
        }

        .product-card:hover .product-actions {
            opacity: 1;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-edit {
            background: var(--success);
            color: var(--white);
        }

        .btn-delete {
            background: var(--danger);
            color: var(--white);
        }

        .btn-view {
            background: var(--primary);
            color: var(--white);
        }

        .product-info {
            padding: 15px;
        }

        .product-name {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.5rem;
        }

        .product-sku {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 10px;
        }

        .product-stock {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stock-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .stock-high {
            background: var(--success);
        }

        .stock-medium {
            background: var(--warning);
        }

        .stock-low {
            background: var(--danger);
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid var(--border);
        }

        .product-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background: #E8F5E9;
            color: var(--success);
        }

        .status-inactive {
            background: #FFEBEE;
            color: var(--danger);
        }

        /* Products Table View */
        .products-table {
            display: none;
            background: var(--white);
            border-radius: 8px;
            overflow: hidden;
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

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background: var(--bg-light);
            font-weight: 600;
            color: var(--primary);
        }

        tr:hover {
            background: var(--bg-light);
        }

        .product-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-cell img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .table-actions {
            display: flex;
            gap: 5px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
        }

        .pagination-btn {
            padding: 8px 12px;
            border: 1px solid var(--border);
            background: var(--white);
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
        }

        .pagination-btn.active {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        .pagination-btn:hover:not(.active) {
            background: var(--bg-light);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: var(--white);
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--primary-light);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--text-light);
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
            max-height: 90vh;
            overflow-y: auto;
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
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filters-row {
                flex-direction: column;
            }
            
            .filter-group {
                min-width: 100%;
            }
            
            .search-box {
                width: 100%;
                margin-top: 10px;
            }
            
            .view-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 10px;
            }
        }

</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-boxes"></i>
            <div>
                <h1>Semua Produk</h1>
                <p>Kelola semua produk di toko Anda</p>
            </div>
        </div>
        <a href="/admin/produk/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <form id="filter-form" method="GET" action="/admin/produk">
            <div class="filters-row">
                <div class="filter-group">
                    <label for="category-filter">Kategori</label>
                    <select id="category-filter" name="category" class="filter-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="status-filter">Status</label>
                    <select id="status-filter" name="status" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="stock-filter">Stok</label>
                    <select id="stock-filter" name="stock" class="filter-select">
                        <option value="">Semua Stok</option>
                        <option value="high" {{ request('stock') == 'high' ? 'selected' : '' }}>Stok Tinggi (>50)</option>
                        <option value="medium" {{ request('stock') == 'medium' ? 'selected' : '' }}>Stok Sedang (10-50)</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stok Rendah (<10)</option>
                        <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sort-by">Urutkan</label>
                    <select id="sort-by" name="sort" class="filter-select">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
                <a href="/admin/produk" class="btn" id="reset-filters">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- View Options -->
    <div class="view-options">
        <div class="view-toggle">
            <button class="view-btn active" id="grid-view-btn">
                <i class="fas fa-th"></i> Grid
            </button>
            <button class="view-btn" id="list-view-btn">
                <i class="fas fa-list"></i> List
            </button>
        </div>
        
        <div class="search-box">
            <i class="fas fa-search"></i>
            <form method="GET" action="/admin/produk" style="display: flex; width: 100%;">
                <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
                <button type="submit" style="display: none;"></button>
            </form>
        </div>
    </div>

    <!-- Products Grid View -->
    <div class="products-grid" id="products-grid">
        @forelse($products as $product)
        <div class="product-card">
            <div class="product-image">
                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}">
                <div class="product-badges">
                    @if($product->stock_quantity == 0)
                        <span class="product-badge badge-out-of-stock">Habis</span>
                    @elseif($product->stock_quantity < 10)
                        <span class="product-badge badge-low-stock">Stok Menipis</span>
                    @elseif($product->created_at->diffInDays(now()) < 7)
                        <span class="product-badge badge-new">Baru</span>
                    @endif
                    @if($product->has_discount)
                        <span class="product-badge" style="background: var(--accent);">Diskon {{ $product->discount_percentage }}%</span>
                    @endif
                </div>
                <div class="product-actions">
                    <button class="action-btn btn-view" title="Lihat Detail" onclick="viewProduct({{ $product->id }})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <a href="/admin/produk/{{ $product->id }}/edit" class="action-btn btn-edit" title="Edit Produk">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="action-btn btn-delete" title="Hapus Produk" onclick="deleteProduct({{ $product->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="product-info">
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-sku">SKU: {{ $product->sku ?? 'N/A' }}</div>
                <div class="product-price">
                    @if($product->has_discount)
                        <span style="text-decoration: line-through; color: var(--text-light); font-size: 0.9rem;">
                            Rp {{ number_format($product->base_price, 0, ',', '.') }}
                        </span>
                        <br>
                        <span style="color: var(--accent);">
                            Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                        </span>
                    @else
                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                    @endif
                </div>
                <div class="product-meta">
                    <div class="product-stock">
                        @php
                            $stockClass = 'stock-high';
                            if ($product->stock_quantity == 0) $stockClass = 'stock-low';
                            elseif ($product->stock_quantity < 10) $stockClass = 'stock-low';
                            elseif ($product->stock_quantity <= 50) $stockClass = 'stock-medium';
                        @endphp
                        <span class="stock-indicator {{ $stockClass }}"></span>
                        <span>Stok: {{ $product->stock_quantity }}</span>
                    </div>
                    <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                </div>
                <div class="product-footer">
                    <span class="product-status {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <div class="product-sales">
                        @php
                            $totalSold = $product->variants->sum('stock_quantity') > 0 ? 
                                rand(10, 100) : rand(50, 500); // Contoh data
                        @endphp
                        Terjual: {{ $totalSold }}
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state" style="display: block; grid-column: 1 / -1;">
            <i class="fas fa-box-open"></i>
            <h3>Tidak ada produk ditemukan</h3>
            <p>Coba ubah filter pencarian atau tambah produk baru</p>
            <a href="/admin/produk/create" class="btn btn-primary" style="margin-top: 15px;">
                <i class="fas fa-plus"></i> Tambah Produk Pertama
            </a>
        </div>
        @endforelse
    </div>

    <!-- Products Table View -->
    <div class="products-table" id="products-table">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Terjual</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="product-cell">
                                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}">
                                <div>
                                    <div>{{ $product->name }}</div>
                                    <div style="font-size: 0.8rem; color: var(--text-light);">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                        <td>
                            @if($product->has_discount)
                                <div>
                                    <span style="text-decoration: line-through; color: var(--text-light); font-size: 0.8rem;">
                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </span>
                                    <br>
                                    <span style="color: var(--accent); font-weight: bold;">
                                        Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @else
                                Rp {{ number_format($product->base_price, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            @php
                                $stockClass = 'stock-high';
                                if ($product->stock_quantity == 0) $stockClass = 'stock-low';
                                elseif ($product->stock_quantity < 10) $stockClass = 'stock-low';
                                elseif ($product->stock_quantity <= 50) $stockClass = 'stock-medium';
                            @endphp
                            <div class="product-stock">
                                <span class="stock-indicator {{ $stockClass }}"></span>
                                <span>{{ $product->stock_quantity }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $totalSold = $product->variants->sum('stock_quantity') > 0 ? 
                                    rand(10, 100) : rand(50, 500);
                            @endphp
                            {{ $totalSold }}
                        </td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-primary btn-sm" onclick="viewProduct({{ $product->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{'/admin/produk/'. $product->id.'/edit' }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="deleteProduct({{ $product->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="pagination">
        {{ $products->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

<script>
    // Toggle between Grid and List view
    const gridViewBtn = document.getElementById('grid-view-btn');
    const listViewBtn = document.getElementById('list-view-btn');
    const productsGrid = document.getElementById('products-grid');
    const productsTable = document.getElementById('products-table');

    gridViewBtn.addEventListener('click', function() {
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        productsGrid.style.display = 'grid';
        productsTable.style.display = 'none';
    });

    listViewBtn.addEventListener('click', function() {
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
        productsGrid.style.display = 'none';
        productsTable.style.display = 'block';
    });

    // Product actions
    function viewProduct(productId) {
        alert('View product: ' + productId);
        // window.location.href = '/admin/products/' + productId;
    }

    function deleteProduct(productId) {
        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            // Ajax delete request
            fetch('/admin/produk/' + productId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus produk');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus produk');
            });
        }
    }

    // Auto submit filter form on change
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });
</script>
@endsection