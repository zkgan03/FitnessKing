{{-- 

  Author : GAN ZHI KEN

--}}
@props([
    'class' => ' ',
    'classDetails' => ' ',
])

@php
    use Carbon\Carbon;

    //convert to carbon date
    $classDate = Carbon::parse($classDetails->class_date, 'Asia/Kuala_Lumpur');
    $startTime = Carbon::parse($classDetails->start_time, 'Asia/Kuala_Lumpur');
    $endTime = Carbon::parse($classDetails->end_time, 'Asia/Kuala_Lumpur');

    $duration = $endTime->diffInMinutes($startTime, true);
@endphp

<div class="{{ $class }} flex items-center gap-5 rounded-lg bg-darkSurface-500 p-10 sm:flex-col xl:flex-row">
    {{-- class img --}}
    <div class="min-h-[20rem] xl:w-[25rem] 2xl:w-[30rem]">
        <img
            class="size-full rounded object-cover object-center"
            src='{{ url("storage/$classDetails->class_image") }}'
            alt=""
        >
    </div>

    {{-- class details --}}
    <div class="flex flex-1 flex-col gap-8">
        {{-- class name / title --}}
        <div class="text-2xl font-bold">
            {{ $classDetails->class_name }}
        </div>

        {{-- class time / day / class location --}}
        <div class="text-sm leading-6">
            <div class="grid grid-cols-[auto,1fr] gap-x-2">
                {{-- date --}}
                <div>
                    Date
                </div>
                <div>
                    : {{ $classDate->format('d/m/Y') }}
                </div>

                {{-- time --}}
                <div>
                    Time
                </div>
                <div>
                    : {{ $startTime->format('h:i A') . ' - ' . $endTime->format('h:i A') }}
                </div>

                {{-- Duration --}}
                <div>
                    Duration
                </div>
                <div>
                    : {{ $duration . ' minutes' }}
                </div>

                {{-- location / class number --}}
                <div>
                    Classroom
                </div>
                <div>
                    : {{ $classDetails->classroom }}
                </div>

                {{-- class type --}}
                <div>
                    Type of Class
                </div>
                <div>
                    : {{ $classDetails->class_type }}
                </div>

                {{-- class instructor --}}
                <div>
                    Instructor
                </div>
                <div>
                    : {{ $classDetails->coach->first_name . ' ' . $classDetails->coach->last_name }}
                </div>
            </div>
        </div>

        {{-- class description --}}
        <div class="text-sm">
            {{ $classDetails->description }}
        </div>

    </div>
</div>
