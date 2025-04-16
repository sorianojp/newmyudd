@extends('layouts.app')

@section('content')
<div class="min-h-screen p-6">
    <div class="max-w-5xl mx-auto bg-white shadow-sm rounded-lg p-6">
    <h1 class="text-2xl font-bold">Subjects Load Schedule</h1>
        @forelse ($enrolledSubjects as $term => $subjects)
            @php
                [$sy, $semCode] = explode('-S', $term);
                [$syFrom, $syTo] = explode('-', $sy);
                $semLabel = match((int)$semCode) {
                    0 => 'Summer',
                    1 => '1st Semester',
                    2 => '2nd Semester',
                    default => 'Unknown'
                };

                $groupedSubjects = collect($subjects)->groupBy('SUB_CODE');
            @endphp

            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">
                    School Year: {{ $syFrom }} - {{ $syTo }} | Semester: {{ $semLabel }}
                </h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded shadow-sm text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">Subject Code</th>
                                <th class="px-4 py-2 border">Subject Title</th>
                                <th class="px-4 py-2 border text-center">Total Units</th>
                                <th class="px-4 py-2 border">Schedule</th>
                                <th class="px-4 py-2 border">Faculty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedSubjects as $code => $group)
                                @php
                                    $dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                    $schedules = $group->map(function ($s) use ($dayNames) {
                                        if ($s->WEEK_DAY !== null && $s->HOUR_FROM_24 && $s->HOUR_TO_24) {
                                            $day = is_numeric($s->WEEK_DAY) ? $dayNames[$s->WEEK_DAY] : $s->WEEK_DAY;
                                            return $day . ': ' .
                                                str_pad($s->HOUR_FROM_24, 2, '0', STR_PAD_LEFT) . ':00 - ' .
                                                str_pad($s->HOUR_TO_24, 2, '0', STR_PAD_LEFT) . ':00';
                                        }
                                        return null;
                                    })->filter()->unique()->implode(', ');
                                @endphp

                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ $group->first()->SUB_CODE }}</td>
                                    <td class="px-4 py-2 border">{{ $group->first()->SUB_NAME }}</td>
                                    <td class="px-4 py-2 border text-center">{{ $group->first()->tot_acad_unit }}</td>
                                    <td class="px-4 py-2 border">{{ $schedules ?: 'N/A' }}</td>
                                    <td class="px-4 py-2 border">{{ $group->first()->faculty_name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p class="text-gray-600 mt-6">No enrolled subjects found.</p>
        @endforelse
    </div>
</div>
@endsection
