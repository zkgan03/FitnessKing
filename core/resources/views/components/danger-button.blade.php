<button
    {{ $attributes->merge([
        'class' => 'inline-flex px-4 py-2 justify-center
                            rounded-md font-semibold 
                            text-white
                            uppercase tracking-widest 
                            bg-yellow-500 hover:bg-opacity-80
                            focus:outline-none focus:ring-2 
                            focus:ring-white
                            transition ease-in-out duration-150',
    ]) }}
>
    {{ $slot }}
</button>
