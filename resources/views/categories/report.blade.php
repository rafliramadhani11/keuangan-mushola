<x-layouts.app>
    <x-slot name="title">
        Data Kategori Keuangan Mushola
    </x-slot>

    <script>
        document.documentElement.classList.remove('dark');
    </script>

    <div class="container mx-auto px-4 py-8 space-y-10">
        <!-- Header Section -->
        <div class="flex items-center justify-center gap-4">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    🕌 DATA KATEGORI KEUANGAN MUSHOLA
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                    Dicetak pada: {{ now()->format('d F Y H:i:s') }}
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-4 mb-8 print:hidden">
            <x-filament::button onclick="window.print()" color="info">
                Print Report
            </x-filament::button>
        </div>

        <!-- Category Table -->
        <div>
            <livewire:category.report.category-table :$startDate :$endDate />
        </div>

    </div>

    <style>
        @media print {
            @page {
                size: portrait;
                margin: 1cm;
            }

            html,
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .print\:hidden {
                display: none !important;
            }

            .print\:shadow-none {
                box-shadow: none !important;
            }
        }
    </style>

</x-layouts.app>