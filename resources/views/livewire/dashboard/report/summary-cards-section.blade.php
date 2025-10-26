 <div class="grid grid-cols-3 gap-4 ">
     <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-6 border border-green-200 dark:border-green-700">
         <div class="flex items-center justify-between">
             <div>
                 <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Pemasukan</p>
                 <p class="text-2xl font-bold text-green-700 dark:text-green-300 mt-2">
                     Rp {{ number_format($this->transactionIncomeQuery()->sum('amount'), 0, ',', '.') }}
                 </p>
                 <p class="text-xs text-green-600 dark:text-green-500 mt-1">{{ $this->transactionIncomeQuery()->count() }} transaksi</p>
             </div>
             <div class="text-4xl">💰</div>
         </div>
     </div>

     <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-6 border border-red-200 dark:border-red-700">
         <div class="flex items-center justify-between">
             <div>
                 <p class="text-sm font-medium text-red-600 dark:text-red-400">Total Pengeluaran</p>
                 <p class="text-2xl font-bold text-red-700 dark:text-red-300 mt-2">
                     Rp {{ number_format($this->transactionExpenseQuery()->sum('amount'), 0, ',', '.') }}
                 </p>
                 <p class="text-xs text-red-600 dark:text-red-500 mt-1">{{ $this->transactionExpenseQuery()->count() }} transaksi</p>
             </div>
             <div class="text-4xl">💸</div>
         </div>
     </div>

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