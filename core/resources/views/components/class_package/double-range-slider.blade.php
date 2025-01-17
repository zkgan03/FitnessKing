{{-- 

  Author : GAN ZHI KEN

--}}
@props([
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'value' => [0, 100],
    'label' => 'Price',
    'unit' => 'RM',
    'type' => 'decimal', //decimal, time, number
])

<div
    class="range-slider flex flex-col rounded-lg bg-darkSurface-500 px-5 py-1 ring-primary-600 focus-within:ring-1"
    data-type="{{ $type }}"
>
    {{-- Label --}}
    <span class="text-sm font-medium">{{ __("$label ($unit)") }}</span>

    {{-- slider --}}
    <div class="relative mt-2 h-2 rounded-full bg-[#DDDDDD]">
        {{-- custom slider --}}
        <div class="custom-slider absolute h-full rounded-full bg-primary-600"></div>

        <input
            type="range"
            step="{{ $step }}"
            value="{{ $value[0] }}"
            min="{{ $min }}"
            max="{{ $max }}"
            class="from-slider [&::-moz-range-thumb]:size-5 [&::-webkit-slider-thumb]:size-5 pointer-events-none absolute left-0 top-0 z-10 h-2 w-full appearance-none bg-transparent ring-[#F6A2BC] focus:z-20 [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:cursor-pointer [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-[#E21655] [&::-moz-range-thumb]:ring-offset-1 [&::-moz-range-thumb]:focus:ring-1 [&::-moz-range-thumb]:focus:ring-offset-white [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-[#E21655] [&::-webkit-slider-thumb]:ring-offset-1 [&::-webkit-slider-thumb]:focus:ring-1 [&::-webkit-slider-thumb]:focus:ring-offset-white"
        />
        <input
            type="range"
            step="{{ $step }}"
            value="{{ $value[1] }}"
            min="{{ $min }}"
            max="{{ $max }}"
            class="to-slider [&::-moz-range-thumb]:size-5 [&::-webkit-slider-thumb]:size-5 pointer-events-none absolute left-0 top-0 z-10 h-2 w-full appearance-none bg-transparent ring-[#F6A2BC] focus:z-20 [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:cursor-pointer [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-[#E21655] [&::-moz-range-thumb]:ring-offset-1 [&::-moz-range-thumb]:focus:ring-1 [&::-moz-range-thumb]:focus:ring-offset-white [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-[#E21655] [&::-webkit-slider-thumb]:ring-offset-1 [&::-webkit-slider-thumb]:focus:ring-1 [&::-webkit-slider-thumb]:focus:ring-offset-white"
        />
    </div>

    {{-- display value --}}
    <div class="relative mt-2 flex h-8 w-52 items-center gap-10">
        <input
            type="text"
            @if ($type == 'decimal') oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" @endif
            class="from-input min-w-12 max-w-20 absolute -left-2 rounded-lg border-none bg-gray-700 text-center text-sm font-medium outline-none"
            value="{{ $value[0] }}"
            min="{{ $min }}"
            max="{{ $max }}"
        />
        <input
            type="text"
            @if ($type == 'decimal') oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" @endif
            class="to-input min-w-12 max-w-20 absolute -right-2 rounded-lg border-none bg-gray-700 text-center text-sm font-medium outline-none"
            value="{{ $value[1] }}"
            min="{{ $min }}"
            max="{{ $max }}"
        />
    </div>

</div>

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sliders = document.querySelectorAll('.range-slider');

            sliders.forEach(slider => {
                const type = slider.getAttribute('data-type');

                // Sliders
                const fromSlider = slider.querySelector('.from-slider');
                const toSlider = slider.querySelector('.to-slider');

                // Inputs fields for the sliders
                const fromInput = slider.querySelector('.from-input');
                const toInput = slider.querySelector('.to-input');

                // Custom slider for displaying the range
                const customSlider = slider.querySelector('.custom-slider');
                const step = parseValue(fromSlider.step);
                const range = toSlider.max - toSlider.min;


                function parseValue(value) {
                    if (type === 'decimal') {
                        return parseFloat(value).toFixed(2);
                    } else if (type === 'time') {

                        //if is integer directly return
                        if (value.indexOf(':') === -1) return parseInt(value)

                        const parts = value.split(':');
                        const hours = parseInt(parts[0], 10);
                        const minutes = parseInt(parts[1], 10);
                        return parseInt(hours * 60 + minutes);
                    } else {
                        return parseInt(value, 10);
                    }
                }

                function formatValue(value) {
                    if (type === 'decimal') {
                        return parseFloat(value).toFixed(2);
                    } else if (type === 'time') {
                        const hours = Math.floor(value / 60).toString().padStart(2, '0');
                        const minutes = (value % 60).toString().padStart(2, '0');
                        return `${hours}:${minutes}`;
                    } else {
                        return parseInt(value, 10);
                    }
                }

                function updateByFromSlider() {
                    let from = parseValue(fromSlider.value);
                    let to = parseValue(toSlider.value);

                    if (type === 'decimal') {
                        from *= 100;
                        to *= 100;
                    }
                    if (from >= to) {
                        if (type === 'decimal') {
                            from /= 100;
                            to /= 100;
                        }
                        fromSlider.value = to - step;
                    }
                    renderInputs();
                }

                function updateByToSlider() {
                    let from = parseValue(fromSlider.value);
                    let to = parseValue(toSlider.value);

                    if (type === 'decimal') {
                        from *= 100;
                        to *= 100;
                    }
                    if (from >= to) {
                        if (type === 'decimal') {
                            from /= 100;
                            to /= 100;
                        }
                        toSlider.value = +from + +step;
                    }
                    renderInputs();
                }

                function updateByFromInput() {
                    let from = parseValue(fromInput.value);
                    let to = parseValue(toInput.value);
                    if (from >= to) {
                        fromInput.value = formatValue(to - step);
                    }
                    renderSlider();
                }

                function updateByToInput() {
                    let from = parseValue(fromInput.value);
                    let to = parseValue(toInput.value);
                    if (from >= to) {
                        toInput.value = formatValue(from + step);
                    }
                    renderSlider();
                }

                function renderSlider() {
                    fromSlider.value = parseValue(fromInput.value);
                    toSlider.value = parseValue(toInput.value);

                    customSlider.style.left = ((fromSlider.value - toSlider.min) / range * 100) + '%';
                    customSlider.style.right = 100 - ((toSlider.value - toSlider.min) / range * 100) + '%';
                }

                function renderInputs() {
                    fromInput.value = formatValue(fromSlider.value);
                    toInput.value = formatValue(toSlider.value);

                    customSlider.style.left = ((fromSlider.value - toSlider.min) / range * 100) + '%';
                    customSlider.style.right = 100 - ((toSlider.value - toSlider.min) / range * 100) + '%';
                }

                fromSlider.addEventListener('input', updateByFromSlider);
                toSlider.addEventListener('input', updateByToSlider);
                fromInput.addEventListener('focusout', updateByFromInput);
                toInput.addEventListener('focusout', updateByToInput);

                renderSlider();
                renderInputs();
            });
        });
    </script>
@endPushOnce
