{{-- 

  Author : GAN ZHI KEN

--}}
<x-layout>

    <div class="container mx-auto p-4">

        {{-- Calendar Container --}}
        <div class="rounded-lg bg-darkSurface-500 shadow-lg">

            <!-- Calendar Header -->
            <div class="flex items-center justify-between border-b border-darkSurface-200 p-4">

                {{-- Calendar Navigation Buttons --}}
                <div class="flex items-center space-x-4">

                    {{-- Calendar Navigation Buttons --}}
                    <button
                        id="prevBtn"
                        class="text-gray-400 hover:text-gray-200"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 320 512"
                            class="size-4"
                        >
                            <path
                                fill="currentColor"
                                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"
                            />
                        </svg>
                    </button>
                    <button
                        id="nextBtn"
                        class="text-gray-400 hover:text-gray-200"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 320 512"
                            class="size-4"
                        >
                            <path
                                fill="currentColor"
                                d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"
                            />
                        </svg>
                    </button>

                    {{-- Show selected month and year --}}
                    <div
                        id="currentDate"
                        class="text-xl font-semibold"
                    >{{ $currentDate->format('F Y') }}
                    </div>

                    {{-- Date Picker --}}
                    <input
                        type="text"
                        id="datePicker"
                        class="rounded-lg border border-gray-600 bg-darkMixed-400 px-2 py-1 text-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-600"
                        placeholder="Select date"
                        value="{{ $currentDate->format('Y-m-d') }}"
                    />

                    {{-- Today Button --}}
                    <button
                        id="todayBtn"
                        class="rounded bg-primary-600 px-3 py-1 font-bold hover:bg-opacity-80"
                    >
                        Today
                    </button>
                </div>

                {{-- Week and Month View Buttons --}}
                <div class="flex space-x-2">
                    <button
                        id="weekView"
                        class="rounded bg-darkSurface-300 px-3 py-1 text-white hover:bg-darkSurface-400"
                    >
                        Week
                    </button>
                    <button
                        id="monthView"
                        class="rounded bg-darkSurface-300 px-3 py-1 text-white hover:bg-darkSurface-400"
                    >
                        Month
                    </button>
                </div>
            </div>

            {{-- Calendar Body --}}
            <div class="relative">

                {{-- Loading Spinner --}}
                <div
                    id="loadingSpinner"
                    class="absolute inset-0 z-50 hidden items-center justify-center bg-darkSurface-500 bg-opacity-75"
                >
                    <div class="h-32 w-32 animate-spin rounded-full border-b-2 border-t-2 border-primary-600"></div>
                </div>

                {{-- Calendar Grid --> will rerender on view change --}}
                <div
                    id="calendarGrid"
                    class="relative p-4"
                >
                    @if ($currentView === 'month')
                        <x-calendar.month-view
                            :currentDate="$currentDate"
                            :classes="$classes"
                        />
                    @else
                        <x-calendar.week-view
                            :currentDate="$currentDate"
                            :classes="$classes"
                        />
                    @endif
                </div>
            </div>
        </div>

        {{-- Class dialog --}}
        <x-calendar.class-detail-dialog />
    </div>

    @pushonce('scripts')
        <script type="module">
            import flatpickr from "{{ Vite::asset('resources/js/flatpickr.js') }}";
            import dayjs from "{{ Vite::asset('resources/js/dayjs.js') }}";

            document.addEventListener("DOMContentLoaded", function() {
                const calendarGrid = document.getElementById("calendarGrid");
                const loadingSpinner = document.getElementById("loadingSpinner");
                const currentDateElement = document.getElementById("currentDate");
                const prevBtn = document.getElementById("prevBtn");
                const nextBtn = document.getElementById("nextBtn");
                const weekViewBtn = document.getElementById("weekView");
                const monthViewBtn = document.getElementById("monthView");
                const datePicker = document.getElementById("datePicker");
                const todayBtn = document.getElementById("todayBtn");

                let currentDate = dayjs("{{ $currentDate->format('Y-m-d') }}");
                let currentView = "{{ $currentView }}";

                // Initialize Flatpickr
                const fp = flatpickr(datePicker, {
                    dateFormat: "Y-m-d",
                    onChange: function(selectedDates, dateStr, instance) {
                        currentDate = dayjs(dateStr);
                        refreshCalendar();
                    }
                });

                // Loading view when change the view between week and month
                function showLoading() {
                    loadingSpinner.classList.add('!flex');
                }

                function hideLoading() {
                    loadingSpinner.classList.remove('!flex');
                }

                function refreshCalendar() {
                    showLoading();

                    fetch(`{{ route('schedule.updateView') }}?view=${currentView}&date=${currentDate.format('YYYY-MM-DD')}`, {
                            method: 'get',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => response.text())
                        .then(html => {
                            calendarGrid.innerHTML = html;
                            currentDateElement.textContent = currentDate.format('MMMM YYYY');
                            datePicker.value = currentDate.format('YYYY-MM-DD');
                            addItemsListeners();
                            hideLoading();
                        })
                        .catch(error => {
                            console.error('Error : ', error);
                            hideLoading();
                        });
                }


                // Add event listeners to the buttons in the calendar
                prevBtn.addEventListener("click", () => {
                    currentDate = currentView === 'week' ?
                        currentDate.subtract(1, 'week') :
                        currentDate.subtract(1, 'month');
                    refreshCalendar();
                });

                nextBtn.addEventListener("click", () => {
                    currentDate = currentView === 'week' ?
                        currentDate.add(1, 'week') :
                        currentDate.add(1, 'month');

                    refreshCalendar();
                });

                weekViewBtn.addEventListener("click", () => {
                    if (currentView === "week") return;
                    currentView = "week";
                    refreshCalendar();
                });

                monthViewBtn.addEventListener("click", () => {
                    if (currentView === "month") return;
                    currentView = "month";
                    refreshCalendar();
                });

                todayBtn.addEventListener("click", () => {
                    currentDate = dayjs();
                    refreshCalendar();
                });

                function addItemsListeners() {
                    document.querySelectorAll('.class-item').forEach(item => {
                        item.addEventListener('click', function(event) {
                            event.stopPropagation();

                            console.log(this.dataset);
                            const classData = {
                                className: this.dataset.className,
                                classDate: this.dataset.classDate,
                                start: this.dataset.classStart,
                                end: this.dataset.classEnd,
                                description: this.dataset.classDescription,
                                classroom: this.dataset.classroom,
                                packageId: this.dataset.packageId,
                                classId: this.dataset.classId
                            };
                            console.log(classData);
                            showClassDetails(classData);

                            // if (confirm('Do you want to drop this enrollment?')) {
                            //     const eventId = this.dataset.eventId;
                            //     unenroll(eventId);
                            // }
                        });
                    });
                }
                addItemsListeners();
            });
        </script>
    @endpushonce
</x-layout>
