<div class="min-h-screen flex items-center ">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    🕌 Donasi Mushola
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Berbagi kebaikan untuk pembangunan dan operasional mushola
                </p>
            </div>

        </div>

        <form wire:submit="create">
            {{ $this->form }}

            <x-filament::button type='submit' class="mt-6 w-full">
                🎁 Donasi Sekarang
            </x-filament::button>
        </form>

        <x-filament-actions::modals />
    </div>
</div>

@script
<script>
    document.documentElement.classList.remove('dark');
</script>
@endscript