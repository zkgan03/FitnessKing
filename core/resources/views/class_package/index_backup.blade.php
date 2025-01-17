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
    {{-- Container --}}
    <div class="mx-32 my-10">

        {{-- Filter and seaching --}}
        <div class="flex items-center justify-between gap-5 sm:flex-col xl:flex-row">
            {{-- Search Bar --}}
            <div>
                <x-class_package.search-bar />
            </div>

            {{-- Filter --}}
            <div class="flex items-center gap-5">
                {{-- TODO : Might need to remove the time filter and day filter due to the change of business rule --}}
                {{-- Day Filter --}}
                {{-- <x-class_package.day-filter-dropdown /> --}}

                {{-- Price (0 - 1000) --}}
                <x-class_package.double-range-slider
                    min="0"
                    max="1000"
                    :value=[0,1000]
                    label="Price"
                    unit="RM"
                    step="10"
                    type="decimal"
                />

                {{-- Time (0:00 - 23:59) --}}
                {{-- <x-class_package.double-range-slider
                    type="time"
                    label="Time"
                    min="0"
                    :max="24 * 60"
                    :value="[0, 24 * 60]"
                    unit="hh:mm"
                    step="15"
                /> --}}
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $classPackages->links() }}
        </div>

        {{-- Class Card --}}
        <div class="flex-start mt-10 flex flex-wrap justify-around gap-10">
            @foreach ($classPackages as $classPackage)
                <x-class_package.class-card
                    href='{{ route('class_package.show', $classPackage->package_id) }}'
                    :packageName="$classPackage->package_name"
                    imageSrc='{{ url("storage/$classPackage->package_image") }}'
                    :title="$classPackage->title"
                    :desc="$classPackage->description"
                    :price="$classPackage->price"
                />
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $classPackages->links() }}
        </div>
    </div>
</x-layout>
