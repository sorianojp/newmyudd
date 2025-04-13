<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <h1>Welcome, {{ $user->USER_ID }}</h1>

    <ul>
        <li>User ID: {{ $user->USER_ID }}</li>
        <li>Login Index: {{ $user->LOGIN_INDEX }}</li>
        <li>Valid: {{ $user->IS_VALID }}</li>
        <li>Last Renewed: {{ $user->LAST_RENEW }}</li>
    </ul>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <form method="GET" action="{{ route('dashboard') }}">
        <label for="term">Grade Term:</label>
        <select name="term" id="term">
            <option value="">-- All --</option>
            <option value="Prelim" {{ request('term') == 'Prelim' ? 'selected' : '' }}>Prelim</option>
            <option value="Midterm" {{ request('term') == 'Midterm' ? 'selected' : '' }}>Midterm</option>
            <option value="Semi-Final" {{ request('term') == 'Semi-Final' ? 'selected' : '' }}>Semi-Final</option>
            <option value="Final" {{ request('term') == 'Final' ? 'selected' : '' }}>Final</option>
        </select>

        <label for="sem">Semester:</label>
        <select name="sem" id="sem">
            <option value="">-- All --</option>
            <option value="1" {{ request('sem') == '1' ? 'selected' : '' }}>1st Semester</option>
            <option value="2" {{ request('sem') == '2' ? 'selected' : '' }}>2nd Semester</option>
        </select>

        <label>School Year:</label>
        <input type="number" name="sy_from" value="{{ request('sy_from') }}" placeholder="2020" style="width: 80px;">
        to
        <input type="number" name="sy_to" value="{{ request('sy_to') }}" placeholder="2021" style="width: 80px;">

        <button type="submit">Filter</button>
    </form>

    @if ($finalGrades->count() > 0)
        <h2>Final Grades</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Grade Name</th>
                    <th>Grade</th>
                    <th>Credits</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($finalGrades as $grade)
                    <tr>
                        <td>{{ $grade->subSection->subject->SUB_CODE ?? 'N/A' }}</td>
                        <td>{{ $grade->subSection->subject->SUB_NAME ?? 'N/A' }}</td>
                        <td>{{ $grade->GRADE_NAME }}</td>
                        <td>{{ $grade->GRADE }}</td>
                        <td>{{ $grade->CREDIT_EARNED }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($termGrades->count() > 0)
        <h2>Term Grades (Prelim / Midterm / Semi-Final)</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Grade Name</th>
                    <th>Grade</th>
                    <th>Credits</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($termGrades as $grade)
                    <tr>
                        <td>{{ $grade->subSection->subject->SUB_CODE ?? 'N/A' }}</td>
                        <td>{{ $grade->subSection->subject->SUB_NAME ?? 'N/A' }}</td>
                        <td>{{ $grade->GRADE_NAME }}</td>
                        <td>{{ $grade->GRADE }}</td>
                        <td>{{ $grade->CREDIT_EARNED }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($finalGrades->isEmpty() && $termGrades->isEmpty())
        <p>No grades found for the selected filters.</p>
    @endif

    <form action="{{ route('logout') }}" method="POST" style="margin-top: 30px;">
        @csrf
        <button type="submit">Logout</button>
    </form>



</body>

</html>
