<x-layouts.app>
    <x-slot name="title">
        Pembayaran Berhasil
    </x-slot>

    <div class="min-h-screen flex flex-col items-center justify-center">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between max-w-3xl w-full mx-auto">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    🕌 Donasi Mushola
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Berbagi kebaikan untuk pembangunan dan operasional mushola
                </p>
            </div>
        </div>

        <x-filament::section class="max-w-3xl w-full mx-auto">
            <!-- Title -->
            <div class="flex items-center justify-center">
                <h1 class="text-3xl font-bold text-gray-800">
                    Pembayaran Berhasil! 🎉
                </h1>
                <div>
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Message -->
            <p class="text-gray-600">
                Terima kasih atas donasi Anda untuk Mushola.
                Semoga menjadi amal jariyah yang bermanfaat.
            </p>

            <!-- Buttons -->
            <div class="flex justify-end mt-3">
                <div class="flex items-center justify-between gap-x-6">
                    <x-filament::button
                        href="{{ route('donation.index') }}"
                        tag="a">
                        Donasi Lagi
                    </x-filament::button>

                    <x-filament::button
                        href="/"
                        tag="a">
                        Kembali ke Beranda
                    </x-filament::button>

                </div>
            </div>
        </x-filament::section>

    </div>
</x-layouts.app>