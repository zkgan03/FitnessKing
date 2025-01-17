{{-- 

  Author : GAN ZHI KEN

--}}
@props(['currentDate', 'classes'])

<div class="grid grid-cols-8">

    {{-- Add time --}}
    <div class="border-r border-darkSurface-200">
        @for ($hour = 0; $hour < 24; $hour++)
            <div class="flex h-12 items-center justify-end p-1 text-sm">
                {{ Carbon\Carbon::createFromTime($hour)->format('h:i A') }}
            </div>
        @endfor
    </div>

    @php
        $weekStart = $currentDate->copy()->startOfWeek()->setTimezone('Asia/Kuala_Lumpur');
        $weekEnd = $currentDate->copy()->endOfWeek()->setTimezone('Asia/Kuala_Lumpur');
        $today = now()->setTimezone('Asia/Kuala_Lumpur');
    @endphp

    @for ($day = 0; $day < 7; $day++)
        @php
            $currentDay = $weekStart->copy()->addDays($day);
            $isToday = $currentDay->isSameDay($today);
            $isSelectedDay = $currentDay->isSameDay($currentDate);
            // dd($currentDay);
        @endphp
        <div
            class="{{ $isSelectedDay ? 'bg-primary-200 bg-opacity-30' : '' }} {{ $isToday ? '!bg-blue-300 !bg-opacity-30' : '' }} border-r border-darkSurface-200">

            {{-- Week header (sun, mon ..) --}}
            <div
                class="{{ $isSelectedDay ? 'bg-primary-500' : '' }} {{ $isToday ? '!bg-blue-500' : 'bg-darkMixed-300' }} sticky top-0 z-20 h-6 border-b border-darkSurface-200 text-center font-semibold">
                {{ $currentDay->format('D n/j') }}
            </div>

            {{-- Week col --}}
            <div class="relative">
                {{-- add col for week view --}}
                @for ($hour = 0; $hour < 24; $hour++)
                    <div
                        class="{{ $isToday ? 'border-darkSurface-200' : 'border-darkSurface-200' }} relative h-12 border-b p-1">
                    </div>
                @endfor

                {{-- Add classes --}}
                @foreach ($classes as $class)
                    @if ($class['start']->isSameDay($currentDay))
                        @php
                            $classStart = max($class['start'], $currentDay->copy()->startOfDay());
                            $classEnd = min($class['end'], $currentDay->copy()->endOfDay());

                            // top = the start time of the class - the start time of the day
                            // then divided by the total minutes in a day (24 * 60)
                            // then multiplied by 100 to get the percentage
                            $top = ($classStart->diffInMinutes($currentDay->copy()->startOfDay(), true) / 1440) * 100;

                            // height = the dururation of the class
                            // then divided by the total minutes in a day (24 * 60)
                            // then multiplied by 100 to get the percentage
                            $height = ($classEnd->diffInMinutes($classStart, true) / 1440) * 100;
                        @endphp
                        <div
                            class="class-item {{ $class['color'] }} absolute left-0 right-0 cursor-pointer overflow-hidden rounded p-1 text-xs"
                            style="top: {{ $top }}%; height: {{ $height }}%;"
                            data-class-name = "{{ $class['class_name'] }}"
                            data-class-id="{{ $class['class_id'] }}"
                            data-classroom="{{ $class['classroom'] }}"
                            data-package-id="{{ $class['package_id'] }}"
                            data-class-date="{{ $class['start']->format('Y-m-d') }}"
                            data-class-start="{{ $class['start']->format('h:i A') }}"
                            data-class-end="{{ $class['end']->format('h:i A') }}"
                            data-class-description="{{ $class['description'] ?? 'No description' }}"
                        >
                            <div class="truncate">
                                {{ $class['class_name'] }}
                            </div>
                            <div class="truncate text-xs">
                                {{ $class['start']->format('h:i A') }} - {{ $class['end']->format('h:i A') }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endfor
</div>
