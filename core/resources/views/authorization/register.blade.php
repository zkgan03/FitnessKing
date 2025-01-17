<x-layout>
    <div class="bg-cover bg-center" style="background-image: url('{{ Vite::asset('resources/images/acc-img.png')}}')">
        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-primary-600">Account Registration</h2>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6" action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="sm:col-span-3">
                        <label for="first-name" class="block text-sm font-bold leading-6 text-white">First name</label>
                        <div class="mt-2">
                            <input type="text" name="first_name" id="first-name" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="last-name" class="block text-sm font-bold leading-6 text-white">Last name</label>
                        <div class="mt-2">
                            <input type="text" name="last_name" id="last-name" autocomplete="family-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <fieldset>
                        <legend class="text-sm font-semibold leading-6 text-white">Gender</legend>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center gap-x-3">
                                <input id="male" name="gender" type="radio" value="M" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600">
                                <label for="male" class="block text-sm font-medium leading-6 text-white">Male</label>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <input id="female" name="gender" type="radio" value="F" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600">
                                <label for="female" class="block text-sm font-medium leading-6 text-white">Female</label>
                            </div>
                        </div>
                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <div>
                        <label for="email" class="block text-sm font-bold leading-6 text-white">Email address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-bold leading-6 text-white">Phone number</label>
                        <div class="mt-2">
                            <input id="phone_number" name="phone_number" type="tel" autocomplete="tel" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                            @error('phone_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-bold leading-6 text-white">Birth Date</label>
                        <div class="mt-2">
                            <input id="birth_date" name="birth_date" type="date" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold leading-6 text-white">Password</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold leading-6 text-white">Confirm Password</label>
                        <div class="mt-2">
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                            @error('password_confirmation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Display CAPTCHA -->
                    <div class="form-group">
                        <label for="captcha" class="block text-sm font-bold leading-6 text-white">Captcha</label>
                        <div>
                            {!! $captchaSvg !!} <!-- Display CAPTCHA SVG -->
                        </div>
                        <input type="hidden" name="captchaText" value="{{ $captchaText }}"> <!-- Hidden CAPTCHA text -->
                        <input type="text" name="inputText" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 form-control @error('captcha') is-invalid @enderror">
                        
                        @error('captcha')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">Register</button>
                    </div>
                </form>

                <p class="mt-10 text-center text-sm text-gray-500">
                    Already registered?
                    <a href="{{ route('login') }}" class="font-semibold leading-6 text-primary-600 hover:text-primary-500">Login here!</a>
                </p>
            </div>
        </div>
    </div>
</x-layout>

