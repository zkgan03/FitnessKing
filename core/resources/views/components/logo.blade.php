@php
    $classes = 'text-3xl font-bold italic text-primary-600';
@endphp

<h1 {{ $attributes->merge(['class' => $classes]) }}>Fitness KING</h1>
