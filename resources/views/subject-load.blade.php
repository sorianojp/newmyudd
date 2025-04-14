<!DOCTYPE html>
<html>
<head>
    <title>Subjects Enrolled</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .title { font-size: 20px; font-weight: bold; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; font-size: 14px; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .term-header { margin-top: 30px; font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>

<div class="title">SUBJECTS ENROLLED</div>

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

    <div class="term-header">
        School Year: {{ $syFrom }} - {{ $syTo }} | Semester: {{ $semLabel }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Title</th>
                <th>Total Units</th>
                <th>Schedule</th>
                <th>Faculty</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($groupedSubjects as $code => $group)

            @php
                $schedules = $group->map(function ($s) {
                    $dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                    $day = is_numeric($s->WEEK_DAY) ? $dayNames[$s->WEEK_DAY] : $s->WEEK_DAY;

                    if ($s->HOUR_FROM_24 && $s->HOUR_TO_24) {
                        return $day . ': ' .
                            str_pad($s->HOUR_FROM_24, 2, '0', STR_PAD_LEFT) . ':00 - ' .
                            str_pad($s->HOUR_TO_24, 2, '0', STR_PAD_LEFT) . ':00';
                    }

                    return null;
                })->filter()->unique()->implode(', ');
            @endphp

            <tr>
    <td>{{ $group->first()->SUB_CODE }}</td>
    <td>{{ $group->first()->SUB_NAME }}</td>
    <td align="center">{{ $group->first()->tot_acad_unit }}</td>
    <td>
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

        {{ $schedules ?: 'N/A' }}
    </td>
    <td>{{ $group->first()->faculty_name ?? 'N/A' }}</td>

</tr>

            @endforeach
        </tbody>
    </table>
@empty
    <p>No enrolled subjects found.</p>
@endforelse

</body>
</html>
