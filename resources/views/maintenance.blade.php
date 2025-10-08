<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Freeka - Maintenance</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-purple-100 via-purple-300 to-orange-100 flex items-center justify-center px-4 sm:px-6 lg:px-8"

>

    <div class="text-center max-w-md">
        <!-- Animated Icon -->
        <div class="animate-float mb-6">
            <img src="https://cdn-icons-png.flaticon.com/512/5953/5953008.png" alt="Maintenance"
                class="w-28 sm:w-32 mx-auto drop-shadow-xl rounded-xl">
        </div>

        <!-- Title -->
        <h1 class="text-3xl sm:text-4xl font-extrabold text-purple-700 mb-3">
            Sedang Maintenance
        </h1>

        <!-- Description -->
        <p class="text-sm sm:text-base text-gray-700 leading-relaxed mb-6">
            Kami sedang memperbarui sistem untuk layanan yang lebih baik. Harap bersabar ya! 💖
        </p>

        <!-- Animated Icons -->
        <div class="flex justify-center gap-4 text-2xl text-pink-600 animate-pulse">
            <i class="ph ph-gear-six"></i>
            <i class="ph ph-wrench"></i>
            <i class="ph ph-gear"></i>
        </div>

        <!-- Optional Back Button -->
       <a href="/" class="inline-block mt-6 text-sm px-6 py-2 rounded-full bg-gradient-to-r from-purple-600 via-pink-500 to-orange-400 text-white font-semibold shadow-md hover:shadow-lg hover:brightness-110 transition">
    Kembali ke Beranda
</a>


        <!-- Footer -->
        <p class="mt-8 text-xs text-gray-400">&copy; {{ date('Y') }} Freeka. Semua hak dilindungi.</p>
    </div>

</body>
</html>
