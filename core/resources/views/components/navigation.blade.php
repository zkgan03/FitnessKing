<nav {{ $attributes->merge(['class' => 'sticky top-0 z-50 w-full']) }}>
    <div class="flex h-16 items-center justify-around bg-darkSurface-600">
        <x-nav-link
            class="hover:no-underline"
            :href="route('home')"
        >
            <x-logo />
        </x-nav-link>

        <div class="">
            <x-nav-link
                :href="route('home')"
                :active="request()->routeIs('home')"
            >
                Home
            </x-nav-link>

            <x-nav-link
                :href="route('class_package.index')"
                :active="Request::is('class-package*')"
            >
                Classes
            </x-nav-link>

            @if (Auth::guard('customer')->check())
            <x-nav-link
                :href="route('schedule.index')"
                :active="Request::is('schedule*')"
            >
                Schedule
            </x-nav-link>
            @endif
            

            @if (Auth::guard('coach')->check())
            <!-- class schedule module, after coach logged in -->
            <x-nav-link :href="route('timetable.index')">
                Timetable
            </x-nav-link>
            <x-nav-link :href="route('attendance.index')">
                Attendance
            </x-nav-link>
            @endif
        </div>

        <div>
            @if (!Auth::guard('customer')->check() && !Auth::guard('coach')->check())
            <x-nav-link :href="route('register')">
                Register
            </x-nav-link>
            <x-nav-link :href="route('login')">
                Log In
            </x-nav-link>
            @endif
            
            @if (Auth::guard('customer')->check() || Auth::guard('coach')->check())
            @php
            if (Auth::guard('customer')->check()) {
                $user = Auth::guard('customer')->user();
            }
            elseif (Auth::guard('coach')->check()) {
                $user = Auth::guard('coach')->user();
            }
            @endphp 
            <div class="relative ml-3">
                <div>
                    <button type="button" class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="absolute -inset-1.5"></span>
                        <span class="sr-only">Open user menu</span>

                        @if ($user->profile_pic === NULL)
                        <svg class="h-8 w-8 rounded-full" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                        </svg>
                        @else
                        <img class="h-8 w-8 rounded-full" src="{{ $user->profile_pic }}" alt="">
                        @endif
                    </button>
                </div>

                <!-- Dropdown menu -->
                <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-darkSurface-400 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden" id="user-menu" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                    <x-nav-link :href="route('account')" class="block px-4 py-2" role="menuitem" tabindex="-1" id="user-menu-item-0">
                        Profile
                    </x-nav-link>
                    @if (Auth::guard('customer')->check())
                    <x-nav-link :href="route('coach-exam')" class="block px-4 py-2" role="menuitem" tabindex="-1" id="user-menu-item-1">
                        Join as Coach
                    </x-nav-link>
                    @endif
                    <x-nav-link :href="route('logout.page')" class="block px-4 py-2" role="menuitem" tabindex="-1" id="user-menu-item-2">
                        Log Out
                    </x-nav-link>
                </div>
            </div>
            @endif
        </div>
    </div>
</nav>

<script>
    // Get the button and menu elements
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu');

    // Add a click event listener to the button
    userMenuButton.addEventListener('click', function() {
        // Toggle the hidden class to show/hide the menu
        userMenu.classList.toggle('hidden');
    });

    // Optionally, add a click outside functionality to close the menu when clicking outside
    window.addEventListener('click', function(event) {
        if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
            userMenu.classList.add('hidden');
        }
    });
</script>

{{--
@push('scripts')
<script>
    window.addEventListener('scroll', function () {
        let header = document.querySelector('nav');
        header.classList.toggle('fixed', window.scrollY > 0);
        header.classList.toggle('absolute', window.scrollY <= 0);
    });
</script>
@endpush

--}}
