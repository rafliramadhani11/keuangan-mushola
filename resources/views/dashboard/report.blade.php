<x-layouts.app>
    <x-slot name="title">
        Laporan Keuangan Mushola
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-6 print:shadow-none">
                <div class="text-center border-b-2 border-gray-200 dark:border-gray-700 pb-6">
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

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <!-- Total Pemasukan -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-6 border border-green-200 dark:border-green-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Pemasukan</p>
                                <p class="text-2xl font-bold text-green-700 dark:text-green-300 mt-2">
                                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-green-600 dark:text-green-500 mt-1">{{ $incomeCount }} transaksi</p>
                            </div>
                            <div class="text-4xl">💰</div>
                        </div>
                    </div>

                    <!-- Total Pengeluaran -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-6 border border-red-200 dark:border-red-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-red-600 dark:text-red-400">Total Pengeluaran</p>
                                <p class="text-2xl font-bold text-red-700 dark:text-red-300 mt-2">
                                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-red-600 dark:text-red-500 mt-1">{{ $expenseCount }} transaksi</p>
                            </div>
                            <div class="text-4xl">💸</div>
                        </div>
                    </div>

                    <!-- Saldo -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-6 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Saldo</p>
                                <p class="text-2xl font-bold text-blue-700 dark:text-blue-300 mt-2">
                                    Rp {{ number_format($balance, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-blue-600 dark:text-blue-500 mt-1">
                                    {{ $balance >= 0 ? 'Surplus' : 'Defisit' }}
                                </p>
                            </div>
                            <div class="text-4xl">💵</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income Transactions Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-6 print:shadow-none print:break-inside-avoid">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="text-2xl mr-2">📈</span>
                    Rincian Pemasukan
                </h2>

                @if($incomeTransactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Donatur</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metode</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($incomeTransactions as $index => $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $transaction->donor?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $transaction->category?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ Str::limit($transaction->desc, 30) }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full
                                                {{ $transaction->payment_method === 'cash' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                                {{ $transaction->payment_method === 'transfer' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                                {{ $transaction->payment_method === 'payment_gateway' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : '' }}">
                                        {{ ucfirst($transaction->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-semibold text-green-600 dark:text-green-400">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-green-50 dark:bg-green-900/20">
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white text-right">
                                    TOTAL PEMASUKAN:
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-right text-green-600 dark:text-green-400">
                                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">Tidak ada data pemasukan pada periode ini.</p>
                @endif
            </div>

            <!-- Expense Transactions Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-6 print:shadow-none print:break-inside-avoid">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="text-2xl mr-2">📉</span>
                    Rincian Pengeluaran
                </h2>

                @if($expenseTransactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metode</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Penanggung Jawab</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($expenseTransactions as $index => $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $transaction->category?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ Str::limit($transaction->desc, 30) }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full
                                                {{ $transaction->payment_method === 'cash' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                                {{ $transaction->payment_method === 'transfer' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}">
                                        {{ ucfirst($transaction->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $transaction->user?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-semibold text-red-600 dark:text-red-400">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-red-50 dark:bg-red-900/20">
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white text-right">
                                    TOTAL PENGELUARAN:
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-right text-red-600 dark:text-red-400">
                                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">Tidak ada data pengeluaran pada periode ini.</p>
                @endif
            </div>

            <!-- Footer / Signature Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 print:shadow-none">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-12">Dibuat Oleh,</p>
                        <div class="border-t border-gray-300 dark:border-gray-600 pt-2">
                            <p class="font-semibold text-gray-900 dark:text-white">Bendahara</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-12">Diperiksa Oleh,</p>
                        <div class="border-t border-gray-300 dark:border-gray-600 pt-2">
                            <p class="font-semibold text-gray-900 dark:text-white">Ketua Takmir</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-12">Mengetahui,</p>
                        <div class="border-t border-gray-300 dark:border-gray-600 pt-2">
                            <p class="font-semibold text-gray-900 dark:text-white">Pengurus</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Print Button -->
            <div class="mt-6 text-center print:hidden">
                <button onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">
                    🖨️ Cetak / Download PDF
                </button>
                <a href="{{ url()->previous() }}"
                    class="ml-4 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition inline-block">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .print\:shadow-none {
                box-shadow: none !important;
            }

            .print\:hidden {
                display: none !important;
            }

            .print\:break-inside-avoid {
                break-inside: avoid;
            }

            @page {
                margin: 1cm;
            }
        }
    </style>
</x-layouts.app>