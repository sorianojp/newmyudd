@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold">My Grades</h1>
        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 my-8">
            <div>
                <label for="sy_from" class="block text-sm font-medium">SY From</label>
                <select name="sy_from" id="sy_from" class="w-full mt-1 border rounded px-2 py-1">
                    <option value="">-- All --</option>
                    @foreach ($availableTerms->pluck('SY_FROM')->unique() as $year)
                        <option value="{{ $year }}" {{ request('sy_from') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sy_to" class="block text-sm font-medium">SY To</label>
                <select name="sy_to" id="sy_to" class="w-full mt-1 border rounded px-2 py-1">
                    <option value="">-- All --</option>
                    @foreach ($availableTerms->pluck('SY_TO')->unique() as $year)
                        <option value="{{ $year }}" {{ request('sy_to') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="semester" class="block text-sm font-medium">Semester</label>
                <select name="semester" id="semester" class="w-full mt-1 border rounded px-2 py-1">
                    <option value="">-- All --</option>
                    <option value="1" {{ request('semester') === '1' ? 'selected' : '' }}>1st Sem</option>
                    <option value="2" {{ request('semester') === '2' ? 'selected' : '' }}>2nd Sem</option>
                    <option value="0" {{ request('semester') === '0' ? 'selected' : '' }}>Summer</option>
                </select>
            </div>

            <div>
                <label for="grade_name" class="block text-sm font-medium">Grade Type</label>
                <select name="grade_name" id="grade_name" class="w-full mt-1 border rounded px-2 py-1">
                    <option value="">-- All --</option>
                    <option value="Prelim" {{ request('grade_name') == 'Prelim' ? 'selected' : '' }}>Prelim</option>
                    <option value="Midterm" {{ request('grade_name') == 'Midterm' ? 'selected' : '' }}>Midterm</option>
                    <option value="Semi-Final" {{ request('grade_name') == 'Semi-Final' ? 'selected' : '' }}>Semi-Final</option>
                    <option value="Final" {{ request('grade_name') == 'Final' ? 'selected' : '' }}>Final</option>
                </select>
            </div>

            <div class="md:col-span-4">
                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </form>

        @forelse ($finalGroupedGrades as $term => $grades)
            @php [$sy, $to, $semLabel] = explode('-', $term); @endphp

            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-2">
                School Year: {{ $sy }} - {{ $to }} | Semester: {{ $semLabel }}
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded shadow-sm text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Subject</th>
                            <th class="px-4 py-2 border">Grade Type</th>
                            <th class="px-4 py-2 border">Grade</th>
                            <th class="px-4 py-2 border">Credits</th>
                            <th class="px-4 py-2 border">Remark</th>
                            <th class="px-4 py-2 border">Encoded By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grades as $grade)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border">{{ $grade->subSection->subject->SUB_NAME ?? 'N/A' }}</td>
                                <td class="px-4 py-2 border">{{ $grade->GRADE_NAME }}</td>
                                <td class="px-4 py-2 border">{{ $grade->GRADE }}</td>
                                <td class="px-4 py-2 border">{{ $grade->CREDIT_EARNED }}</td>
                                <td class="px-4 py-2 border">{{ $grade->remark->REMARK ?? 'N/A' }}</td>
                                <td class="px-4 py-2 border">
                                    {{ $grade->encodedByUser->LNAME ?? '' }},
                                    {{ $grade->encodedByUser->FNAME ?? '' }}
                                    {{ $grade->encodedByUser->MNAME ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-gray-600 mt-4">No grades found for the selected filters.</p>
        @endforelse
    </div>
</div>
@endsection
