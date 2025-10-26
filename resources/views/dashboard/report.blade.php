<x-layouts.app>
    <x-slot name="title">
        Laporan Keuangan Mushola
    </x-slot>

    <div class="container mx-auto px-4 py-8 space-y-10">

        <!-- Header Section -->
        <div class="flex items-center justify-center gap-4">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    🕌 LAPORAN KEUANGAN MUSHOLA
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

        <div>
            <livewire:dashboard.report.summary-cards-section />
        </div>

        <!-- Income Table -->
        <div>
            <livewire:dashboard.report.income-table />
        </div>

        <!-- Expense Table -->
        <div>
            <livewire:dashboard.report.expense-table />
        </div>

        <!-- Signature Section -->
        <div class="bg-white print:shadow-none">
            <div class="flex justify-between items-center gap-8 text-center">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-24">Dibuat Oleh,</p>
                    <div class="border-t border-gray-300 dark:border-gray-600 pt-2">
                        <p class="font-semibold text-gray-900 dark:text-white">Bendahara</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-24">Mengetahui,</p>
                    <div class="border-t border-gray-300 dark:border-gray-600 pt-2">
                        <p class="font-semibold text-gray-900 dark:text-white">Pengurus</p>
                    </div>
                </div>
            </div>
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