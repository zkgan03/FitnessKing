{{-- 

  Author : GAN ZHI KEN

--}}
<x-layout>
    <div
        class="container mx-auto p-4"
        style="background-image: url('{{ Vite::asset('resources/images/home-img.jpg') }}')"
    >
        <h1 class="mb-4 text-2xl font-bold">Timetable</h1>
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Time</th>
                    <th class="border px-4 py-2">Monday</th>
                    <th class="border px-4 py-2">Tuesday</th>
                    <th class="border px-4 py-2">Wednesday</th>
                    <th class="border px-4 py-2">Thursday</th>
                    <th class="border px-4 py-2">Friday</th>
                    <th class="border px-4 py-2">Saturday</th>
                    <th class="border px-4 py-2">Sunday</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $timeslots = [
                        '08:00 AM',
                        '09:00 AM',
                        '10:00 AM',
                        '11:00 AM',
                        '12:00 PM',
                        '01:00 PM',
                        '02:00 PM',
                        '03:00 PM',
                        '04:00 PM',
                        '05:00 PM',
                        '06:00 PM',
                        '07:00 PM',
                        '08:00 PM',
                        '09:00 PM',
                        '10:00 PM',
                    ];
                    $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp

                @foreach ($timeslots as $timeslot)
                    <tr>
                        <td class="border px-4 py-2 text-center">{{ $timeslot }}</td>
                        @for ($day = 0; $day < 7; $day++)
                            @php
                                $assignedClass = $timetables->first(function ($item) use ($day, $timeslot) {
                                    return $item->day == $day && $item->timeslot == $timeslot;
                                });
                            @endphp
                            <td
                                class="droppable border px-4 py-2 text-center"
                                data-day="{{ $day }}"
                                data-timeslot="{{ $timeslot }}"
                            >
                                @if ($assignedClass)
                                    <div
                                        class="draggable rounded bg-green-200 p-2"
                                        draggable="true"
                                        data-class-id="{{ $assignedClass->class_id }}"
                                    >
                                        <p class="text-black">{{ $assignedClass->gymClass->class_name }}</p>
                                    </div>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <div class="container mx-auto mt-6 bg-white p-4 shadow-md">
        <h2 class="mb-4 text-xl font-bold text-black">Unassigned Classes</h2>
        <div
            id="unassigned-classes"
            class="grid grid-cols-4 gap-4"
        >
            @foreach ($unassignedClasses as $class)
                <div
                    class="draggable rounded-md bg-red-200 p-4"
                    draggable="true"
                    data-class-id="{{ $class->class_id }}"
                >
                    <p class="font-semibold text-black">{{ $class->class_name }}</p>
                    <p class="text-black">{{ $class->description }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.droppable').forEach(slot => {
                new Sortable(slot, {
                    group: 'shared',
                    animation: 150,
                    onAdd: function(evt) {
                        const classId = evt.item.getAttribute('data-class-id');
                        const day = evt.to.getAttribute('data-day');
                        const timeslot = evt.to.getAttribute('data-timeslot');

                        toggleLoading(true);

                        saveTimetableAssignment(classId, day, timeslot)
                            .then(response => {
                                console.log(response);
                                if (response.status === 'success') {
                                    showNotification('Class assigned successfully!',
                                        'success');
                                } else {
                                    showNotification('Failed to assign class: ' + (response
                                        .message || 'Unknown error'), 'error');
                                    evt.from.appendChild(evt.item);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showNotification('An error occurred: ' + error, 'error');
                                evt.from.appendChild(evt.item);
                            })
                            .finally(() => {
                                toggleLoading(false);
                            });
                    },
                    onRemove: function(evt) {
                        const classId = evt.item.getAttribute('data-class-id');

                        toggleLoading(true);

                        removeTimetableAssignment(classId)
                            .then(response => {
                                if (response.status === 'success') {
                                    showNotification('Class removed successfully!',
                                        'success');
                                } else {
                                    showNotification('Failed to remove class.', 'error');
                                    evt.from.appendChild(evt.item);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showNotification('An error occurred.', 'error');
                                evt.from.appendChild(evt.item);
                            })
                            .finally(() => {
                                toggleLoading(false);
                            });
                    }
                });
            });

            new Sortable(document.getElementById('unassigned-classes'), {
                group: 'shared',
                animation: 150
            });
        });

        function saveTimetableAssignment(classId, day, timeslot) {
            return fetch('{{ route('timetable.assign') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        class_id: classId,
                        day: day,
                        timeslot: timeslot
                    })
                })
                .then(response => response.json());
        }

        function removeTimetableAssignment(classId) {
            return fetch('{{ route('timetable.remove') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        class_id: classId
                    })
                })
                .then(response => response.json());
        }

        function showNotification(message, type) {
            alert(`${type.toUpperCase()}: ${message}`);
        }

        function toggleLoading(isLoading) {
            if (isLoading) {
                // Show loader
            } else {
                // Hide loader
            }
        }
    </script>

</x-layout>
