<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - HatMart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .hat-purple-gradient {
            background: linear-gradient(to right, #8b5cf6, #ec4899);
        }

        .hat-purple-text {
            background: linear-gradient(to right, #8b5cf6, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hat-purple-btn {
            background: linear-gradient(to right, #8b5cf6, #ec4899);
            color: white;
        }

        .hat-purple-btn:hover {
            background: linear-gradient(to right, #a78bfa, #f472b6);
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
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
</head>

<body class="bg-gradient-to-br from-purple-50 via-pink-50 to-white min-h-screen flex items-center justify-center px-4">
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md animate-fade-in">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-extrabold hat-purple-text">Daftar Freeka</h1>
            <p class="text-sm text-gray-500">Buat akun baru dan mulai belanja!</p>
        </div>

        @if (session('gagal'))
            <div
                class="mb-5 relative flex items-start gap-3 p-4 rounded-lg shadow-md border border-pink-200 bg-gradient-to-r from-purple-100 via-pink-100 to-white text-pink-800 animate-fade-in">
                <i class="ph ph-warning-circle text-2xl mt-0.5 text-pink-600 animate-pulse"></i>
                <div class="text-sm font-medium">
                    <strong class="block font-bold text-pink-700">Gagal Mendaftar!</strong>
                    <span>{{ session('gagal') }}</span>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="absolute top-2 right-3 text-pink-500 hover:text-pink-700">
                    <i class="ph ph-x"></i>
                </button>
            </div>
        @endif

        <form action="/register" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
                <input id="email" name="email" type="text" value="{{ old('email') }}"
                    placeholder="email@example.com"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                @error('email')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="contact">Nomor Kontak</label>
                <input id="contact" name="contact" type="text" value="{{ old('contact') }}"
                    placeholder="08xxxxxxxx"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                @error('contact')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>
            <div class="mt-4">
                <label for="referral_code" class="block font-medium text-sm text-gray-700">
                    Kode Referral (opsional)
                </label>
                <input id="referral_code" type="text" name="referral_code"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                @error('password')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="password2">Konfirmasi Password</label>
                <input id="password2" name="password2" type="password" placeholder="Ulangi password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="captcha">Captcha</label>
                <div class="flex items-center gap-4">
                    <img src="{{ captcha_src() }}" id="captcha" alt="captcha"
                        class="cursor-pointer border rounded w-28 h-12 object-contain" onclick="refreshCaptcha()">
                    <input type="text" id="captcha_input" name="captcha"
                        class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400"
                        required>
                </div>
                @error('captcha')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit"
                class="w-full hat-purple-btn font-semibold py-2 px-4 rounded-lg shadow transition duration-200">
                Daftar
            </button>

            <div class="text-sm text-center text-gray-500 mt-4">
                Sudah punya akun?
                <a href="/login" class="text-purple-600 font-medium hover:underline">Login di sini</a>
            </div>
        </form>
    </div>

    <script>
        function refreshCaptcha() {
            document.getElementById('captcha').src = "{{ captcha_src() }}" + "?" + Math.random();
        }
    </script>
</body>

</html>
