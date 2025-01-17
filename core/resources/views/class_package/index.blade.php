{{-- 

  Author : GAN ZHI KEN

--}}
<x-layout>
    <div
        class="h-[30vh] bg-cover"
        style="background-image: url('{{ Vite::asset('resources/images/classes-img.png') }}')"
    >
        <div class="flex h-full items-center justify-center">
            <span class="text-6xl font-bold">Rule Your Body, Rule Your Life</span>
        </div>
    </div>

    {!! $content !!}

    @push('scripts')
    @endPush
</x-layout>
