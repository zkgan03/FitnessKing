{{-- 

  Author : GAN ZHI KEN

--}}
@props([
    'isUser' => true,
])

@php
    $classes = $isUser ? 'bg-primary-600 text-white self-end' : 'bg-gray-100 text-black self-start ';
@endphp

<div {{ $attributes->merge(['class' => "inline py-2 px-3 rounded max-w-[70%] m-2 break-words {$classes}"]) }}">
    {{-- Since we need to preserve newline or any sensitive value, we need below function --}}
    {{-- nl2br() : change '\n' to <br> element --}}
    {{-- e() :  runs htmlentities over the given string / escapes any HTML special characters in the content to prevent XSS attacks --}}
    {!! nl2br(e($slot)) !!}
</div>
