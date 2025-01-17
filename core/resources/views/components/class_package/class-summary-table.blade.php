{{-- 

  Author : GAN ZHI KEN

--}}
@props(['classPackage', 'classes'])

<div>
    <table class="size-full table-striped table-hover table-bordered table text-center">
        <thead>
            <tr class="bg-darkMixed-300 text-xl">
                <th>{{ __('Class Name') }}</th>
                <th>{{ __('Classroom') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Start') }}</th>
                <th>{{ __('End') }}</th>
                <th>{{ __('Duration') }}</th>
            </tr>
        </thead>
        <tbody class="">
            @php
                use Carbon\Carbon;
            @endphp
            @foreach ($classes as $class)
                @php
                    //convert to carbon date
                    $classDate = Carbon::parse($class->class_date, 'Asia/Kuala_Lumpur');
                    $startTime = Carbon::parse($class->start_time, 'Asia/Kuala_Lumpur');
                    $endTime = Carbon::parse($class->end_time, 'Asia/Kuala_Lumpur');
                @endphp
                <tr class="text-black odd:bg-gray-200 even:bg-gray-300 hover:bg-darkMixed-200 hover:text-white">
                    <td class="py-2">{{ $class->class_name }}</td>
                    <td>{{ $class->classroom }}</td>
                    <td>{{ $class->class_type }}</td>
                    <td>{{ $classDate->format('d/m/Y') }}</td>
                    <td>{{ $startTime->format('h:i A') }}</td>
                    <td>{{ $endTime->format('h:i A') }}</td>
                    <td>
                        {{ $endTime->diffInMinutes($startTime, true) . ' min' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
