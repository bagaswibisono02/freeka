@extends('layouts.navUser')
@section('body')
  <style>
        :root {
            --primary-purple: #7E57C2;
            --dark-purple: #5E35B1;
            --light-purple: #B39DDB;
            --accent-purple: #D1C4E9;
            --bg-light: #F5F3FF;
            --text-dark: #2D3748;
            --text-light: #718096;
            --success: #48BB78;
            --warning: #ED8936;
            --danger: #F56565;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #F5F3FF 0%, #EDE9FE 100%);
            color: var(--text-dark);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(126, 87, 194, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--dark-purple);
        }

        .logo i {
            font-size: 1.8rem;
        }

        .user-actions {
            display: flex;
            gap: 15px;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--accent-purple);
            color: var(--dark-purple);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            background: var(--primary-purple);
            color: white;
            transform: translateY(-2px);
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-purple);
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: var(--text-light);
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(126, 87, 194, 0.1);
            border: none;
            margin-bottom: 25px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(126, 87, 194, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-purple), var(--dark-purple));
            color: white;
            padding: 18px 25px;
            border-bottom: none;
            font-weight: 600;
        }

        .card-body {
            padding: 25px;
        }

        .order-info {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .product-image {
            flex: 0 0 300px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .product-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-image img:hover {
            transform: scale(1.05);
        }

        .product-details {
            flex: 1;
            min-width: 300px;
        }

        .product-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-purple);
            margin-bottom: 15px;
        }

        .detail-item {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #EDF2F7;
        }

        .detail-label {
            flex: 0 0 180px;
            font-weight: 600;
            color: var(--text-light);
        }

        .detail-value {
            flex: 1;
            color: var(--text-dark);
        }

        .price-highlight {
            color: var(--primary-purple);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-processing {
            background: rgba(237, 137, 54, 0.15);
            color: var(--warning);
        }

        .status-shipped {
            background: rgba(72, 187, 120, 0.15);
            color: var(--success);
        }

        .status-delivered {
            background: rgba(126, 87, 194, 0.15);
            color: var(--primary-purple);
        }

        .tracking-container {
            padding: 20px 0;
        }

        .tracking-timeline {
            position: relative;
            padding-left: 30px;
        }

        .tracking-timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--accent-purple);
            border-radius: 3px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -36px;
            top: 5px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: white;
            border: 3px solid var(--light-purple);
            z-index: 2;
        }

        .timeline-item.active::before {
            background: var(--primary-purple);
            border-color: var(--primary-purple);
        }

        .timeline-item.completed::before {
            background: var(--success);
            border-color: var(--success);
        }

        .timeline-content {
            background: var(--bg-light);
            padding: 18px;
            border-radius: 12px;
            border-left: 4px solid var(--light-purple);
        }

        .timeline-item.active .timeline-content {
            border-left-color: var(--primary-purple);
            background: white;
            box-shadow: 0 4px 12px rgba(126, 87, 194, 0.1);
        }

        .timeline-item.completed .timeline-content {
            border-left-color: var(--success);
        }

        .timeline-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark-purple);
        }

        .timeline-date {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .timeline-desc {
            margin-top: 8px;
            font-size: 0.9rem;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-purple), var(--dark-purple));
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(126, 87, 194, 0.3);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary-purple);
            color: var(--primary-purple);
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background: var(--primary-purple);
            color: white;
            transform: translateY(-2px);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-card {
            background: var(--bg-light);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid var(--primary-purple);
        }

        .info-card i {
            font-size: 1.5rem;
            color: var(--primary-purple);
            margin-bottom: 10px;
        }

        .info-card h5 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .info-card p {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        @media (max-width: 768px) {
            .order-info {
                flex-direction: column;
            }
            
            .product-image {
                flex: 0 0 100%;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .detail-item {
                flex-direction: column;
            }
            
            .detail-label {
                flex: 0 0 100%;
                margin-bottom: 5px;
            }
        }
    </style>
<div class="container">
    <h1 class="page-title">Detail Pemesanan</h1>
    <p class="page-subtitle">Lacak pesanan dan lihat detail produk yang Anda beli</p>

    <!-- Card Informasi Pesanan -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-shopping-bag me-2"></i>Informasi Pesanan
        </div>
        <div class="card-body">
            <div class="order-info">
                <div class="product-image">
                    <img src="{{ url('/file?file=' . encrypt($pesanan->produk->media[0]->file)) }}" alt="Produk">
                </div>
                <div class="product-details">
                    <h2 class="product-title">{{ $pesanan->produk->nama }}</h2>
                    
                    <div class="detail-item">
                        <div class="detail-label">Harga Satuan</div>
                        <div class="detail-value">@currency($pesanan->produk->harga)</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Jumlah</div>
                        <div class="detail-value">{{ $pesanan->jumlah }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Total Harga</div>
                        <div class="detail-value price-highlight">@currency($pesanan->jumlah * $pesanan->produk->harga)</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Alamat Pengiriman</div>
                        <div class="detail-value">{{ $pesanan->alamatPenerima->alamat ?? '-' }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Penerima</div>
                        <div class="detail-value">{{ $pesanan->alamatPenerima->penerima }} ({{ $pesanan->alamatPenerima->contact ?? '-' }})</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Status Pesanan</div>
                        <div class="detail-value">
                            @php
                                $statusBadge = match(strtolower($pesanan->pengiriman->status ?? 'diproses')) {
                                    'diproses' => 'status-processing',
                                    'dikirim' => 'status-shipped',
                                    'delivered' => 'status-delivered',
                                    default => 'status-processing'
                                };
                                $statusText = ucfirst($pesanan->pengiriman->status ?? 'Sedang diproses penjual');
                            @endphp
                            <span class="status-badge {{ $statusBadge }}">{{ $statusText }}</span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">No. Resi</div>
                        <div class="detail-value">
                            {{ $pesanan->pengiriman->resi ?? 'Sedang diproses penjual' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Ekspedisi</div>
                        <div class="detail-value">
                            {{ $pesanan->pengiriman->ekspedisi ?? 'Sedang diproses penjual' }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="action-buttons mt-4">
                <button class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>Cetak Invoice
                </button>
                <button class="btn btn-outline">
                    <i class="fas fa-headset me-2"></i>Hubungi Penjual
                </button>
                <button class="btn btn-outline">
                    <i class="fas fa-undo me-2"></i>Ajukan Pengembalian
                </button>
            </div>
        </div>
    </div>

    <!-- Card Lacak Pengiriman -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-shipping-fast me-2"></i>Lacak Pengiriman
        </div>
        <div class="card-body">
            <div class="tracking-container">
                <div class="tracking-timeline">
                    @php
                        $pengiriman = $pesanan->pengiriman;
                        $response = $pengiriman ? json_decode($pengiriman->response_api, true) : null;
                    @endphp

                    @if(!$pengiriman || !$pengiriman->resi || !$pengiriman->ekspedisi)
                        <div class="timeline-item active">
                            <div class="timeline-content">
                                <div class="timeline-title">Pesanan Sedang Diproses</div>
                                <div class="timeline-date">{{ \Carbon\Carbon::parse($pesanan->created_at)->translatedFormat('d M Y, H:i') }}</div>
                                <div class="timeline-desc">Pesanan Anda sedang dipersiapkan oleh penjual</div>
                            </div>
                        </div>
                    @elseif($response && isset($response['history']))
                        @foreach($response['history'] as $h)
                            @php
                                $classStatus = strtoupper($h['desc']) === 'DELIVERED' ? 'completed' : 'active';
                            @endphp
                            <div class="timeline-item {{ $classStatus }}">
                                <div class="timeline-content">
                                    <div class="timeline-title">{{ $h['desc'] }}</div>
                                    <div class="timeline-date">{{ \Carbon\Carbon::parse($h['date'])->translatedFormat('d M Y, H:i') }}</div>
                                    <div class="timeline-desc">{{ $h['location'] ?? '' }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="timeline-item active">
                            <div class="timeline-content">
                                <div class="timeline-title">Menunggu Update Pengiriman</div>
                                <div class="timeline-date">{{ \Carbon\Carbon::parse($pesanan->created_at)->translatedFormat('d M Y, H:i') }}</div>
                                <div class="timeline-desc">Resi dan data pengiriman belum tersedia</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Card Informasi Tambahan -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
        </div>
       <div class="card-body">
    <div class="info-grid">
        <!-- Tanggal Pesanan -->
        <div class="info-card">
            <i class="fas fa-calendar"></i>
            <h5>Tanggal Pesanan</h5>
            <p>{{ \Carbon\Carbon::parse($pesanan->created_at)->translatedFormat('d M Y, H:i') }}</p>
        </div>

        <!-- No. Invoice -->
        <div class="info-card">
            <i class="fas fa-receipt"></i>
            <h5>No. Invoice</h5>
            @php
                $faspay = json_decode($pesanan->response_faspay, true);
                $invoice = $faspay['data']['merchant_ref'] ?? '-';
            @endphp
            <p>{{ $invoice }}</p>
        </div>

        <!-- Ekspedisi -->
        <div class="info-card">
            <i class="fas fa-truck"></i>
            <h5>Ekspedisi</h5>
            <p>{{ $pengiriman->ekspedisi ?? 'Sedang diproses penjual' }}</p>
        </div>

        <!-- Resi -->
        <div class="info-card">
            <i class="fas fa-barcode"></i>
            <h5>Resi</h5>
            <p>{{ $pengiriman->resi ?? 'Sedang diproses penjual' }}</p>
        </div>
    </div>
</div>

    </div>
</div>
@endsection
