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
            --gray-100: #F7FAFC;
            --gray-200: #EDF2F7;
        }




        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-item {
            margin-bottom: 8px;
            padding: 0 15px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {

            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-purple);
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: var(--text-light);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-purple), var(--dark-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Notification Filter */
        .notification-filter {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 30px;
            color: var(--text-light);
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary-purple);
            color: white;
            border-color: var(--primary-purple);
        }

        /* Notification Styles */
        .notification-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(126, 87, 194, 0.1);
            overflow: hidden;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            border-bottom: 1px solid var(--gray-200);
        }

        .notification-header h3 {
            font-weight: 600;
            color: var(--dark-purple);
            margin: 0;
        }

        .mark-all-read {
            background: none;
            border: none;
            color: var(--primary-purple);
            font-weight: 500;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .mark-all-read:hover {
            color: var(--dark-purple);
        }

        .notification-list {
            max-height: 600px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            padding: 20px 25px;
            border-bottom: 1px solid var(--gray-200);
            transition: background 0.3s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background: var(--bg-light);
        }

        .notification-item.unread {
            background: rgba(126, 87, 194, 0.05);
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .icon-order {
            background: rgba(72, 187, 120, 0.15);
            color: var(--success);
        }

        .icon-promo {
            background: rgba(237, 137, 54, 0.15);
            color: var(--warning);
        }

        .icon-system {
            background: rgba(126, 87, 194, 0.15);
            color: var(--primary-purple);
        }

        .icon-security {
            background: rgba(245, 101, 101, 0.15);
            color: var(--danger);
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--text-dark);
        }

        .notification-desc {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .notification-time {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-purple);
            color: white;
            border: none;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--primary-purple);
            color: var(--primary-purple);
        }

        .unread-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary-purple);
            margin-left: 10px;
            flex-shrink: 0;
            align-self: center;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--light-purple);
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--text-light);
            max-width: 400px;
            margin: 0 auto 20px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
            }

            .logo span,
            .nav-link span {
                display: none;
            }

            .nav-link {
                justify-content: center;
                padding: 15px;
            }


        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }



            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .notification-filter {
                justify-content: center;
            }
        }
    </style>
    <div class="container-fluid">

        <!-- Main Content -->
        <div class="main-content">

            <div class="notification-filter">
                <a href="/notifikasi?filter=all" class="filter-btn {{ request('filter') == 'all' ? 'active' : '' }}">Semua</a>
                <a href="/notifikasi?filter=unread"
                    class="filter-btn {{ request('filter') == 'unread' ? 'active' : '' }}">Belum Dibaca</a>
                <a href="/notifikasi?filter=order"
                    class="filter-btn {{ request('filter') == 'order' ? 'active' : '' }}">Pesanan</a>
                <a href="/notifikasi?filter=promo"
                    class="filter-btn {{ request('filter') == 'promo' ? 'active' : '' }}">Promo</a>
                <a href="/notifikasi?filter=system"
                    class="filter-btn {{ request('filter') == 'system' ? 'active' : '' }}">Sistem</a>
            </div>


            <!-- Notification Container -->
            <div class="notification-container">
                <div class="notification-header">
                    <h3>Notifikasi Terbaru</h3>
                    <form action="" method="POST">
                        @csrf
                        <button type="submit" class="mark-all-read">
                            <i class="fas fa-check-double me-2"></i>Tandai Sudah Dibaca Semua
                        </button>
                    </form>
                </div>

                <div class="notification-list">
                    @forelse($notifikasi as $item)
                        <div class="notification-item {{ $item->is_read ? '' : 'unread' }}">
                            <div
                                class="notification-icon 
                            @if (Str::contains(strtolower($item->title), 'pesanan')) icon-order
                            @elseif(Str::contains(strtolower($item->title), 'promo')) icon-promo
                            @elseif(Str::contains(strtolower($item->title), 'keamanan')) icon-security
                            @else icon-system @endif">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">{{ $item->title }}</div>
                                <p class="notification-desc">{{ $item->message }}</p>
                                <div class="notification-time">{{ $item->created_at->diffForHumans() }}</div>
                                <div class="notification-actions">
                                    @if (Str::contains(strtolower($item->title), 'pesanan'))
                                        <a href="{{ route('pesanan.show', $item->pesanan_id ?? '') }}"
                                            class="action-btn btn-primary">Lacak Pesanan</a>
                                    @endif
                                </div>
                            </div>
                            @if (!$item->is_read)
                                <div class="unread-indicator"></div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-bell-slash"></i>
                            <h4>Tidak ada notifikasi</h4>
                            <p>Semua notifikasi Anda sudah dibaca atau belum ada notifikasi baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const filterButtons = document.querySelectorAll('.filter-btn');

                filterButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault(); // mencegah default klik jika pakai <a>

                        // Hapus kelas active dari semua tombol
                        filterButtons.forEach(btn => btn.classList.remove('active'));
                        // Tambahkan active ke tombol yang diklik
                        this.classList.add('active');

                        // Ambil URL dari tombol (menggunakan href)
                        const url = this.getAttribute('href');
                        if (url) {
                            // Redirect ke URL dengan query parameter
                            window.location.href = url;
                        }
                    });
                });
            });
        </script>
    @endsection
