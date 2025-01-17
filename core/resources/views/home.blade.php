<x-layout>
    <div
        class="h-[80vh] bg-cover bg-center"
        style="background-image: url('{{ Vite::asset("resources/images/home-img.jpg") }}')"
    >

        <div class="just flex h-full flex-col items-center justify-center gap-20">
            <span class="text-6xl font-bold">Where Strength Meets Majesty</span>
            <a href="{{ route("class_package.index") }}">
                <x-primary-button class="px-16 py-5 text-xl">
                    Start Today
                </x-primary-button>
            </a>
        </div>
    </div>

</x-layout>
