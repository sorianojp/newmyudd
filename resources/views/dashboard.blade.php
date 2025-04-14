<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <h1>Welcome, {{ $user->userProfile->FNAME }}</h1>

    <ul>
        <li>User ID: {{ $user->USER_ID }}</li>
        <li>Login Index: {{ $user->LOGIN_INDEX }}</li>
        <li>Valid: {{ $user->IS_VALID }}</li>
        <li>Last Renewed: {{ $user->LAST_RENEW }}</li>
        <li>User Index: {{ $user->USER_INDEX }}</li>
    </ul>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('dashboard') }}">
        <label for="sy_from">SY From:</label>
        <select name="sy_from" id="sy_from">
            <option value="">-- All --</option>
            @foreach ($availableTerms->pluck('SY_FROM')->unique() as $year)
                <option value="{{ $year }}" {{ request('sy_from') == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>

        <label for="sy_to">SY To:</label>
        <select name="sy_to" id="sy_to">
            <option value="">-- All --</option>
            @foreach ($availableTerms->pluck('SY_TO')->unique() as $year)
                <option value="{{ $year }}" {{ request('sy_to') == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>

        <label for="semester">Semester:</label>
        <select name="semester" id="semester">
            <option value="">-- All --</option>
            <option value="1" {{ request('semester') === '1' ? 'selected' : '' }}>1st Sem</option>
            <option value="2" {{ request('semester') === '2' ? 'selected' : '' }}>2nd Sem</option>
            <option value="0" {{ request('semester') === '0' ? 'selected' : '' }}>Summer</option>
        </select>

        <label for="grade_name">Grade Type:</label>
        <select name="grade_name" id="grade_name">
            <option value="">-- All --</option>
            <option value="Prelim" {{ request('grade_name') == 'Prelim' ? 'selected' : '' }}>Prelim</option>
            <option value="Midterm" {{ request('grade_name') == 'Midterm' ? 'selected' : '' }}>Midterm</option>
            <option value="Semi-Final" {{ request('grade_name') == 'Semi-Final' ? 'selected' : '' }}>Semi-Final</option>
            <option value="Final" {{ request('grade_name') == 'Final' ? 'selected' : '' }}>Final</option>
        </select>

        <button type="submit">Filter</button>
    </form>

{{-- Grouped Grades Display --}}
@php
    use Illuminate\Support\Collection;

    // Step 1: Group all grades by School Year only (SY_FROM-SY_TO)
    $bySchoolYear = $allGrades
        ->filter(fn($g) => $g->curriculum)
        ->groupBy(function ($item) {
            $sy = $item->curriculum->SY_FROM ?? '0000';
            $to = $item->curriculum->SY_TO ?? '0000';
            return "{$sy}-{$to}";
        });

    // Step 2: Sort School Years descending
    $sortedSchoolYears = $bySchoolYear->sortKeysDesc();

    // Step 3: For each School Year, group by SEMESTER with custom order (0 → 2 → 1)
@endphp

@forelse ($sortedSchoolYears as $schoolYear => $gradesByYear)
    @php
        $bySem = $gradesByYear->groupBy(fn($g) => $g->curriculum->SEMESTER ?? 1);
        $orderedSem = collect([0, 2, 1])->filter(fn($s) => isset($bySem[$s]));
    @endphp

    @foreach ($orderedSem as $sem)
        @php
            $grades = $bySem[$sem];
            $semLabel = match((int)$sem) {
                0 => 'Summer',
                2 => 'Second Semester',
                1 => 'First Semester',
                default => 'Unknown'
            };

            // Sort grades inside group by grade type (Final → Prelim)
            $sortedGrades = $grades->sortBy(function ($grade) {
                return match ($grade->GRADE_NAME) {
                    'Final' => 0,
                    'Semi-Final' => 1,
                    'Midterm' => 2,
                    'Prelim' => 3,
                    default => 4,
                };
            });

            [$syFrom, $syTo] = explode('-', $schoolYear);
        @endphp

        <h3>School Year: {{ $syFrom }} - {{ $syTo }} | Semester: {{ $semLabel }}</h3>

        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Grade Type</th>
                    <th>Grade</th>
                    <th>Credits</th>
                    <th>Remark</th>
                    <th>Encoded By</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sortedGrades as $grade)
                    <tr>
                        <td>{{ $grade->subSection->subject->SUB_NAME ?? 'N/A' }}</td>
                        <td>{{ $grade->GRADE_NAME }}</td>
                        <td>{{ $grade->GRADE }}</td>
                        <td>{{ $grade->CREDIT_EARNED }}</td>
                        <td>{{ $grade->remark->REMARK ?? 'N/A' }}</td>
                        <td>
                            {{ $grade->encodedByUser->LNAME ?? '' }},
                            {{ $grade->encodedByUser->FNAME ?? '' }}
                            {{ $grade->encodedByUser->MNAME ?? '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
@empty
    <p>No grades found.</p>
@endforelse

</body>
</html>
