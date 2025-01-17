{{-- 

  Author : GAN ZHI KEN

--}}

@props(['currentDate', 'classes'])

<div class="grid grid-cols-7 gap-1">

    {{-- days header --}}
    @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
        <div class="text-center font-semibold">{{ $day }}</div>
    @endforeach

    @php
        $firstDayOfMonth = $currentDate->copy()->startOfMonth()->setTimezone('Asia/Kuala_Lumpur');
        $lastDayOfMonth = $currentDate->copy()->endOfMonth()->setTimezone('Asia/Kuala_Lumpur');
        $today = now()->setTimezone('Asia/Kuala_Lumpur');
    @endphp

    {{-- generate the empty column without date --}}
    @for ($i = 0; $i < $firstDayOfMonth->dayOfWeek; $i++)
        <div class="h-24 border border-darkSurface-200 p-1"></div>
    @endfor

    {{-- generate the column with date --}}
    @for ($day = 1; $day <= $lastDayOfMonth->day; $day++)
        @php
            $currentDay = $currentDate->copy()->setDay($day);
            $isToday = $currentDay->isSameDay($today);
            $isSelectedDay = $currentDay->isSameDay($currentDate);
        @endphp
        <div
            class="{{ $isSelectedDay ? 'bg-primary-200 bg-opacity-30' : '' }} {{ $isToday ? '!bg-blue-300 !bg-opacity-30' : '' }} flex h-24 flex-col gap-1 overflow-hidden border border-darkSurface-200 p-1 transition-colors duration-200 hover:bg-darkMixed-400">
            <span
                class="{{ $isSelectedDay ? 'inline-block rounded-full bg-primary-500 w-6 h-6 text-center leading-6' : '' }} {{ $isToday ? 'inline-block rounded-full !bg-blue-500 w-6 h-6 text-center leading-6' : '' }} text-sm font-semibold"
            >
                {{ $day }}
            </span>
            @foreach ($classes as $class)
                @if ($class['start']->isSameDay($currentDay))
                    <div
                        class="class-item {{ $class['color'] }} cursor-pointer truncate rounded p-1 text-xs"
                        data-class-name = "{{ $class['class_name'] }}"
                        data-class-id="{{ $class['class_id'] }}"
                        data-classroom="{{ $class['classroom'] }}"
                        data-package-id="{{ $class['package_id'] }}"
                        data-class-date="{{ $class['start']->format('Y-m-d') }}"
                        data-class-start="{{ $class['start']->format('h:i A') }}"
                        data-class-end="{{ $class['end']->format('h:i A') }}"
                        data-class-description="{{ $class['description'] ?? 'No description' }}"
                    >
                        {{ $class['class_name'] }}
                    </div>
                @endif
            @endforeach
        </div>
    @endfor

    {{-- generate the empty column without date --}}
    @for ($i = 0; $i < 42 - ($firstDayOfMonth->dayOfWeek + $lastDayOfMonth->day); $i++)
        <div class="h-24 border border-darkSurface-200 p-1"></div>
    @endfor
</div>
