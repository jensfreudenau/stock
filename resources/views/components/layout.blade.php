<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<html x-data="{ darkMode: localStorage.getItem('dark') === 'true'}"
      x-init="$watch('darkMode', val => localStorage.setItem('dark', val))"
      x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    @stack('scripts')
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans antialiased" x-data="{ open: false }">
<button
    @click="open = !open"
    class="p-4 md:hidden focus:outline-none z-40"
>
    <svg
        class="w-6 h-6 text-gray-800"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16"
        ></path>
    </svg>
</button>
<div class="flex gap-8 bg-white dark:bg-gray-900" >
    <x-sidebar />
    <main class="mt-4 px-4">
        <div class="block sm:absolute top-5 right-8 order-1">
            <x-dark-mode-toggle size="4"/>
        </div>
        {{ $slot }}
        <x-footer/>
    </main>
</div>
@stack('js_after')
</body>

</html>

