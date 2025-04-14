<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Grade;
use App\Models\TermGrade;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filter inputs
        $filterFrom = $request->input('sy_from');
        $filterTo = $request->input('sy_to');
        $filterSem = $request->input('semester');
        $filterGradeName = $request->input('grade_name');

        // ======= G_SHEET_FINAL (Final grades) =======
        $finalGrades = collect(); // empty by default
        if (!$filterGradeName || $filterGradeName === 'Final') {
            $finalGrades = $user->finalGrades()
                ->with(['subSection.subject', 'remark', 'encodedByUser', 'curriculum'])
                ->where('IS_VALID', 1)
                ->where('IS_DEL', 0)
                ->when($filterGradeName === 'Final', fn($q) =>
                    $q->where('GRADE_NAME', 'Final')
                )
                ->when($filterFrom, fn($q) =>
                    $q->whereHas('curriculum', fn($c) => $c->where('SY_FROM', $filterFrom))
                )
                ->when($filterTo, fn($q) =>
                    $q->whereHas('curriculum', fn($c) => $c->where('SY_TO', $filterTo))
                )
                ->when($filterSem !== null, fn($q) =>
                    $q->whereHas('curriculum', fn($c) => $c->where('SEMESTER', $filterSem))
                )
                ->get();
        }

        // ======= GRADE_SHEET (Term grades) =======
        $termGrades = collect(); // empty by default
        if (!$filterGradeName || $filterGradeName !== 'Final') {
            $termGrades = $user->termGrades()
                ->with(['subSection.subject', 'remark', 'encodedByUser', 'curriculum'])
                ->where('IS_VALID', 1)
                ->where('IS_DEL', 0)
                ->when($filterGradeName, fn($q) =>
                    $q->where('GRADE_NAME', $filterGradeName)
                )
                ->when($filterFrom, fn($q) =>
                    $q->whereHas('curriculum', fn($c) => $c->where('SY_FROM', $filterFrom))
                )
                ->when($filterTo, fn($q) =>
                    $q->whereHas('curriculum', fn($c) => $c->where('SY_TO', $filterTo))
                )
                ->when($filterSem !== null, fn($q) =>
                    $q->whereHas('curriculum', fn($c) => $c->where('SEMESTER', $filterSem))
                )
                ->get();
        }

        // ======= Merge all grades =======
        $allGrades = $termGrades->merge($finalGrades);

        // ======= Collect available terms for dropdowns =======
        $availableTerms = $allGrades
            ->pluck('curriculum')
            ->filter()
            ->unique(fn($c) => $c->SY_FROM . '-' . $c->SY_TO . '-' . $c->SEMESTER)
            ->sortByDesc('SY_FROM');

        return view('dashboard', compact(
            'user',
            'allGrades',
            'availableTerms',
            'filterFrom',
            'filterTo',
            'filterSem',
            'filterGradeName'
        ));
    }
}
