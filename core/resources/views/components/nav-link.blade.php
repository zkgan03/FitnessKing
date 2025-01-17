@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'mx-2 tracking-wider underline-offset-4 inline-flex cursor-pointer text-primary-600 underline '
            : 'mx-2 tracking-wider underline-offset-4 inline-flex cursor-pointer hover:text-primary-600 hover:underline decoration-transparent hover:decoration-current transition ease-in-out duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
