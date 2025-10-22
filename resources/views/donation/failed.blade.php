<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Gagal</title>
    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
                <!-- Error Icon -->
                <div class="mx-auto w-16 h-16 bg-danger-100 dark:bg-danger-900/20 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Pembayaran Gagal
                </h1>

                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Pembayaran Anda tidak dapat diproses. Silakan coba lagi atau hubungi kami jika masalah berlanjut.
                </p>

                <div class="space-y-3">
                    <a href="{{ route('donation') }}"
                        class="block w-full px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition">
                        Coba Lagi
                    </a>
                    <a href="/"
                        class="block w-full px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium rounded-lg transition">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    @filamentScripts
    @vite('resources/js/app.js')
</body>

</html>