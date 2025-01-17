{{-- 

  Author : GAN ZHI KEN

--}}
<x-layout>
    {{-- @php
        $test = '<script>
            alert("Hello World");
        </script>';
    @endphp

    {{ $test }} --}}

    <div
        class="h-[30vh] bg-cover"
        style="background-image: url('{{ Vite::asset('resources/images/classes-img.png') }}')"
    >
        <div class="flex h-full items-center justify-center">
            <span class="text-6xl font-bold">Build Your Strength, Claim Your Throne</span>
        </div>
    </div>


    {{-- Header / Package Name --}}
    <h2 class="mt-20 px-28 text-4xl font-bold">{{ $classPackage->package_name }}</h2>

    {{-- Package Details container --}}
    <div class="px-32 py-10">
        {{-- Summary and enroll button --}}
        <div class="flex flex-col gap-5 rounded-xl bg-darkMixed-500 px-10 py-6">

            <div class="text-2xl font-bold">{{ __('Classes Summary') }}</div>

            {{-- Summary Table --}}
            <div class="w-full">
                <x-class_package.class-summary-table
                    :classes="$classes"
                    :classPackage="$classPackage"
                />
            </div>

            {{-- Enroll Button --}}
            <div class="flex items-center justify-between gap-2">

                <div>
                    {{-- General info of package --}}
                    <div class="flex flex-col">
                        {{-- Totol Classes --}}
                        <div>{{ __('Total Classes : ' . count($classes)) }} </div>

                        {{-- Slot Available --}}
                        <div>{{ __("Slot Available : $slotAvailable (max : $classPackage->max_capacity)") }}</div>

                    </div>

                    {{-- Price --}}
                    <div class="mt-5 text-2xl font-bold">
                        {{ __('RM ' . number_format($classPackage->price, 2)) }}
                    </div>
                </div>
                {{-- Enrolled or Unenrolled button --}}
                <div>

                    @php
                        $isStarted = Carbon\Carbon::parse($classPackage->start_date, 'Asia/Kuala_Lumpur')->isPast();
                    @endphp
                    @if ($isStarted)
                        {{-- If the class package is started, do not show the enroll or unenroll button --}}
                        <div class="gap2 flex flex-col font-bold text-yellow-400">
                            <div>
                                {{ __('Class has started / ended') }}
                            </div>
                            <div>
                                {{ __('You are not able to enroll or unenroll') }}
                            </div>
                        </div>
                    @elseif ($isEnrolled)
                        {{-- If enrolled, but havnt started, allow user to unenroll --}}
                        <div class="flex items-end gap-3 p-2">

                            {{-- Message --}}
                            <div class="ml-auto text-right text-sm">
                                <div>
                                    {{ __('You have enrolled this package!') }}
                                </div>
                                <div>
                                    {{ __('Click the button to unenroll') }}
                                </div>
                                <div class="mt-2 text-lg font-bold text-yellow-400">
                                    {{ __('NOTE : Not refundable') }}
                                </div>
                            </div>

                            {{-- unenroll button --}}
                            <div>
                                <x-danger-button
                                    id="unenroll-button"
                                    type="button"
                                    class="w-full self-center text-sm"
                                    value="{{ $classPackage->package_id }}"
                                >
                                    {{ __('Unenroll') }}
                                </x-danger-button>
                            </div>

                            {{-- Dialog Container --}}
                            <div
                                id="unenroll-dialog-container"
                                class="confirm-dialog fixed inset-0 z-50 hidden items-center justify-center backdrop-blur"
                            >
                                {{-- Model --}}
                                <div
                                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                    aria-hidden="true"
                                ></div>

                                {{-- main content --}}
                                <div class="relative flex min-h-screen items-center justify-center">
                                    <div
                                        class="flex min-h-[10em] w-[30em] flex-col justify-between gap-4 rounded-lg bg-darkSurface-500 p-4 shadow-lg">

                                        {{-- Title and text --}}
                                        <div class="text-left">
                                            <div class="text-lg font-bold">
                                                {{ __('Are you confirm to unenroll?') }}
                                            </div>

                                            <div class="mt-2 text-sm text-yellow-400">
                                                {{ __('NOTE : Not refundable') }}
                                            </div>
                                        </div>

                                        {{-- Buttons --}}
                                        <div class="flex gap-5 text-center">
                                            <form
                                                class="ml-auto w-fit"
                                                action="{{ route('class_packages.unenroll') }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button
                                                    type="submit"
                                                    class="w-full self-center text-sm"
                                                    name="package_id"
                                                    value="{{ $classPackage->package_id }}"
                                                >
                                                    {{ __('Confirm') }}
                                                </x-danger-button>
                                            </form>
                                            <button
                                                id="cancel-unenroll-button"
                                                class="inline-block rounded-lg bg-darkSurface-200 px-4 py-2 text-sm font-semibold hover:opacity-80"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        @pushonce('scripts')
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    //close and open dialog when unenroll button is clicked
                                    const unenrollButton = document.querySelector('#unenroll-button');
                                    const unenrollDialogContainer = document.querySelector('#unenroll-dialog-container');
                                    const cancelUnenrollButton = document.querySelector('#cancel-unenroll-button');
                                    const unenrollForm = document.querySelector('#unenroll-form');
                                    const packageIdInput = document.querySelector('#package_id');

                                    unenrollButton.addEventListener('click', function() {
                                        unenrollDialogContainer.classList.remove('hidden');
                                        unenrollDialogContainer.classList.add('flex');
                                    });

                                    cancelUnenrollButton.addEventListener('click', function() {
                                        unenrollDialogContainer.classList.add('hidden');
                                        unenrollDialogContainer.classList.remove('flex');
                                    });
                                });
                            </script>
                        @endpushonce
                    @elseif($slotAvailable == 0)
                        {{-- If the class package is full, do not show the enroll button --}}
                        <div class="gap2 flex flex-col font-bold text-yellow-400">
                            <div>
                                {{ __('Class is fulled') }}
                            </div>
                            <div>
                                {{ __('You are not able to enroll ') }}
                            </div>
                        </div>
                    @else
                        {{-- Show enroll button --}}
                        <a href="{{ route('payment.index', $classPackage->package_id) }}">
                            <x-primary-button
                                type="submit"
                                class="w-full self-center text-lg"
                            >
                                {{ __('Enroll Now') }}
                            </x-primary-button>
                        </a>
                    @endif
                </div>
            </div>
        </div>


        {{-- class details --}}
        <div class="mt-20 flex flex-col gap-5 p-10">
            @foreach ($classes as $class)
                <x-class_package.class-details :classDetails="$class" />
            @endforeach
        </div>
    </div>
</x-layout>
