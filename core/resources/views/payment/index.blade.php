{{-- 

  Author : GAN ZHI KEN

--}}
<x-layout>

    <div class="mx-auto my-10 flex w-fit flex-row gap-5">

        <div class="w-96">
            <img
                class="size-full rounded"
                src='{{ url("storage/$package->package_image") }}'
                alt=""
            >
        </div>

        <div class="flex flex-col justify-center gap-10">
            {{-- Package name and price --}}
            <div>
                <div class="text-xl">
                    {{ __($package->package_name) }}
                </div>
                <div class="text-2xl font-bold">
                    {{ __('RM ' . number_format($package->price, 2)) }}
                </div>
            </div>

            {{-- Payment buttons --}}
            <form
                method="post"
                action="{{ route('payment.processPayment') }}"
            >
                @csrf
                <div class="flex h-full flex-col justify-center gap-2">
                    <input
                        type="hidden"
                        name="package_id"
                        value="{{ $package->package_id }}"
                    />
                    {{-- Paypal button --}}
                    <button
                        type="submit"
                        value="paypal"
                        name="payment_method"
                        class="inline-flex w-full items-center justify-center rounded bg-[#009cde] px-5 py-2.5 text-gray-200 hover:bg-[#009cde]/90 focus:outline-none focus:ring-2 focus:ring-[#009cde]/50 dark:focus:ring-[#009cde]/50"
                    >
                        <svg
                            class="size-4 -ms-1 me-2"
                            aria-hidden="true"
                            focusable="false"
                            data-prefix="fab"
                            data-icon="paypal"
                            role="img"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 384 512"
                        >
                            <path
                                fill="currentColor"
                                d="M111.4 295.9c-3.5 19.2-17.4 108.7-21.5 134-.3 1.8-1 2.5-3 2.5H12.3c-7.6 0-13.1-6.6-12.1-13.9L58.8 46.6c1.5-9.6 10.1-16.9 20-16.9 152.3 0 165.1-3.7 204 11.4 60.1 23.3 65.6 79.5 44 140.3-21.5 62.6-72.5 89.5-140.1 90.3-43.4 .7-69.5-7-75.3 24.2zM357.1 152c-1.8-1.3-2.5-1.8-3 1.3-2 11.4-5.1 22.5-8.8 33.6-39.9 113.8-150.5 103.9-204.5 103.9-6.1 0-10.1 3.3-10.9 9.4-22.6 140.4-27.1 169.7-27.1 169.7-1 7.1 3.5 12.9 10.6 12.9h63.5c8.6 0 15.7-6.3 17.4-14.9 .7-5.4-1.1 6.1 14.4-91.3 4.6-22 14.3-19.7 29.3-19.7 71 0 126.4-28.8 142.9-112.3 6.5-34.8 4.6-71.4-23.8-92.6z"
                            >
                            </path>
                        </svg>
                        Pay with PayPal
                    </button>

                    {{-- Cards button --}}
                    <button
                        type="submit"
                        value="card"
                        name="payment_method"
                        class="inline-flex w-full items-center justify-center gap-2 rounded bg-gray-200 px-5 py-2.5 text-gray-900 hover:bg-gray-300 focus:ring-2"
                    >
                        <svg
                            class="h-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 122.88 78.22"
                        >
                            <path
                                fill="currentColor"
                                d="M11.24,58h17V62.1h-17V58Zm75.4-13A9.77,9.77,0,0,1,94.51,49a9.85,9.85,0,1,1,0,11.76A9.84,9.84,0,1,1,86.64,45Zm29.48,29.29A2.94,2.94,0,0,0,119,71.48V34H3.9V71.48a2.64,2.64,0,0,0,.82,2,2.87,2.87,0,0,0,2,.85ZM6.74,78.2a6.55,6.55,0,0,1-4.76-2,6.58,6.58,0,0,1-2-4.75V6.74A6.72,6.72,0,0,1,6.74,0H116.12a6.76,6.76,0,0,1,6.76,6.74V71.48a6.68,6.68,0,0,1-2,4.75,6.81,6.81,0,0,1-4.77,2q-54.74,0-109.38,0ZM3.9,14.56H119V6.73a2.75,2.75,0,0,0-.87-2,2.81,2.81,0,0,0-2-.87H6.74a2.8,2.8,0,0,0-2,.87,2.76,2.76,0,0,0-.82,2v7.83ZM36.09,58H64.38V62.1H36.09V58Z"
                            />
                        </svg>
                        Credit Card / Debit Card
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-layout>
