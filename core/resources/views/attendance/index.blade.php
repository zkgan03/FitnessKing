<x-layout>
    <div class="min-h-[80vh] bg-cover bg-center"
        style="background-image: url('{{ Vite::asset("resources/images/classes-img.png") }}')">
        <div class="flex flex-col items-center justify-center h-full gap-8 bg-black bg-opacity-70 p-8">
            <h1 class="text-4xl font-extrabold text-yellow-300 tracking-wide">Attendance System</h1>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative w-full max-w-xl mb-4">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">There were some problems with your input:</span>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-black p-8 rounded-lg shadow-lg w-full max-w-xl border-4 border-indigo-400">
                {!! $inputFormHtml !!}
            </div>

            @if ($gymClasses->isNotEmpty())
                    <div class="bg-black p-8 rounded-lg shadow-lg w-full max-w-4xl border-4 border-yellow-500">
                        <h2 class="text-2xl font-semibold mb-6 text-indigo-800">Customer Attendance</h2>
                        <table class="table-auto w-full border-collapse text-lg">
                            <thead>
                                <tr class="bg-indigo-100">
                                    <th class="border px-6 py-3 text-left text-indigo-900 border-indigo-500">Customer Name</th>
                                    <th class="border px-6 py-3 text-left text-indigo-900 border-indigo-500">Class Name</th>
                                    <th class="border px-6 py-3 text-left text-indigo-900 border-indigo-500">Classroom</th>
                                    <th class="border px-6 py-3 text-left text-indigo-900 border-indigo-500">Time</th>
                                    <th class="border px-6 py-3 text-left text-indigo-900 border-indigo-500">Attendance</th>
                                </tr>
                            </thead>
                            <tbody id="attendance-table">
                                @foreach ($gymClasses as $gymClass)
                                                        @foreach ($gymClass->customers as $customer)
                                                                                    @php
                                                            $attendanceRecord = $gymClass->attendances->firstWhere('customer_id', $customer->customer_id);
                                                            $isPresent = $attendanceRecord ? $attendanceRecord->is_present : false;
                                                                                    @endphp
                                                                                    <tr class="hover:bg-yellow-100" data-customer-id="{{ $customer->customer_id }}" data-class-id="{{ $gymClass->class_id }}">
                                                                                        <td class="border px-6 py-4 text-indigo-800">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                                                                        <td class="border px-6 py-4 text-indigo-800">{{ $gymClass->class_name }}</td>
                                                                                        <td class="border px-6 py-4 text-indigo-800">{{ $gymClass->classroom }}</td>
                                                                                        <td class="border px-6 py-4 text-indigo-800">
                                                                                            {{ date('H:i', strtotime($gymClass->start_time)) }} -
                                                                                            {{ date('H:i', strtotime($gymClass->end_time)) }}
                                                                                        </td>
                                                                                        <td class="border px-6 py-4 flex justify-center items-center">
    <input type="checkbox" class="attendance-checkbox h-8 w-8 text-yellow-500"
        data-customer-id="{{ $customer->customer_id }}"
        data-class-id="{{ $gymClass->class_id }}"
        {{ $isPresent ? 'checked' : '' }}>
</td>

                                                                                    </tr>
                                                        @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            @else
                <p class="text-yellow-300 text-xl font-semibold">No classes found based on your filters.</p>
            @endif

            @if(session('status'))
                <p class="text-yellow-300 text-lg font-semibold">{{ session('status') }}</p>
            @endif
        </div>
    </div>

    <script>
        document.querySelectorAll('.attendance-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const customerId = this.getAttribute('data-customer-id');
                const classId = this.getAttribute('data-class-id');
                const isPresent = this.checked ? 1 : 0;

                fetch('{{ route('attendance.mark') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ class_id: classId, attendances: { [customerId]: isPresent } })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Attendance updated successfully');
                    } else {
                        alert('Failed to update attendance');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</x-layout>
