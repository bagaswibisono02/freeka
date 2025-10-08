@extends('layouts.sidebar')
@section('body')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: "{{ session('error') }}",
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#d33'
            });
        });
    </script>
@endif
@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `
                    <ul style="text-align:left; padding-left:20px; list-style-type:disc;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#d33'
            });
        });
    </script>
@endif

<style>
    /* CSS sama persis dengan halaman create */
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

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        color: var(--text-light);
        margin-top: 5px;
    }

    .breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
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
        text-decoration: none;
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

    .btn-outline {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text);
    }

    .btn-outline:hover {
        background: var(--bg-light);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    /* Form Container */
    .form-container {
        background: var(--white);
        border-radius: 8px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .form-tabs {
        display: flex;
        border-bottom: 1px solid var(--border);
        background: var(--bg-light);
        flex-wrap: wrap;
    }

    .form-tab {
        padding: 15px 20px;
        cursor: pointer;
        transition: var(--transition);
        border-bottom: 3px solid transparent;
        font-weight: 500;
    }

    .form-tab.active {
        border-bottom: 3px solid var(--primary);
        color: var(--primary);
        background: var(--white);
    }

    .form-content {
        padding: 30px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Form Styles */
    .form-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 1.2rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        font-size: 1.1rem;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
        min-width: 250px;
    }

    .form-group.full-width {
        flex: 100%;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-group .required::after {
        content: " *";
        color: var(--danger);
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 4px;
        font-size: 1rem;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(139, 95, 191, 0.1);
    }

    .form-control.error {
        border-color: var(--danger);
    }

    .form-help {
        font-size: 0.85rem;
        color: var(--text-light);
        margin-top: 5px;
    }

    .form-error {
        font-size: 0.85rem;
        color: var(--danger);
        margin-top: 5px;
        display: none;
    }

    .form-control.error + .form-error {
        display: block;
    }

    /* Checkbox Styles */
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }

    /* Custom Variants Section */
    .variants-section {
        margin-bottom: 30px;
    }

    .variant-item {
        background: var(--bg-light);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid var(--primary);
        box-shadow: var(--shadow);
    }

    .variant-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }

    .variant-actions {
        display: flex;
        gap: 10px;
    }

    .variant-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .file-upload-section {
        margin-top: 15px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 6px;
        border: 1px dashed var(--border);
    }

    .file-preview {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .file-preview-item {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .file-preview-item img,
    .file-preview-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .file-preview-item .file-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 4px;
        font-size: 0.7rem;
        text-align: center;
    }

    /* Buy Links Section */
    .buy-links-section {
        margin-bottom: 30px;
    }

    .buy-link-item {
        background: var(--bg-light);
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 3px solid var(--success);
    }

    .buy-link-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .buy-link-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 15px;
    }

    /* Image Upload */
    .image-upload-section {
        margin-bottom: 30px;
    }

    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 15px;
    }

    .image-preview {
        width: 120px;
        height: 120px;
        border: 2px dashed var(--border);
        border-radius: 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .image-preview:hover {
        border-color: var(--primary);
    }

    .image-preview img,
    .image-preview video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    .image-preview.has-file {
        border-style: solid;
    }

    .image-preview.has-file img,
    .image-preview.has-file video {
        display: block;
    }

    .image-preview.has-file .upload-text {
        display: none;
    }

    .upload-text {
        text-align: center;
        color: var(--text-light);
    }

    .upload-text i {
        font-size: 2rem;
        margin-bottom: 10px;
        color: var(--primary-light);
    }

    .remove-file {
        position: absolute;
        top: 5px;
        right: 5px;
        background: var(--danger);
        color: var(--white);
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: var(--transition);
    }

    .image-preview:hover .remove-file {
        opacity: 1;
    }

    /* Existing Media */
    .existing-media-item {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .existing-media-item img,
    .existing-media-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .media-checkbox {
        position: absolute;
        top: 5px;
        left: 5px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 4px;
        padding: 2px;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        margin-top: 30px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 15px;
        }
        
        .form-group {
            min-width: 100%;
        }
        
        .form-tabs {
            flex-direction: column;
        }
        
        .form-tab {
            border-bottom: 1px solid var(--border);
            border-left: 3px solid transparent;
        }
        
        .form-tab.active {
            border-left: 3px solid var(--primary);
            border-bottom: 1px solid var(--border);
        }
        
        .form-actions {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: space-between;
        }
        
        .variant-content,
        .buy-link-content {
            grid-template-columns: 1fr;
        }
    }

    /* Loading State */
    .loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid var(--primary);
        border-radius: 50%;
        border-right-color: transparent;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <div class="page-title">
                <i class="fas fa-edit"></i>
                <h1>Edit Produk</h1>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                <i class="fas fa-chevron-right"></i>
                <a href="/admin/produk">Produk</a>
                <i class="fas fa-chevron-right"></i>
                <span>Edit {{ $product->name }}</span>
            </div>
        </div>
        <a href="/admin/produk" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Produk
        </a>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <!-- Form Tabs -->
        <div class="form-tabs">
            <div class="form-tab active" data-tab="basic-info">Informasi Dasar</div>
            <div class="form-tab" data-tab="custom-variants">Varian Custom</div>
            <div class="form-tab" data-tab="buy-links">Link Beli</div>
            <div class="form-tab" data-tab="images">Media</div>
            <div class="form-tab" data-tab="seo">SEO</div>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <form id="edit-product-form" action="{{ '/admin/produk/'.$product->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Hidden fields for deleted items -->
                <input type="hidden" name="deleted_variants" id="deleted-variants" value="">
                <input type="hidden" name="deleted_buy_links" id="deleted-buy-links" value="">
                <input type="hidden" name="deleted_media" id="deleted-media" value="">
                
                <!-- Basic Information Tab -->
                <div class="tab-content active" id="basic-info">
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i>
                            <h3>Informasi Produk</h3>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="product-name" class="required">Nama Produk</label>
                                <input type="text" id="product-name" name="name" class="form-control" placeholder="Masukkan nama produk" required value="{{ old('name', $product->name) }}">
                                <div class="form-error">Nama produk wajib diisi</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="product-category" class="required">Kategori</label>
                                <select id="product-category" name="category_id" class="form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-error">Kategori wajib dipilih</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="product-brand">Merek</label>
                                <select id="product-brand" name="brand_id" class="form-control">
                                    <option value="">Pilih Merek</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="base_price" class="required">Harga Dasar (Rp)</label>
                                <input type="number" id="base_price" name="base_price" class="form-control" placeholder="0" min="0" required value="{{ old('base_price', $product->base_price) }}">
                                <div class="form-error">Harga dasar wajib diisi</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="sale_price">Harga Diskon (Rp)</label>
                                <input type="number" id="sale_price" name="sale_price" class="form-control" placeholder="0" min="0" value="{{ old('sale_price', $product->sale_price) }}">
                                <div class="form-help">Kosongkan jika tidak ada diskon</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="cost_price">Harga Pokok (Rp)</label>
                                <input type="number" id="cost_price" name="cost_price" class="form-control" placeholder="0" min="0" value="{{ old('cost_price', $product->cost_price) }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="stock_quantity" class="required">Stok Dasar</label>
                                <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" placeholder="0" min="0" required value="{{ old('stock_quantity', $product->stock_quantity) }}">
                                <div class="form-error">Stok wajib diisi</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="sku">SKU Produk</label>
                                <input type="text" id="sku" name="sku" class="form-control" placeholder="Kode unik produk" value="{{ old('sku', $product->sku) }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="low_stock_threshold">Peringatan Stok Rendah</label>
                                <input type="number" id="low_stock_threshold" name="low_stock_threshold" class="form-control" placeholder="10" min="0" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="weight">Berat (gram)</label>
                                <input type="number" id="weight" name="weight" class="form-control" placeholder="0" min="0" value="{{ old('weight', $product->weight) }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="dimensions">Dimensi (cm)</label>
                                <input type="text" id="dimensions" name="dimensions" class="form-control" placeholder="P x L x T" value="{{ old('dimensions', $product->dimensions) }}">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="product-description" class="required">Deskripsi Produk</label>
                                <textarea id="product-description" name="description" class="form-control" rows="6" placeholder="Deskripsikan produk secara detail" required>{{ old('description', $product->description) }}</textarea>
                                <div class="form-error">Deskripsi produk wajib diisi</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="checkbox-group">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label for="is_active">Produk Aktif</label>
                            </div>
                            
                            <div class="checkbox-group">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured">Produk Unggulan</label>
                            </div>
                            
                            <div class="checkbox-group">
                                <input type="checkbox" id="is_draft" name="is_draft" value="1" {{ old('is_draft', $product->is_draft) ? 'checked' : '' }}>
                                <label for="is_draft">Simpan sebagai Draft</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Variants Tab -->
                <div class="tab-content" id="custom-variants">
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-layer-group"></i>
                            <h3>Varian Custom Produk</h3>
                        </div>
                        <div class="form-help">
                            Kelola varian custom untuk produk ini. Setiap varian bisa memiliki file/video, harga, dan stok yang berbeda.
                        </div>
                        
                        <div id="variants-container">
                            @php
                                $oldVariants = old('variants', $product->variants->toArray());
                            @endphp
                            @foreach($oldVariants as $index => $variant)
                            <div class="variant-item" id="variant-{{ $variant['id'] ?? $index }}">
                                <div class="variant-header">
                                    <h4>Varian {{ $loop->iteration }}</h4>
                                    <div class="variant-actions">
                                        @if(isset($variant['id']))
                                        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] }}">
                                        @endif
                                        <button type="button" class="btn btn-danger btn-sm remove-variant" data-variant-id="{{ $variant['id'] ?? '' }}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                                <div class="variant-content">
                                    <div class="form-group">
                                        <label>Nama Varian</label>
                                        <input type="text" name="variants[{{ $index }}][name]" class="form-control" placeholder="Contoh: Premium Edition" required value="{{ $variant['name'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tipe Varian</label>
                                        <select name="variants[{{ $index }}][type]" class="form-control variant-type" required>
                                            <option value="">Pilih Tipe</option>
                                            <option value="color" {{ ($variant['type'] ?? '') == 'color' ? 'selected' : '' }}>Warna</option>
                                            <option value="size" {{ ($variant['type'] ?? '') == 'size' ? 'selected' : '' }}>Ukuran</option>
                                            <option value="material" {{ ($variant['type'] ?? '') == 'material' ? 'selected' : '' }}>Material</option>
                                            <option value="edition" {{ ($variant['type'] ?? '') == 'edition' ? 'selected' : '' }}>Edition</option>
                                            <option value="custom" {{ ($variant['type'] ?? '') == 'custom' ? 'selected' : '' }}>Custom</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nilai Varian</label>
                                        <input type="text" name="variants[{{ $index }}][value]" class="form-control" placeholder="Contoh: Merah, XL, dll" required value="{{ $variant['value'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>SKU Varian</label>
                                        <input type="text" name="variants[{{ $index }}][sku]" class="form-control" placeholder="Kode unik varian" value="{{ $variant['sku'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Harga (Rp)</label>
                                        <input type="number" name="variants[{{ $index }}][price]" class="form-control" placeholder="0" min="0" required value="{{ $variant['price'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Stok</label>
                                        <input type="number" name="variants[{{ $index }}][stock]" class="form-control" placeholder="0" min="0" required value="{{ $variant['stock_quantity'] ?? $variant['stock'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="file-upload-section">
                                    <label>File/Media Varian (Opsional)</label>
                                    <div class="form-help">
                                        @if(isset($variant['image']))
                                            <div class="file-preview">
                                                <div class="file-preview-item">
                                                    <img src="{{ Storage::url($variant['image']) }}" alt="Variant Image">
                                                    <div class="file-info">Existing Image</div>
                                                </div>
                                            </div>
                                        @endif
                                        Upload gambar atau video baru untuk mengganti yang lama
                                    </div>
                                    <input type="file" name="variants[{{ $index }}][file]" class="form-control variant-file" accept="image/*,video/*" style="margin-bottom: 10px;">
                                    <div class="file-preview" id="file-preview-{{ $index }}"></div>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="variant_active_{{ $index }}" name="variants[{{ $index }}][is_active]" value="1" {{ ($variant['is_active'] ?? true) ? 'checked' : '' }}>
                                    <label for="variant_active_{{ $index }}">Varian Aktif</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <button type="button" id="add-variant" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Varian
                        </button>
                    </div>
                </div>

                <!-- Buy Links Tab -->
                <div class="tab-content" id="buy-links">
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-shopping-cart"></i>
                            <h3>Link Beli Produk</h3>
                        </div>
                        <div class="form-help">
                            Kelola multiple link beli untuk produk ini (Shopee, Tokopedia, Lazada, dll)
                        </div>
                        
                        <div id="buy-links-container">
                            @php
                                $oldBuyLinks = old('buy_links', $product->buyLinks->toArray());
                            @endphp
                            @foreach($oldBuyLinks as $index => $link)
                            <div class="buy-link-item">
                                <div class="buy-link-header">
                                    <h5>Link Beli {{ $loop->iteration }}</h5>
                                    <button type="button" class="btn btn-danger btn-sm remove-buy-link" data-link-id="{{ $link['id'] ?? '' }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="buy-link-content">
                                    @if(isset($link['id']))
                                    <input type="hidden" name="buy_links[{{ $index }}][id]" value="{{ $link['id'] }}">
                                    @endif
                                    <div class="form-group">
                                        <label>Platform</label>
                                        <select name="buy_links[{{ $index }}][platform]" class="form-control" required>
                                            <option value="">Pilih Platform</option>
                                            <option value="shopee" {{ ($link['platform'] ?? '') == 'shopee' ? 'selected' : '' }}>Shopee</option>
                                            <option value="tokopedia" {{ ($link['platform'] ?? '') == 'tokopedia' ? 'selected' : '' }}>Tokopedia</option>
                                            <option value="lazada" {{ ($link['platform'] ?? '') == 'lazada' ? 'selected' : '' }}>Lazada</option>
                                            <option value="blibli" {{ ($link['platform'] ?? '') == 'blibli' ? 'selected' : '' }}>Blibli</option>
                                            <option value="bukalapak" {{ ($link['platform'] ?? '') == 'bukalapak' ? 'selected' : '' }}>Bukalapak</option>
                                            <option value="website" {{ ($link['platform'] ?? '') == 'website' ? 'selected' : '' }}>Website</option>
                                            <option value="other" {{ ($link['platform'] ?? '') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Platform (jika Lainnya)</label>
                                        <input type="text" name="buy_links[{{ $index }}][custom_platform]" class="form-control" placeholder="Nama platform" value="{{ $link['custom_platform'] ?? '' }}">
                                    </div>
                                    <div class="form-group full-width">
                                        <label>URL Link Beli</label>
                                        <input type="url" name="buy_links[{{ $index }}][url]" class="form-control" placeholder="https://..." required value="{{ $link['url'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Harga di Platform ini (Rp)</label>
                                        <input type="number" name="buy_links[{{ $index }}][price]" class="form-control" placeholder="0" min="0" value="{{ $link['price'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="link_active_{{ $index }}" name="buy_links[{{ $index }}][is_active]" value="1" {{ ($link['is_active'] ?? true) ? 'checked' : '' }}>
                                    <label for="link_active_{{ $index }}">Link Aktif</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <button type="button" id="add-buy-link" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Link Beli
                        </button>
                    </div>
                </div>

                <!-- Images Tab -->
                <div class="tab-content" id="images">
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-images"></i>
                            <h3>Media Produk</h3>
                        </div>
                        
                        <div class="image-upload-section">
                            <label>Gambar Utama</label>
                            <div class="form-help">
                                @if($product->main_image)
                                <div class="image-preview-container">
                                    <div class="existing-media-item">
                                        <img src="{{ Storage::url($product->main_image) }}" alt="Main Image">
                                    </div>
                                </div>
                                @endif
                                Upload gambar baru untuk mengganti gambar utama
                            </div>
                            
                            <div class="image-preview-container">
                                <div class="image-preview" id="main-image-preview">
                                    <div class="upload-text">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Klik untuk upload</span>
                                    </div>
                                    <img src="" alt="Preview">
                                    <input type="file" id="main-image" name="main_image" accept="image/*,video/*" style="display: none;">
                                    <button type="button" class="remove-file">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="image-upload-section">
                            <label>Media Tambahan (Maksimal 5)</label>
                            <div class="form-help">
                                @if($product->media->count() > 0)
                                <div class="image-preview-container">
                                    @foreach($product->media as $media)
                                    <div class="existing-media-item">
                                        @if($media->file_type == 'image')
                                            <img src="{{ Storage::url($media->file_path) }}" alt="Additional Media">
                                        @else
                                            <video controls>
                                                <source src="{{ Storage::url($media->file_path) }}" type="{{ $media->mime_type }}">
                                            </video>
                                        @endif
                                        <div class="media-checkbox">
                                            <input type="checkbox" class="media-delete-checkbox" value="{{ $media->id }}">
                                            <label>Hapus</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                Gambar atau video tambahan untuk menunjukkan produk
                            </div>
                            
                            <div class="image-preview-container" id="additional-media-container">
                                <div class="image-preview additional-media">
                                    <div class="upload-text">
                                        <i class="fas fa-plus"></i>
                                        <span>Tambah Media</span>
                                    </div>
                                    <img src="" alt="Preview">
                                    <video controls style="display: none;"></video>
                                    <input type="file" class="additional-media-input" accept="image/*,video/*" style="display: none;">
                                    <button type="button" class="remove-file">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Tab -->
                <div class="tab-content" id="seo">
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-search"></i>
                            <h3>Optimisasi Mesin Pencari (SEO)</h3>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="seo-title">Judul SEO</label>
                                <input type="text" id="seo-title" name="meta_title" class="form-control" placeholder="Judul untuk mesin pencari" value="{{ old('meta_title', $product->meta_title) }}">
                                <div class="form-help">Rekomendasi: 50-60 karakter</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="seo-description">Deskripsi SEO</label>
                                <textarea id="seo-description" name="meta_description" class="form-control" rows="3" placeholder="Deskripsi untuk mesin pencari">{{ old('meta_description', $product->meta_description) }}</textarea>
                                <div class="form-help">Rekomendasi: 150-160 karakter</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="seo-keywords">Kata Kunci</label>
                                <input type="text" id="seo-keywords" name="meta_keywords" class="form-control" placeholder="Kata kunci dipisahkan koma" value="{{ old('meta_keywords', $product->meta_keywords) }}">
                                <div class="form-help">Contoh: headphone, wireless, noise cancelling</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Pratinjau Hasil Pencarian</label>
                                <div class="seo-preview">
                                    <div class="seo-preview-title" id="seo-preview-title">{{ old('meta_title', $product->meta_title) ?: 'Judul akan muncul di sini' }}</div>
                                    <div class="seo-preview-url">https://purplezone.com/products/{{ $product->slug }}</div>
                                    <div class="seo-preview-description" id="seo-preview-description">{{ old('meta_description', $product->meta_description) ?: 'Deskripsi akan muncul di sini' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div>
                        <button type="button" id="save-draft" class="btn btn-outline">
                            <i class="fas fa-save"></i> Simpan Draft
                        </button>
                    </div>
                    <div class="action-buttons">
                        <a href="/admin/produk" class="btn btn-outline">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" id="update-product" class="btn btn-primary">
                            <i class="fas fa-check"></i> Update Produk
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tab Navigation
    const tabs = document.querySelectorAll('.form-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tabId = tab.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            tab.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Deleted items arrays
    const deletedVariants = [];
    const deletedBuyLinks = [];
    const deletedMedia = [];

    // Custom Variants Management
    const variantsContainer = document.getElementById('variants-container');
    const addVariantBtn = document.getElementById('add-variant');
    
    // Hitung jumlah varian yang sudah ada
    let variantCount = {{ count(old('variants', $product->variants)) }};
    
    function createVariantItem() {
        variantCount++;
        const variantId = `new-variant-${variantCount}`;
        
        return `
            <div class="variant-item" id="${variantId}">
                <div class="variant-header">
                    <h4>Varian ${variantCount}</h4>
                    <div class="variant-actions">
                        <button type="button" class="btn btn-danger btn-sm remove-variant" data-variant-id="">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
                <div class="variant-content">
                    <div class="form-group">
                        <label>Nama Varian</label>
                        <input type="text" name="variants[${variantCount}][name]" class="form-control" placeholder="Contoh: Premium Edition" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe Varian</label>
                        <select name="variants[${variantCount}][type]" class="form-control variant-type" required>
                            <option value="">Pilih Tipe</option>
                            <option value="color">Warna</option>
                            <option value="size">Ukuran</option>
                            <option value="material">Material</option>
                            <option value="edition">Edition</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nilai Varian</label>
                        <input type="text" name="variants[${variantCount}][value]" class="form-control" placeholder="Contoh: Merah, XL, dll" required>
                    </div>
                    <div class="form-group">
                        <label>SKU Varian</label>
                        <input type="text" name="variants[${variantCount}][sku]" class="form-control" placeholder="Kode unik varian">
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="variants[${variantCount}][price]" class="form-control" placeholder="0" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="variants[${variantCount}][stock]" class="form-control" placeholder="0" min="0" required>
                    </div>
                </div>
                <div class="file-upload-section">
                    <label>File/Media Varian (Opsional)</label>
                    <div class="form-help">Upload gambar atau video khusus untuk varian ini</div>
                    <input type="file" name="variants[${variantCount}][file]" class="form-control variant-file" accept="image/*,video/*" style="margin-bottom: 10px;">
                    <div class="file-preview" id="file-preview-${variantCount}"></div>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="variant_active_${variantCount}" name="variants[${variantCount}][is_active]" value="1" checked>
                    <label for="variant_active_${variantCount}">Varian Aktif</label>
                </div>
            </div>
        `;
    }

    addVariantBtn.addEventListener('click', () => {
        const variantItem = document.createElement('div');
        variantItem.innerHTML = createVariantItem();
        variantsContainer.appendChild(variantItem);
        
        // Add event listener for file preview
        const fileInput = variantItem.querySelector('.variant-file');
        const previewContainer = variantItem.querySelector('.file-preview');
        
        fileInput.addEventListener('change', function(e) {
            previewContainer.innerHTML = '';
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const previewItem = document.createElement('div');
                previewItem.className = 'file-preview-item';
                
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    previewItem.appendChild(img);
                } else if (file.type.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.src = URL.createObjectURL(file);
                    video.controls = true;
                    previewItem.appendChild(video);
                }
                
                const fileInfo = document.createElement('div');
                fileInfo.className = 'file-info';
                fileInfo.textContent = file.name;
                previewItem.appendChild(fileInfo);
                
                previewContainer.appendChild(previewItem);
            }
        });
    });

    // Remove variant dengan tracking deleted items
    variantsContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-variant')) {
            const button = e.target.closest('.remove-variant');
            const variantId = button.getAttribute('data-variant-id');
            const variantItem = button.closest('.variant-item');
            
            if (variantId) {
                // Jika variant sudah ada di database, tambahkan ke deleted list
                deletedVariants.push(variantId);
                document.getElementById('deleted-variants').value = deletedVariants.join(',');
            }
            
            if (variantsContainer.querySelectorAll('.variant-item').length > 1) {
                variantItem.remove();
                // Renumber remaining variants
                const remainingVariants = variantsContainer.querySelectorAll('.variant-item');
                remainingVariants.forEach((item, index) => {
                    item.querySelector('h4').textContent = `Varian ${index + 1}`;
                });
                variantCount = remainingVariants.length;
            } else {
                alert('Produk harus memiliki minimal 1 varian');
            }
        }
    });

    // Buy Links Management
    const buyLinksContainer = document.getElementById('buy-links-container');
    const addBuyLinkBtn = document.getElementById('add-buy-link');
    
    let buyLinkCount = {{ count(old('buy_links', $product->buyLinks)) }};

    function createBuyLinkItem() {
        buyLinkCount++;
        return `
            <div class="buy-link-item">
                <div class="buy-link-header">
                    <h5>Link Beli ${buyLinkCount}</h5>
                    <button type="button" class="btn btn-danger btn-sm remove-buy-link" data-link-id="">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="buy-link-content">
                    <div class="form-group">
                        <label>Platform</label>
                        <select name="buy_links[${buyLinkCount}][platform]" class="form-control" required>
                            <option value="">Pilih Platform</option>
                            <option value="shopee">Shopee</option>
                            <option value="tokopedia">Tokopedia</option>
                            <option value="lazada">Lazada</option>
                            <option value="blibli">Blibli</option>
                            <option value="bukalapak">Bukalapak</option>
                            <option value="website">Website</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Platform (jika Lainnya)</label>
                        <input type="text" name="buy_links[${buyLinkCount}][custom_platform]" class="form-control" placeholder="Nama platform">
                    </div>
                    <div class="form-group full-width">
                        <label>URL Link Beli</label>
                        <input type="url" name="buy_links[${buyLinkCount}][url]" class="form-control" placeholder="https://..." required>
                    </div>
                    <div class="form-group">
                        <label>Harga di Platform ini (Rp)</label>
                        <input type="number" name="buy_links[${buyLinkCount}][price]" class="form-control" placeholder="0" min="0">
                    </div>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="link_active_${buyLinkCount}" name="buy_links[${buyLinkCount}][is_active]" value="1" checked>
                    <label for="link_active_${buyLinkCount}">Link Aktif</label>
                </div>
            </div>
        `;
    }

    addBuyLinkBtn.addEventListener('click', () => {
        const buyLinkItem = document.createElement('div');
        buyLinkItem.innerHTML = createBuyLinkItem();
        buyLinksContainer.appendChild(buyLinkItem);
    });

    // Remove buy link dengan tracking deleted items
    buyLinksContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-buy-link')) {
            const button = e.target.closest('.remove-buy-link');
            const linkId = button.getAttribute('data-link-id');
            const buyLinkItem = button.closest('.buy-link-item');
            
            if (linkId) {
                // Jika link sudah ada di database, tambahkan ke deleted list
                deletedBuyLinks.push(linkId);
                document.getElementById('deleted-buy-links').value = deletedBuyLinks.join(',');
            }
            
            if (buyLinksContainer.querySelectorAll('.buy-link-item').length > 1) {
                buyLinkItem.remove();
                // Renumber remaining buy links
                const remainingLinks = buyLinksContainer.querySelectorAll('.buy-link-item');
                remainingLinks.forEach((item, index) => {
                    item.querySelector('h5').textContent = `Link Beli ${index + 1}`;
                });
                buyLinkCount = remainingLinks.length;
            }
        }
    });

    // Media deletion handling
    document.querySelectorAll('.media-delete-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                deletedMedia.push(this.value);
            } else {
                const index = deletedMedia.indexOf(this.value);
                if (index > -1) {
                    deletedMedia.splice(index, 1);
                }
            }
            document.getElementById('deleted-media').value = deletedMedia.join(',');
        });
    });

    // Media Upload Functionality (sama seperti create)
    const mainImagePreview = document.getElementById('main-image-preview');
    const mainImageInput = document.getElementById('main-image');
    
    mainImagePreview.addEventListener('click', () => {
        mainImageInput.click();
    });
    
    mainImageInput.addEventListener('change', (e) => {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            const reader = new FileReader();
            
            reader.onload = (e) => {
                mainImagePreview.classList.add('has-file');
                if (file.type.startsWith('image/')) {
                    mainImagePreview.querySelector('img').src = e.target.result;
                    mainImagePreview.querySelector('img').style.display = 'block';
                    mainImagePreview.querySelector('video').style.display = 'none';
                } else if (file.type.startsWith('video/')) {
                    mainImagePreview.querySelector('video').src = e.target.result;
                    mainImagePreview.querySelector('video').style.display = 'block';
                    mainImagePreview.querySelector('img').style.display = 'none';
                }
            }
            
            reader.readAsDataURL(file);
        }
    });

    // Additional media functionality
    const additionalMediaContainer = document.getElementById('additional-media-container');
    
    additionalMediaContainer.addEventListener('click', (e) => {
        if (e.target.closest('.image-preview') && 
            !e.target.closest('.image-preview').classList.contains('has-file') &&
            additionalMediaContainer.querySelectorAll('.image-preview').length < 6) {
            
            const mediaPreview = e.target.closest('.image-preview');
            const fileInput = mediaPreview.querySelector('input[type="file"]');
            fileInput.click();
        }
    });
    
    additionalMediaContainer.addEventListener('change', (e) => {
        if (e.target.classList.contains('additional-media-input')) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                const reader = new FileReader();
                const mediaPreview = e.target.closest('.image-preview');
                
                reader.onload = (e) => {
                    mediaPreview.classList.add('has-file');
                    if (file.type.startsWith('image/')) {
                        mediaPreview.querySelector('img').src = e.target.result;
                        mediaPreview.querySelector('img').style.display = 'block';
                        mediaPreview.querySelector('video').style.display = 'none';
                    } else if (file.type.startsWith('video/')) {
                        mediaPreview.querySelector('video').src = e.target.result;
                        mediaPreview.querySelector('video').style.display = 'block';
                        mediaPreview.querySelector('img').style.display = 'none';
                    }
                    
                    // Add new empty slot if we have less than 5 media
                    if (additionalMediaContainer.querySelectorAll('.image-preview').length < 5) {
                        const newMediaPreview = document.createElement('div');
                        newMediaPreview.className = 'image-preview additional-media';
                        newMediaPreview.innerHTML = `
                            <div class="upload-text">
                                <i class="fas fa-plus"></i>
                                <span>Tambah Media</span>
                            </div>
                            <img src="" alt="Preview" style="display: none;">
                            <video controls style="display: none;"></video>
                            <input type="file" class="additional-media-input" accept="image/*,video/*" style="display: none;">
                            <button type="button" class="remove-file">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        additionalMediaContainer.appendChild(newMediaPreview);
                    }
                }
                
                reader.readAsDataURL(file);
            }
        }
    });

    // Remove media
    additionalMediaContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-file')) {
            e.stopPropagation();
            const mediaPreview = e.target.closest('.image-preview');
            mediaPreview.classList.remove('has-file');
            mediaPreview.querySelector('img').src = '';
            mediaPreview.querySelector('video').src = '';
            mediaPreview.querySelector('img').style.display = 'none';
            mediaPreview.querySelector('video').style.display = 'none';
            mediaPreview.querySelector('input[type="file"]').value = '';
            
            // If this is not the last empty slot, remove it
            const allPreviews = additionalMediaContainer.querySelectorAll('.image-preview');
            if (allPreviews.length > 1 && !mediaPreview.classList.contains('has-file') && 
                mediaPreview === allPreviews[allPreviews.length - 1]) {
                mediaPreview.remove();
            }
        }
    });

    // SEO Preview
    const seoTitleInput = document.getElementById('seo-title');
    const seoDescriptionInput = document.getElementById('seo-description');
    const seoPreviewTitle = document.getElementById('seo-preview-title');
    const seoPreviewDescription = document.getElementById('seo-preview-description');
    
    seoTitleInput.addEventListener('input', () => {
        seoPreviewTitle.textContent = seoTitleInput.value || 'Judul akan muncul di sini';
    });
    
    seoDescriptionInput.addEventListener('input', () => {
        seoPreviewDescription.textContent = seoDescriptionInput.value || 'Deskripsi akan muncul di sini';
    });

    // Auto-generate SEO title and description from product name
    const productNameInput = document.getElementById('product-name');
    productNameInput.addEventListener('blur', () => {
        if (productNameInput.value && !seoTitleInput.value) {
            seoTitleInput.value = `Beli ${productNameInput.value} - Harga Terbaik | PurpleZone`;
            seoPreviewTitle.textContent = seoTitleInput.value;
        }
        
        if (productNameInput.value && !seoDescriptionInput.value) {
            seoDescriptionInput.value = `Temukan ${productNameInput.value} dengan kualitas terbaik dan harga terjangkau hanya di PurpleZone. Pesan sekarang dan dapatkan penawaran spesial!`;
            seoPreviewDescription.textContent = seoDescriptionInput.value;
        }
    });

    // Form Validation and Submission
    const form = document.getElementById('edit-product-form');
    const updateBtn = document.getElementById('update-product');
    
    // Remove error class when user starts typing
    form.addEventListener('input', (e) => {
        if (e.target.hasAttribute('required')) {
            e.target.classList.remove('error');
        }
    });
    
    // Save draft functionality
    document.getElementById('save-draft').addEventListener('click', () => {
        document.getElementById('is_draft').checked = true;
        form.submit();
    });

    // Show success message if there's any in session
    @if(session('success'))
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#8B5FBF'
            });
        });
    @endif
</script>
@endsection