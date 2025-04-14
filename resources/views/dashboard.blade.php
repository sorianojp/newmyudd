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

    <h2>Final Grades</h2>
<table border="1">
    <tr>
        <th>Subject</th>
        <th>Grade Name</th>
        <th>Grade</th>
        <th>Credits</th>
        <th>Remark</th>
        <th>Instructor</th>
    </tr>
    @foreach ($finalGrades as $grade)
        <tr>
            <td>{{ $grade->subSection->subject->SUB_NAME ?? 'N/A' }}</td>
            <td>{{ $grade->GRADE_NAME }}</td>
            <td>{{ $grade->GRADE }}</td>
            <td>{{ $grade->CREDIT_EARNED }}</td>
            <td>{{ $grade->remark->REMARK ?? 'N/A' }}</td>
            <td>
                {{ $grade->encodedByUser->FNAME ?? '' }}
                {{ $grade->encodedByUser->MNAME ?? '' }}
                {{ $grade->encodedByUser->LNAME ?? '' }}
            </td>
        </tr>
    @endforeach
</table>

<h2>Term Grades (Prelim, Midterm, Semi-Final)</h2>
<table border="1">
    <tr>
        <th>Subject</th>
        <th>Grade Name</th>
        <th>Grade</th>
        <th>Credits</th>
        <th>Remark</th>
        <th>Instructor</th>
    </tr>
    @foreach ($termGrades as $term)
        <tr>
            <td>{{ $term->subSection->subject->SUB_NAME ?? 'N/A' }}</td>
            <td>{{ $term->GRADE_NAME }}</td>
            <td>{{ $term->GRADE }}</td>
            <td>{{ $term->CREDIT_EARNED }}</td>
            <td>{{ $term->remark->REMARK ?? 'N/A' }}</td>
            <td>
                {{ $term->encodedByUser->FNAME ?? '' }}
                {{ $term->encodedByUser->MNAME ?? '' }}
                {{ $term->encodedByUser->LNAME ?? '' }}
            </td>
        </tr>
    @endforeach
</table>

    <form action="{{ route('logout') }}" method="POST" style="margin-top: 30px;">
        @csrf
        <button type="submit">Logout</button>
    </form>



</body>

</html>
