
 @extends('layouts.navUser')

@section('body')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        


      
        .logo {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .logo i {
            color: var(--accent);
        }
        
        header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        header p {
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.9;
        }
        
        .profile-section {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(138, 43, 226, 0.15);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            border: 2px solid var(--primary-light);
        }
        
        .profile-section::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, var(--primary-light) 0%, transparent 70%);
            border-radius: 0 20px 0 100px;
            opacity: 0.1;
        }
        
        .profile-pic {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 30px;
            overflow: hidden;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        
        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-pic i {
            font-size: 50px;
            color: white;
        }
        
        .profile-info h2 {
            font-size: 28px;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .profile-info p {
            color: var(--text);
            margin-bottom: 20px;
            font-size: 16px;
        }
        
        .referral-stats {
            display: flex;
            gap: 20px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 15px;
            text-align: center;
            min-width: 120px;
            box-shadow: 0 5px 15px rgba(138, 43, 226, 0.3);
        }
        
        .stat-card h3 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }
        
        .referral-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(138, 43, 226, 0.15);
            margin-bottom: 30px;
            border: 2px solid var(--primary-light);
            position: relative;
            overflow: hidden;
        }
        
        .referral-section::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, var(--secondary) 0%, transparent 70%);
            opacity: 0.1;
            border-radius: 50%;
        }
        
        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            color: var(--primary);
        }
        
        .referral-code {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(138, 43, 226, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .referral-code::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.1"><path d="M30,30 Q50,10 70,30 T90,50 T70,70 T50,90 T30,70 T10,50 T30,30 Z" fill="white"/></svg>');
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .referral-code h3 {
            font-size: 20px;
            margin-bottom: 15px;
            position: relative;
        }
        
        .code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 4px;
            margin: 15px 0;
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 10px;
            display: inline-block;
            position: relative;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .copy-btn {
            background: white;
            color: var(--primary);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .copy-btn:hover {
            background: var(--light);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .share-options {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .share-btn {
            flex: 1;
            min-width: 140px;
            padding: 15px;
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .share-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .share-btn:hover::before {
            left: 100%;
        }
        
        .share-btn.whatsapp {
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        }
        
        .share-btn.facebook {
            background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        }
        
        .share-btn.telegram {
            background: linear-gradient(135deg, #0088cc 0%, #005999 100%);
        }
        
        .share-btn.email {
            background: linear-gradient(135deg, #dd4b39 0%, #c23321 100%);
        }
        
        .share-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .benefits-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(138, 43, 226, 0.15);
            margin-bottom: 30px;
            border: 2px solid var(--primary-light);
            position: relative;
            overflow: hidden;
        }
        
        .benefits-section::after {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, var(--accent) 0%, transparent 70%);
            opacity: 0.1;
            border-radius: 50%;
        }
        
        .benefit-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        
        .benefit-card {
            background: linear-gradient(135deg, #F8F0FF 0%, white 100%);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid var(--primary-light);
            box-shadow: 0 5px 15px rgba(138, 43, 226, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .benefit-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }
        
        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(138, 43, 226, 0.2);
        }
        
        .benefit-card h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .benefit-card p {
            color: var(--text);
            font-size: 15px;
            margin-bottom: 15px;
        }
        
        .benefit-card .icon {
            font-size: 50px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .progress-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(138, 43, 226, 0.15);
            margin-bottom: 30px;
            border: 2px solid var(--primary-light);
            position: relative;
            overflow: hidden;
        }
        
        .progress-container {
            margin-top: 20px;
        }
        
        .progress-bar {
            height: 25px;
            background: #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 15px;
            transition: width 0.8s ease;
            position: relative;
            overflow: hidden;
        }
        
        .progress::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .progress-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }
        
        .progress-label {
            text-align: center;
            flex: 1;
            padding: 10px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .progress-label.active {
            background: var(--light);
            color: var(--primary);
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(138, 43, 226, 0.1);
        }
        
        .referral-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-card-grid {
            background: linear-gradient(135deg, var(--light) 0%, white 100%);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(138, 43, 226, 0.1);
            border: 1px solid var(--primary-light);
        }
        
        .stat-card-grid h3 {
            font-size: 32px;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .stat-card-grid p {
            color: var(--text);
            font-size: 15px;
        }
        
        .referral-list {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(138, 43, 226, 0.15);
            border: 2px solid var(--primary-light);
            position: relative;
            overflow: hidden;
        }
        
        .referral-list::before {
            content: "";
            position: absolute;
            bottom: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, var(--secondary) 0%, transparent 70%);
            opacity: 0.1;
            border-radius: 50%;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }
        
        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            font-weight: 600;
        }
        
        tr:hover {
            background-color: var(--light);
        }
        
        .status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }
        
        .status.active {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .status.pending {
            background: #fff3e0;
            color: #ef6c00;
        }
        
        footer {
            text-align: center;
            padding: 30px;
            margin-top: 40px;
            color: var(--text);
            font-size: 15px;
        }
        
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: var(--primary);
            opacity: 0;
            pointer-events: none;
        }
        
        @media (max-width: 768px) {
            .profile-section {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-pic {
                margin-right: 0;
                margin-bottom: 20px;
            }
            
            .referral-stats {
                justify-content: center;
            }
            
            .share-options {
                flex-direction: column;
            }
            
            .benefit-cards {
                grid-template-columns: 1fr;
            }
            
            .referral-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .code {
                font-size: 28px;
                letter-spacing: 3px;
            }
        }
        
        /* Animasi tambahan */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .floating {
            animation: float 5s ease-in-out infinite;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>


    
  <div class="container">
    {{-- PROFILE SECTION --}}
    <section class="profile-section">
        <div class="profile-pic floating">
            @if ($user->profile_photo_path)
                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile">
            @else
                <i class="fas fa-user"></i>
            @endif
        </div>
        <div class="profile-info">
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }} | Bergabung sejak {{ $user->created_at->translatedFormat('F Y') }}</p>
            <div class="referral-stats">
                <div class="stat-card pulse">
                    <h3>{{ $referralCount }}</h3>
                    <p>Referral</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $points }}</h3>
                    <p>Poin</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $rewards->where('referral_count', '<=', $referralCount)->count() }}</h3>
                    <p>Hadiah</p>
                </div>
            </div>
        </div>
    </section>

    {{-- REFERRAL CODE --}}
    <section class="referral-section">
        <h2 class="section-title">
            <i class="fas fa-link"></i> Kode Referral Anda
        </h2>
        <div class="referral-code">
            <h3>Bagikan kode ini kepada teman Anda dan dapatkan hadiah!</h3>
            <div class="code">{{ $user->referral_code }}</div>
            <button class="copy-btn" onclick="copyReferralCode()">
                <i class="fas fa-copy"></i> Salin Kode
            </button>

            <div class="share-options">
                @php
                    $shareLink = url('/register?ref=' . $user->referral_code);
                @endphp
                <a href="https://wa.me/?text={{ urlencode('Gabung menggunakan kode referral saya: ' . $shareLink) }}" target="_blank" class="share-btn whatsapp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="https://facebook.com/sharer/sharer.php?u={{ urlencode($shareLink) }}" target="_blank" class="share-btn facebook">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a href="https://t.me/share/url?url={{ urlencode($shareLink) }}" target="_blank" class="share-btn telegram">
                    <i class="fab fa-telegram-plane"></i> Telegram
                </a>
                <a href="mailto:?subject=Kode Referral&body={{ urlencode('Gunakan kode referral saya: ' . $user->referral_code . ' di ' . $shareLink) }}" class="share-btn email">
                    <i class="fas fa-envelope"></i> Email
                </a>
            </div>
        </div>
    </section>

    {{-- BENEFITS --}}
    <section class="benefits-section">
        <h2 class="section-title">
            <i class="fas fa-gift"></i> Manfaat Referral
        </h2>
        <div class="benefit-cards">
            @foreach ($rewards as $reward)
                <div class="benefit-card">
                    <div class="icon">
                        @if ($reward->referral_count <= 10)
                            <i class="fas fa-shipping-fast"></i>
                        @elseif ($reward->referral_count <= 20)
                            <i class="fas fa-coins"></i>
                        @else
                            <i class="fas fa-crown"></i>
                        @endif
                    </div>
                    <h3>{{ $reward->referral_count }} Referral</h3>
                    <p>{{ $reward->description }}</p>
                    <div class="progress-label {{ $referralCount >= $reward->referral_count ? 'active' : '' }}">
                        Saat ini: {{ $referralCount }}/{{ $reward->referral_count }}
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- REFERRAL LIST --}}
    <section class="referral-list">
        <h2 class="section-title">
            <i class="fas fa-users"></i> Daftar Referral Anda
        </h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal Bergabung</th>
                    <th>Status</th>
                    <th>Poin Diperoleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($referrals as $ref)
                    <tr>
                        <td>{{ $ref->referredUser->name }}</td>
                        <td>{{ $ref->referredUser->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="status {{ $ref->status }}">
                                {{ ucfirst($ref->status) }}
                            </span>
                        </td>
                        <td>{{ $ref->referredUser->referralPoints->points ?? 0 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada referral</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
    
    <footer>
        <div class="container">
            <p>&copy; 2023 Program Referral. Semua hak dilindungi. | <a href="#" style="color: var(--primary);">Syarat dan Ketentuan</a></p>
        </div>
    </footer>

    <script>
        function copyReferralCode() {
            const code = document.querySelector('.code').textContent;
            navigator.clipboard.writeText(code).then(() => {
                alert('Kode referral berhasil disalin: ' + code);
                createConfetti();
            });
        }
        
        function createConfetti() {
            const colors = ['#8A2BE2', '#9B30FF', '#FF6BCB', '#FFD700', '#4A3C6E'];
            const confettiCount = 100;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.animationDelay = Math.random() * 5 + 's';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.opacity = Math.random() * 0.5 + 0.5;
                document.body.appendChild(confetti);
                
                // Animate confetti
                animateConfetti(confetti);
            }
            
            // Remove confetti after animation
            setTimeout(() => {
                const confettiElements = document.querySelectorAll('.confetti');
                confettiElements.forEach(el => el.remove());
            }, 5000);
        }
        
        function animateConfetti(confetti) {
            const duration = Math.random() * 3 + 2;
            const horizontal = Math.random() * 200 - 100;
            
            confetti.style.transition = `all ${duration}s ease-out`;
            confetti.style.transform = `translate(${horizontal}px, 100vh) rotate(${Math.random() * 720}deg)`;
            confetti.style.opacity = '0';
        }
        
        // Simulasi progress bar berdasarkan jumlah referral
        function updateProgressBar(referralCount) {
            const progressBar = document.querySelector('.progress');
            let progressPercentage = 0;
            
            if (referralCount >= 100) {
                progressPercentage = 100;
            } else if (referralCount >= 20) {
                progressPercentage = 20 + (referralCount - 20) * (80 / 80);
            } else if (referralCount >= 10) {
                progressPercentage = 10 + (referralCount - 10) * (10 / 10);
            } else {
                progressPercentage = referralCount;
            }
            
            progressBar.style.width = progressPercentage + '%';
        }
        
        // Inisialisasi progress bar
        document.addEventListener('DOMContentLoaded', function() {
            updateProgressBar(15); // Jumlah referral saat ini
            
            // Tambahkan efek hover pada card
            const cards = document.querySelectorAll('.benefit-card, .stat-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection