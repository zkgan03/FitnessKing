<x-layout>
    <div class="h-[80vh] bg-cover bg-center grid place-content-center"
        style="background-image: url('{{ Vite::asset('resources/images/profile-img.png')}}')">

        <div class="w-screen max-w-6xl overflow-auto rounded-3xl border-2 border-gray-600">
            <div class="group items-center gap-x-6 p-4">
                <div class="flex">
                    <h3 class="text-2xl leading-6 font-bold pr-2">Profile</h3>
                    @if(Auth::guard('customer')->check())
                        @php
                            $user = Auth::guard('customer')->user();
                            $id = $user->customer_id;
                        @endphp

                        <x-nav-link :href="route('edit', $id)" class="font-bold text-primary-600 hover:text-white focus:outline-none">
                            Edit &#128393;
                        </x-nav-link>

                    @elseif(Auth::guard('coach')->check())
                        @php
                            $coach = Auth::guard('coach')->user();
                            $id = $coach->coach_id; // Use $coach here instead of $user
                        @endphp

                        <x-nav-link :href="route('edit', $id)" class="font-bold text-primary-600 hover:text-white focus:outline-none">
                            Edit &#128393;
                        </x-nav-link>
                    @endif
                </div>

                <div class="mt-5 border-t border-gray-400">
                    
                <!-- section divider -->
                <dl class="divide-y divide-gray-600">

                <div class="col-span-full">
                <div class="mt-2 flex items-center gap-x-3">
                    @if ($user->profile_pic === NULL)
                    <svg class="h-28 w-28 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                    </svg>
                    @else
                    <img class="h-28 w-28" src="{{ $user->profile_pic }}" alt="">
                    @endif
                </div>
                </div>

                <div class="py-4 sm:grid sm:py-5 sm:grid-cols-2 sm:gap-4">
                    <dt class="text-xl font-medium text-primary-600">I am a</dt>
                    <dd class="mt-1 flex text-xl sm:mt-0 sm:col-span-2">
                    @if(Auth::guard('customer')->check())
                        @php
                            $user = Auth::guard('customer')->user();
                            $role = $user->role; // Assuming role is a direct attribute on Customer
                        @endphp

                        <span class="flex-grow">Customer</span>

                    @elseif(Auth::guard('coach')->check())
                        @php
                            $coach = Auth::guard('coach')->user();
                            $role = 'Coach'; // Set directly if role is fixed
                        @endphp

                        <span class="flex-grow">Coach</span>
                    @endif
                        <span class="ml-4 flex-shrink-0">
                        </span>
                    </dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-2 sm:gap-4">
                <dt class="text-xl font-medium text-primary-600">Full name</dt>
                <dd class="mt-1 flex text-xl sm:mt-0 sm:col-span-2">
                    <span class="flex-grow">{{ $user->first_name }} {{ $user->last_name }}</span>
                    <span class="ml-4 flex-shrink-0">
                    </span>
                </dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-2 sm:gap-4">
                <dt class="text-xl font-medium text-primary-600">Gender</dt>
                <dd class="mt-1 flex text-xl sm:mt-0 sm:col-span-2">
                    @if($user->gender === 'M')
                        <span class="flex-grow">Male</span>

                    @elseif($user->gender === 'F')
                        <span class="flex-grow">Female</span>
                    @endif
                    <span class="ml-4 flex-shrink-0">
                    </span>
                </dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-2 sm:gap-4">
                <dt class="text-xl font-medium text-primary-600">Birth Date</dt>
                <dd class="mt-1 flex text-xl sm:mt-0 sm:col-span-2">
                    <span class="flex-grow">{{ $user->birth_date }}</span>
                    <span class="ml-4 flex-shrink-0">
                    </span>
                </dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-2 sm:gap-4">
                <dt class="text-xl font-medium text-primary-600">Account Creation Date and Time</dt>
                <dd class="mt-1 flex text-xl sm:mt-0 sm:col-span-2">
                    <span class="flex-grow">{{ $user->creation_date }}</span>
                    <span class="ml-4 flex-shrink-0">
                    </span>
                </dd>
                </div>

                <div class="py-4 sm:grid sm:py-5 sm:grid-cols-2 sm:gap-4">
                <dt class="text-xl font-medium text-primary-600">Email address</dt>
                <dd class="mt-1 flex text-xl sm:mt-0 sm:col-span-2">
                    <span class="flex-grow">{{ $user->email }}</span>
                    <span class="ml-4 flex-shrink-0">
                    </span>
                </dd>
                </div>

                <div class="py-4 sm:grid sm:py-5 sm:grid-cols-2 sm:gap-4">
                <dt class="text-xl font-medium text-primary-600">Phone Number</dt>
                <dd class="mt-1 flex text-xl sm:mt-0 sm:col-span-2">
                    <span class="flex-grow">{{ $user->phone_number }}</span>
                    <span class="ml-4 flex-shrink-0">
                    </span>
                </dd>
                </div>
            </div>
        </div>

    </div>
</x-layout>