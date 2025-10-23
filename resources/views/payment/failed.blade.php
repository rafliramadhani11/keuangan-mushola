<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Gagal</title>
    @vite(['resources/css/app.css'])
</head>

<body class="antialiased bg-gradient-to-br from-red-50 to-pink-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <!-- Error Icon -->
            <div class="mb-6">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                Pembayaran Gagal 😔
            </h1>

            <!-- Message -->
            <p class="text-gray-600 mb-8">
                Maaf, terjadi kesalahan saat memproses pembayaran Anda.
                Silakan coba lagi.
            </p>

            <!-- Possible Reasons -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 text-left">
                <p class="text-sm font-semibold text-gray-700 mb-2">Kemungkinan penyebab:</p>
                <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                    <li>Saldo e-wallet tidak mencukupi</li>
                    <li>Pembayaran dibatalkan</li>
                    <li>Waktu pembayaran habis</li>
                    <li>Koneksi internet terputus</li>
                </ul>
            </div>

            <!-- Buttons -->
            <div class="space-y-3">
                <a href="{{ route('donation.index') }}"
                    class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                    Coba Lagi
                </a>
                <a href="/"
                    class="block w-full border-2 border-gray-300 text-gray-700 font-bold py-3 rounded-lg hover:bg-gray-50 transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>

</html>