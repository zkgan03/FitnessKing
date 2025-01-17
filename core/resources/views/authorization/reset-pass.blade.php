<x-layout>
    <div
        class="h-[80vh] bg-cover bg-center"
        style="background-image: url('{{ Vite::asset('resources/images/acc-img.png') }}')"
    >
        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-primary-600">Forgot
                    password?</h2>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form
                    class="space-y-6"
                    action="{{ route('reset-pass') }}"
                    method="POST"
                >
                    @csrf
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-bold leading-6 text-white"
                        >Please enter your email you used to register the account for.</label>
                        <div class="mt-2">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            >
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >Send</button>
            </div>
            </form>
        </div>
    </div>
    </div>
</x-layout>
