<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Page Title' }}</title>

    {{-- Dark Mode Script - MUST be FIRST before any styling to prevent flash --}}
    <script>
        (function() {
            // Function to get system preference
            function getSystemTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            // Get theme: 1. localStorage, 2. system preference
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = getSystemTheme();
            const theme = savedTheme || systemTheme;

            // Apply theme immediately
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Listen for system theme changes (when user changes OS theme)
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                // Only apply system theme if user hasn't set a preference
                if (!localStorage.getItem('theme')) {
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }

                    // Trigger custom event to update UI
                    window.dispatchEvent(new Event('theme-changed'));
                }
            });
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    {{-- Filament Styles --}}
    @livewireStyles
    @filamentStyles

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 antialiased">
    {{ $slot }}

    <!-- Filament Scripts -->
    @filamentScripts
    @livewireScripts
</body>

</html>