<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>{{ config('app.name', 'Fitness KING') }}</title>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')

    {{-- Styles for chatbot typing indicator --}}
    <style>
        @keyframes blink {
            50% {
                opacity: 1;
            }
        }

        .typing-indicator span:nth-of-type(1) {
            animation: 1s blink infinite 0.3333s;
        }

        .typing-indicator span:nth-of-type(2) {
            animation: 1s blink infinite 0.6666s;
        }

        .typing-indicator span:nth-of-type(3) {
            animation: 1s blink infinite 0.9999s;
        }
    </style>

</head>

<body class="antialiase *:text-[#EEEEEE] bg-darkSurface-600 font-inter">
    <x-navigation />

    @if (session('success'))
        <div class="mb-4 bg-green-500 px-4 py-2 text-white">
            {{ session('success') }}
            <button
                class="float-right"
                onclick="this.parentElement.style.display='none'"
            >Close</button>
        </div>
    @elseif (session('error'))
        <div class="mb-4 bg-red-500 px-4 py-2 text-white">
            {{ session('error') }}
            <button
                class="float-right"
                onclick="this.parentElement.style.display='none'"
            >Close</button>
        </div>
    @endif

    <main {{ $attributes }}>
        {{ $slot }}
    </main>


    <x-footer />

    <livewire:chatbot />


    <!-- Scripts -->
    @stack('scripts')
</body>

</html>
