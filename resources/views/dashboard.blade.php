<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <h1>Welcome, {{ $user->userProfile->full_name }}</h1>
    <form action="{{ route('logout') }}" method="POST" style="margin-top: 30px;">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <ul>
        <li>User ID: {{ $user->USER_ID }}</li>
        <li>Login Index: {{ $user->LOGIN_INDEX }}</li>
        <li>Valid: {{ $user->IS_VALID }}</li>
        <li>Last Renewed: {{ $user->LAST_RENEW }}</li>
        <li>User Index: {{ $user->USER_INDEX }}</li>
    </ul>
    <form method="GET" action="{{ route('dashboard') }}">
        <label for="sy_from">SY From:</label>
        <select name="sy_from" id="sy_from">
            <option value="">-- All --</option>
            @foreach ($availableTerms->pluck('SY_FROM')->unique() as $year)
                <option value="{{ $year }}" {{ request('sy_from') == $year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>

        <label for="sy_to">SY To:</label>
        <select name="sy_to" id="sy_to">
            <option value="">-- All --</option>
            @foreach ($availableTerms->pluck('SY_TO')->unique() as $year)
                <option value="{{ $year }}" {{ request('sy_to') == $year ? 'selected' : '' }}>{{ $year }}</option>
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

    @forelse ($finalGroupedGrades as $term => $grades)
        @php
            [$sy, $to, $semLabel] = explode('-', $term);
        @endphp

        <h3>School Year: {{ $sy }} - {{ $to }} | Semester: {{ $semLabel }}</h3>

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
                @foreach ($grades as $grade)
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
    @empty
        <p>No grades found for the selected filters.</p>
    @endforelse

</body>
</html>
