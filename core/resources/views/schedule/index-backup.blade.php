{{-- 

  Author : GAN ZHI KEN

--}}
<x-layout>

    <div class="container mx-auto p-4">
        <div class="rounded-lg bg-darkSurface-500 shadow-lg">
            <!-- Calendar Header -->
            <div class="flex items-center justify-between border-b border-darkSurface-200 p-4">
                <div class="flex items-center space-x-4">

                    {{--  --}}
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

                    {{--  --}}
                    <h2
                        id="currentDate"
                        class="text-xl font-semibold"
                    ></h2>
                    <input
                        type="text"
                        id="datePicker"
                        class="rounded-lg border border-gray-600 bg-darkMixed-400 px-2 py-1 text-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-600"
                        placeholder="Select date"
                    />
                    <button
                        id="todayBtn"
                        class="rounded bg-primary-600 px-3 py-1 font-bold hover:bg-opacity-80"
                    >
                        Today
                    </button>
                </div>

                {{--  --}}
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
            <!-- Calendar Grid -->
            <div
                id="calendarGrid"
                class="relative p-4"
            ></div>
        </div>
    </div>



    @pushonce('scripts')
        <script type="module">
            import flatpickr from "{{ Vite::asset('resources/js/flatpickr.js') }}";
            import dayjs from "{{ Vite::asset('resources/js/dayjs.js') }}";

            document.addEventListener("DOMContentLoaded", function() {
                const calendarGrid = document.getElementById("calendarGrid");
                const currentDateElement = document.getElementById("currentDate");
                const prevBtn = document.getElementById("prevBtn");
                const nextBtn = document.getElementById("nextBtn");
                const weekViewBtn = document.getElementById("weekView");
                const monthViewBtn = document.getElementById("monthView");
                const datePicker = document.getElementById("datePicker");
                const todayBtn = document.getElementById("todayBtn");

                let currentDate = dayjs();
                let currentView = "month";

                // Dummy events
                const events = [{
                        id: 1,
                        title: "Meeting with Client",
                        start: dayjs().hour(10).minute(0),
                        end: dayjs().hour(11).minute(30),
                        color: "bg-primary-600",
                    },
                    {
                        id: 2,
                        title: "Lunch Break",
                        start: dayjs().hour(12).minute(0),
                        end: dayjs().hour(13).minute(0),
                        color: "bg-green-700",
                    },
                    {
                        id: 3,
                        title: "Project Deadline",
                        start: dayjs().add(2, "days").hour(15).minute(0),
                        end: dayjs().add(2, "days").hour(16).minute(0),
                        color: "bg-red-700",
                    },
                    {
                        id: 4,
                        title: "Team Building",
                        start: dayjs().add(1, "week").hour(14).minute(0),
                        end: dayjs().add(1, "week").hour(17).minute(0),
                        color: "bg-yellow-700",
                    },
                    {
                        id: 5,
                        title: "Conference Call",
                        start: dayjs().add(2, "weeks").hour(11).minute(0),
                        end: dayjs().add(2, "weeks").hour(12).minute(30),
                        color: "bg-purple-700",
                    },
                ];

                // Initialize Flatpickr
                const fp = flatpickr(datePicker, {
                    dateFormat: "Y-m-d",
                    onChange: function(selectedDates, dateStr, instance) {
                        currentDate = dayjs(dateStr);
                        renderCalendar();
                    }
                });

                function renderCalendar() {
                    calendarGrid.innerHTML = "";
                    currentDateElement.textContent = currentDate.format("MMMM YYYY");
                    datePicker.value = currentDate.format("YYYY-MM-DD");
                    fp.setDate(currentDate.toDate());

                    switch (currentView) {
                        case "week":
                            renderWeekView();
                            break;
                        case "month":
                            renderMonthView();
                            break;
                    }
                }

                function renderWeekView() {
                    const weekStart = currentDate.startOf("week");
                    const weekGrid = document.createElement("div");
                    weekGrid.className = "grid grid-cols-8";

                    const today = dayjs();

                    // Add time column
                    const timeColumn = document.createElement("div");
                    timeColumn.className = "border-r border-darkSurface-200";

                    for (let hour = 0; hour < 24; hour++) {
                        const timeCell = document.createElement("div");
                        timeCell.className =
                            "h-12 p-1 text-sm flex items-center justify-end";
                        timeCell.textContent = dayjs().hour(hour).minute(0).format("hh:mm A");
                        timeColumn.appendChild(timeCell);
                    }
                    weekGrid.appendChild(timeColumn);

                    // Add day columns
                    for (let day = 0; day < 7; day++) {
                        const dayColumn = document.createElement("div");
                        dayColumn.className = "border-r border-darkSurface-200";
                        const currentDay = weekStart.add(day, "days");

                        const dayHeader = document.createElement("div");
                        dayHeader.className =
                            "h-6 text-center font-semibold border-b border-darkSurface-200 sticky top-0 z-20";

                        if (currentDay.isSame(today, "day")) {
                            dayColumn.classList.add("bg-primary-100", "bg-opacity-20"); // highlight the day
                            dayHeader.classList.add("bg-primary-500", "text-white"); // highlight the header
                        } else {
                            dayHeader.classList.add("bg-darkMixed-300");
                        }

                        dayHeader.textContent = currentDay.format("ddd D/M");
                        dayColumn.appendChild(dayHeader);

                        const dayBody = document.createElement("div");
                        dayBody.className = "relative";

                        // each hour time slot
                        for (let hour = 0; hour < 24; hour++) {
                            const cell = document.createElement("div");
                            cell.className = "h-12 border-b p-1 relative";

                            if (currentDay.isSame(today, "day")) {
                                cell.classList.add("border-darkSurface-200");
                            } else {
                                cell.classList.add("border-darkSurface-200");
                            }

                            dayBody.appendChild(cell);
                        }

                        // Add events to the week view
                        events.forEach((event) => {
                            if (event.start.isSame(currentDay, "day")) {
                                console.log("same day for event", event);
                                const eventElement = createEventElement(
                                    event,
                                    currentDay,
                                    currentDay.endOf("day"),
                                    true
                                );
                                dayBody.appendChild(eventElement);
                            }
                        });

                        dayColumn.appendChild(dayBody);
                        weekGrid.appendChild(dayColumn);
                    }

                    calendarGrid.appendChild(weekGrid);
                }

                function renderMonthView() {
                    const firstDayOfMonth = currentDate.startOf("month");
                    const lastDayOfMonth = currentDate.endOf("month");

                    const monthGrid = document.createElement("div");
                    monthGrid.className = "grid grid-cols-7 gap-1";

                    const today = dayjs();

                    // Day headers
                    const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                    daysOfWeek.forEach((day) => {
                        const dayHeader = document.createElement("div");
                        dayHeader.className = "text-center font-semibold";
                        dayHeader.textContent = day;
                        monthGrid.appendChild(dayHeader);
                    });

                    // Add empty cells for days before the first day of the month
                    for (let i = 0; i < firstDayOfMonth.day(); i++) {
                        const emptyCell = document.createElement("div");
                        emptyCell.className = "h-24 border border-darkSurface-200  p-1";
                        monthGrid.appendChild(emptyCell);
                    }

                    // Add cells for each day of the month
                    for (let day = 1; day <= lastDayOfMonth.date(); day++) {
                        const cell = document.createElement("div");
                        cell.className =
                            "h-24 border border-darkSurface-200 p-1 hover:bg-darkMixed-400 transition-colors duration-200 overflow-hidden flex flex-col gap-1";

                        const currentDay = currentDate.date(day);
                        if (currentDay.isSame(today, "day")) {
                            cell.classList.add("bg-darkMixed-400");
                        }

                        const dayNumber = document.createElement("span");
                        dayNumber.className = "text-sm font-semibold";
                        dayNumber.textContent = day;
                        if (currentDay.isSame(today, "day")) {
                            dayNumber.classList.add(
                                "inline-block",
                                "rounded-full",
                                "bg-primary-500",
                                "text-white",
                                "w-6",
                                "h-6",
                                "text-center",
                                "leading-6"
                            );
                        }

                        cell.appendChild(dayNumber);
                        monthGrid.appendChild(cell);

                        // Add events to the day cell
                        events.forEach((event) => {
                            if (event.start.isSame(currentDay, "day")) {
                                const eventElement = document.createElement("div");
                                eventElement.className =
                                    `${event.color} rounded p-1 text-xs truncate cursor-pointer`;
                                eventElement.textContent = event.title;
                                eventElement.title = `${event.title}\n${event.start.format(
								"h:mm A"
							)} - ${event.end.format("h:mm A")}`;
                                cell.appendChild(eventElement);
                            }
                        });
                    }

                    // Add empty cells for days after the last day of the month
                    const remainingCells = 42 - (firstDayOfMonth.day() + lastDayOfMonth.date());
                    for (let i = 0; i < remainingCells; i++) {
                        const emptyCell = document.createElement("div");
                        emptyCell.className = "h-24 border border-darkSurface-200  p-1";
                        monthGrid.appendChild(emptyCell);
                    }

                    calendarGrid.appendChild(monthGrid);
                }

                function createEventElement(event, viewStart, viewEnd, showTime = false) {
                    const eventElement = document.createElement("div");
                    const titleDiv = document.createElement("div");
                    eventElement.className = `${event.color} rounded p-1 text-xs cursor-pointer absolute`;
                    titleDiv.textContent = event.title;
                    titleDiv.className = "truncate";
                    eventElement.appendChild(titleDiv);

                    if (showTime) {
                        const timeDiv = document.createElement("div");
                        timeDiv.textContent = `${event.start.format("h:mm A")} - ${event.end.format("h:mm A")}`;
                        timeDiv.className = "text-xs truncate";
                        eventElement.appendChild(timeDiv);
                    }
                    eventElement.title = `${event.title}\n
                    ${event.start.format("h:mm A")} - ${event.end.format("h:mm A")}`;

                    const startTime = event.start.isBefore(viewStart) ? viewStart : event.start;
                    const endTime = event.end.isAfter(viewEnd) ? viewEnd : event.end;

                    const totalMinutes = viewEnd.diff(viewStart, "minute");
                    const eventStartMinutes = startTime.diff(viewStart, "minute"); // minutes from the start of the view
                    const eventEndMinutes = viewEnd.diff(endTime, "minute"); // minutes from the end of the view

                    const top = `${(eventStartMinutes / totalMinutes) * 100}%`;
                    const bottom = `${(eventEndMinutes / totalMinutes) * 100}%`;

                    eventElement.style.top = top;
                    eventElement.style.bottom = bottom;
                    eventElement.style.width = "100%";
                    eventElement.style.zIndex = "10";

                    return eventElement;
                }

                prevBtn.addEventListener("click", () => {
                    switch (currentView) {
                        case "week":
                            currentDate = currentDate.subtract(1, "week");
                            break;
                        case "month":
                            currentDate = currentDate.subtract(1, "month");
                            break;
                    }
                    renderCalendar();
                });

                nextBtn.addEventListener("click", () => {
                    switch (currentView) {
                        case "week":
                            currentDate = currentDate.add(1, "week");
                            break;
                        case "month":
                            currentDate = currentDate.add(1, "month");
                            break;
                    }
                    renderCalendar();
                });

                weekViewBtn.addEventListener("click", () => {
                    if (currentView === "week") return;
                    currentView = "week";
                    renderCalendar();
                });

                monthViewBtn.addEventListener("click", () => {
                    if (currentView === "month") return;
                    currentView = "month";
                    renderCalendar();
                });

                todayBtn.addEventListener("click", () => {
                    currentDate = dayjs();
                    renderCalendar();
                });

                // Initial render
                renderCalendar();

            });
        </script>
    @endpushonce

</x-layout>
