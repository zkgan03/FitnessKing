{{-- 

  Author : GAN ZHI KEN

--}}
<div class="relative flex h-8 justify-center">

    {{-- Search Bar --}}
    <input
        type="text"
        name="q"
        class="w-96 rounded-lg bg-darkSurface-500 px-5 pr-16 placeholder-opacity-50 focus:outline-none focus:ring-1 focus:ring-primary-600"
        placeholder="Search Class"
        autocomplete="off"
    >

    {{-- Search Button --}}
    <button
        type="submit"
        class="absolute bottom-0 right-0 top-0 w-16 rounded-lg px-5 transition duration-150 hover:text-primary-600"
    >
        <svg
            class=""
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 20 20"
        >
            <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
            />
        </svg>
    </button>
</div>
