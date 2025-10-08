<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Freeka</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .hat-accent {
            background: linear-gradient(to right, #9333ea, #ec4899, #f97316);
        }

        .hat-accent-text {
            background: linear-gradient(to right, #9333ea, #ec4899, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hat-accent-btn {
            background: linear-gradient(to right, #9333ea, #ec4899, #f97316);
            color: white;
        }

        .hat-accent-btn:hover {
            filter: brightness(1.1);
        }
    </style>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-orange-50 flex items-center justify-center px-4 sm:px-6 lg:px-8">

    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md border border-pink-100">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="text-3xl font-extrabold hat-accent-text">Selamat Datang!</div>
            <p class="text-sm text-gray-500 mt-1">Masuk ke akun <strong>Freeka</strong> Anda</p>
        </div>

        <!-- Form Login -->
        <form action="/login" method="POST" class="space-y-5">
            @csrf
            @if (session('status'))
                <div class="mb-5 relative flex items-start gap-3 p-4 rounded-lg shadow-md border border-purple-300 bg-gradient-to-r from-purple-50 via-pink-50 to-orange-50 text-purple-800"
                    role="alert">
                    <i class="ph ph-check-circle text-xl mt-0.5 text-purple-600"></i>
                    <div class="text-sm font-medium">
                        <strong class="block font-semibold">Berhasil!</strong>
                        <span>{{ session('status') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()"
                        class="absolute top-2 right-3 text-purple-500 hover:text-purple-700">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
            @endif
            @if (session('gagal'))
                <div class="mb-5 relative flex items-start gap-3 p-4 rounded-lg border-l-4 border-pink-500 shadow-lg bg-gradient-to-r from-red-100 via-pink-100 to-orange-100 text-red-800 animate-fade-in ring-1 ring-pink-300"
                    role="alert">
                    <i class="ph ph-warning-circle text-2xl mt-0.5 text-pink-600 animate-pulse"></i>
                    <div class="text-sm font-medium">
                        <strong class="block font-bold text-pink-700">Oops! Login Gagal</strong>
                        <span class="text-red-700">{{ session('gagal') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()"
                        class="absolute top-2 right-3 text-pink-500 hover:text-pink-700 transition">
                        <i class="ph ph-x"></i>
                    </button>
                </div>

                <style>
                    @keyframes fade-in {
                        from {
                            opacity: 0;
                            transform: translateY(-0.5rem);
                        }

                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    .animate-fade-in {
                        animation: fade-in 0.4s ease-out;
                    }
                </style>
            @endif

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 transition" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 transition" />
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox text-pink-500 focus:ring-pink-400">
                    <span class="ml-2 text-gray-600">Ingat saya</span>
                </label>
                <a href="/forgot-password" class="text-pink-500 hover:underline">Lupa Sandi?</a>
            </div>

            <button type="submit"
                class="w-full hat-accent-btn font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                Masuk
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Belum punya akun?
            <a href="/register" class="text-pink-500 hover:underline font-medium">Daftar Sekarang</a>
        </p>
    </div>

</body>

</html>
