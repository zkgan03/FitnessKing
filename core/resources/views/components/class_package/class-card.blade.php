{{-- 

  Author : GAN ZHI KEN

--}}
@props([
    'class' => ' ',
    'href' => '#',
    'imageSrc' => Vite::asset('resources/images/home-img.jpg'),
    'packageName' => 'Package Name',
    'desc' =>
        'Package Description - Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore exercitationem beatae illo molestias, explicabo assumenda, ecusandae repellendus? Incidunt porro vitae voluptatibus. Aliquid maxime asperiores unde exercitationem debitis excepturi nulla rerum, quo quia. Dolorem voluptate repudiandae aspernatur aperiam, perspiciatis harum! Quia illum repudiandae nisi neque alias animi voluptate! Sunt, magnam id modi dolor ',
    'price' => '59.99',
])

<div class="{{ $class }} w-1/4 rounded-xl bg-darkSurface-500">
    {{-- img --}}
    <div>
        <img
            class="size-full rounded-t-xl object-cover object-center"
            src="{{ $imageSrc }}"
            alt=""
        >
    </div>

    <div class="mt-3 flex flex-col gap-3 px-7 pb-4">
        {{-- title --}}
        <div class="font-bold lg:text-lg xl:text-2xl">
            {{ __($packageName) }}
        </div>

        {{-- desc --}}
        <div class="leading-5 lg:text-sm">
            {{ __($desc) }}
        </div>

        {{-- price --}}
        <div class="font-bold lg:text-lg xl:text-2xl">
            {{ __("RM $price ") }}
        </div>

        {{-- learn more button --}}
        <div class="">
            <a href="{{ $href }}">
                <x-primary-button class="rounded-md text-sm">
                    {{ __('Learn More') }}
                </x-primary-button>
            </a>
        </div>
    </div>

</div>
