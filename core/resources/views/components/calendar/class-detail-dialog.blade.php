{{-- 

  Author : GAN ZHI KEN

--}}
<div
    id="classDetailsDialog"
    class="fixed inset-0 z-50 hidden min-h-screen overflow-y-auto"
>

    {{-- Model --}}
    <div
        class="fixed inset-0 z-0 bg-gray-500 bg-opacity-75 backdrop-blur transition-opacity"
        aria-hidden="true"
    ></div>

    {{-- Class details container --}}
    <div class="relative z-10 flex h-full items-center justify-center">
        <div
            class="size-fit max-w-[45rem] overflow-hidden rounded-lg bg-darkSurface-500 text-left align-bottom shadow-xl transition-all">

            <div class="bg-darkSurface-500 px-4 py-4">

                {{-- Class name --}}
                <div
                    id="className"
                    class="text-lg font-bold"
                ></div>

                {{-- Class details (Class date, time, classroom) --}}
                <div class="mt-2 grid grid-cols-[auto,1fr] gap-x-2 text-sm">

                    {{-- Class date --}}
                    <div>
                        Date
                    </div>
                    <div id="classDate"></div>

                    {{-- Class time --}}
                    <div>
                        Time
                    </div>
                    <div id="classTime"></div>

                    {{-- Class classroom --}}
                    <div>
                        Classroom
                    </div>
                    <div id="classroom"></div>
                </div>

                {{-- Class description --}}
                <div
                    id="classDescription"
                    class="mt-2 text-sm"
                ></div>
            </div>

            {{-- Buttons (View Package Details and close) --}}
            <div class="flex gap-3 px-4 py-3 font-bold">

                {{-- View Package Details (href will generate dynamically in js) --}}
                <a
                    id="viewPackageDetailBtn"
                    class="ml-auto"
                >
                    <button
                        type="button"
                        class="inline-flex w-auto justify-center rounded-md bg-primary-600 px-4 py-2 text-sm hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-white"
                    >
                        View Package Details
                    </button>
                </a>

                {{-- Close button --}}
                <button
                    type="button"
                    id="closeDialog"
                    class="inline-flex w-auto justify-center rounded-md bg-darkSurface-200 px-4 py-2 text-sm hover:opacity-80 focus:outline-none"
                >
                    Close
                </button>
            </div>
        </div>
    </div>

</div>

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classDetailsDialog = document.getElementById("classDetailsDialog");
            const closeDialogBtn = document.getElementById("closeDialog");

            function hideClassDetails() {
                classDetailsDialog.classList.add('hidden');
            }
            closeDialogBtn.addEventListener('click', hideClassDetails);
        });

        const classPackageRoute = "{{ route('class_package.index') }}";

        function showClassDetails(classData) {
            document.getElementById('className').textContent = `${classData.className}`;

            document.getElementById('classDate').textContent = ` : ${classData.classDate}`;
            document.getElementById('classTime').textContent = ` : ${classData.start} - ${classData.end}`;
            document.getElementById('classroom').textContent = `: ${classData.classroom}`;

            document.getElementById('classDescription').textContent = classData.description || 'No description available.';

            const viewPackageDetailBtn = document.getElementById('viewPackageDetailBtn');
            viewPackageDetailBtn.href = `${classPackageRoute}/${classData.packageId}`;

            const classDetailsDialog = document.getElementById("classDetailsDialog");
            classDetailsDialog.classList.remove('hidden');
        }
    </script>
@endPushOnce
