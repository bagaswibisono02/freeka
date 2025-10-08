<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - HatMart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .hat-gradient {
            background: linear-gradient(to right, #8b5cf6, #ec4899);
        }

        .hat-gradient-hover:hover {
            background: linear-gradient(to right, #7c3aed, #f43f5e);
        }

        .hat-text {
            background: linear-gradient(to right, #8b5cf6, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white shadow-xl rounded-xl p-6 sm:p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold hat-text">Reset Password</h2>
            <p class="text-sm text-gray-600 mt-1">Masukkan password baru Anda di bawah ini.</p>
        </div>

        @if (session('status'))
            <div class="mb-5 relative flex items-start gap-3 p-4 rounded-lg shadow-md border border-purple-300 bg-gradient-to-r from-purple-50 via-pink-50 to-orange-50 text-purple-800"
                role="alert">
                <i class="ph ph-check-circle text-xl mt-0.5 text-purple-600"></i>
                <div class="text-sm font-medium">
                    <strong class="block font-semibold">Berhasil!</strong>
                    <span>{{ session('status') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="absolute top-2 right-3 text-purple-500 hover:text-purple-700">
                    <i class="ph ph-x"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-50 p-3 rounded-md border border-red-300">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" onsubmit="handleSubmit(this)" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password" required autofocus
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-400"
                    placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-400"
                    placeholder="Ulangi password">
            </div>

            <button id="submitBtn" type="submit"
                class="w-full py-2 font-semibold rounded hat-gradient text-white hat-gradient-hover transition duration-200 flex items-center justify-center gap-2">
                <span id="submitBtnText">Reset Password</span>
                <svg id="spinner" class="hidden w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z" />
                </svg>
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Kembali ke <a href="{{ route('login') }}" class="text-pink-500 hover:underline">Login</a>
        </p>
    </div>

    <script>
        function handleSubmit(form) {
            const btn = form.querySelector('#submitBtn');
            const text = form.querySelector('#submitBtnText');
            const spinner = form.querySelector('#spinner');

            btn.disabled = true;
            text.textContent = 'Memproses...';
            spinner.classList.remove('hidden');
        }
    </script>

</body>

</html>
