{{-- 

  Author : GAN ZHI KEN

--}}
<div class="drop-down-container relative w-36">
    <button
        class="flex w-full items-center justify-between rounded-lg bg-darkSurface-500 px-4 py-2 text-center text-sm font-medium focus:outline-none focus:ring-1 focus:ring-primary-600"
        type="button"
    >
        All days
        <svg
            class="ms-2.5 h-2.5 w-2.5"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 10 6"
        >
            <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m1 1 4 4 4-4"
            />
        </svg>
    </button>

    <!-- Dropdown menu -->
    <div class="drop-down-menu absolute top-[110%] z-10 hidden w-full rounded-lg bg-darkSurface-500 shadow">
        <label class="block border-b border-gray-200 text-sm font-medium">
            <div class="flex items-center rounded p-2">
                <input
                    checked
                    type="checkbox"
                    class="all-days-checkbox h-4 w-4 rounded accent-primary-600 focus:ring-2 focus:ring-primary-600"
                >
                <span class="ms-2 w-full rounded text-sm font-medium">All days</span>
            </div>
        </label>


        <ul class="h-48 overflow-y-auto px-3 pb-3 text-sm">
            <li>
                <label>
                    <div class="flex items-center rounded p-2">
                        <input
                            type="checkbox"
                            value=""
                            class="day-checkbox h-4 w-4 rounded accent-[#E21655] focus:ring-2 focus:ring-[#E21655]"
                        >
                        <span class="ms-2 w-full rounded text-sm font-medium">Monday</span>
                    </div>
                </label>
            </li>
            <li>
                <label>
                    <div class="flex items-center rounded p-2">
                        <input
                            type="checkbox"
                            value=""
                            class="day-checkbox h-4 w-4 rounded accent-[#E21655] focus:ring-2 focus:ring-[#E21655]"
                        >
                        <span class="ms-2 w-full rounded text-sm font-medium">Tuesday</span>
                    </div>
                </label>
            </li>
            <li>
                <label>
                    <div class="flex items-center rounded p-2">
                        <input
                            type="checkbox"
                            value=""
                            class="day-checkbox h-4 w-4 rounded accent-[#E21655] focus:ring-2 focus:ring-[#E21655]"
                        >
                        <span class="ms-2 w-full rounded text-sm font-medium">Wednesday</span>
                    </div>
                </label>
            </li>
            <li>
                <label>
                    <div class="flex items-center rounded p-2">
                        <input
                            type="checkbox"
                            value=""
                            class="day-checkbox h-4 w-4 rounded accent-[#E21655] focus:ring-2 focus:ring-[#E21655]"
                        >
                        <span class="ms-2 w-full rounded text-sm font-medium">Thursday</span>
                    </div>
                </label>
            </li>
            <li>
                <label>
                    <div class="flex items-center rounded p-2">
                        <input
                            type="checkbox"
                            value=""
                            class="day-checkbox h-4 w-4 rounded accent-[#E21655] focus:ring-2 focus:ring-[#E21655]"
                        >
                        <span class="ms-2 w-full rounded text-sm font-medium">Friday</span>
                    </div>
                </label>
            </li>
            <li>
                <label>
                    <div class="flex items-center rounded p-2">
                        <input
                            type="checkbox"
                            value=""
                            class="day-checkbox h-4 w-4 rounded accent-[#E21655] focus:ring-2 focus:ring-[#E21655]"
                        >
                        <span class="ms-2 w-full rounded text-sm font-medium">Saturday</span>
                    </div>
                </label>
            </li>
            <li>
                <label>
                    <div class="flex items-center rounded p-2">
                        <input
                            type="checkbox"
                            value=""
                            class="day-checkbox h-4 w-4 rounded accent-[#E21655] focus:ring-2 focus:ring-[#E21655]"
                        >
                        <span class="ms-2 w-full rounded text-sm font-medium">Sunday</span>
                    </div>
                </label>
            </li>
        </ul>

    </div>
</div>

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const dayFilterDropdown = document.querySelector('.drop-down-container');
            const dayFilterDropdownButton = dayFilterDropdown.querySelector('button');
            const dayFilterDropdownMenu = dayFilterDropdown.querySelector('.drop-down-menu');

            dayFilterDropdownButton.addEventListener('click', function() {
                dayFilterDropdownMenu.classList.toggle('hidden');
            });

            // Close the dropdown when clicking outside of it
            document.addEventListener('click', function(event) {
                if (!dayFilterDropdown.contains(event.target)) {
                    dayFilterDropdownMenu.classList.add('hidden');
                }
            });

            // check all checkboxes when clicking on the "All days" checkbox
            const allDaysCheckbox = document.querySelector('.all-days-checkbox');
            const dayCheckboxes = document.querySelectorAll('.day-checkbox');

            allDaysCheckbox.addEventListener('change', function() {
                dayCheckboxes.forEach(checkbox => {
                    console.log(allDaysCheckbox.checked)
                    checkbox.checked = allDaysCheckbox.checked;
                });
            });

            // uncheck "All days" checkbox when any day checkbox is unchecked
            // check "All days" checkbox when all day checkboxes are checked
            dayCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allDaysChecked = [...dayCheckboxes].every(checkbox => checkbox.checked);
                    allDaysCheckbox.checked = allDaysChecked;
                });
            });

            // make all the days is checkeded initially
            allDaysCheckbox.checked = true;
            dayCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        });
    </script>
@endPushOnce
