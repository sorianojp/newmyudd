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

        $filterFrom = $request->input('sy_from');
        $filterTo = $request->input('sy_to');
        $filterSem = $request->input('semester');
        $filterGradeName = $request->input('grade_name');

        // Final Grades (G_SHEET_FINAL)
        $finalGrades = collect();
        if (!$filterGradeName || $filterGradeName === 'Final') {
            $finalGrades = $user->finalGrades()
                ->with(['subSection.subject', 'remark', 'encodedByUser', 'curriculum'])
                ->where('IS_VALID', 1)->where('IS_DEL', 0)
                ->when($filterGradeName === 'Final', fn($q) => $q->where('GRADE_NAME', 'Final'))
                ->when($filterFrom, fn($q) => $q->whereHas('curriculum', fn($c) => $c->where('SY_FROM', $filterFrom)))
                ->when($filterTo, fn($q) => $q->whereHas('curriculum', fn($c) => $c->where('SY_TO', $filterTo)))
                ->when($filterSem !== null, fn($q) => $q->whereHas('curriculum', fn($c) => $c->where('SEMESTER', $filterSem)))
                ->get();
        }

        // Term Grades (GRADE_SHEET)
        $termGrades = collect();
        if (!$filterGradeName || $filterGradeName !== 'Final') {
            $termGrades = $user->termGrades()
                ->with(['subSection.subject', 'remark', 'encodedByUser', 'curriculum'])
                ->where('IS_VALID', 1)->where('IS_DEL', 0)
                ->when($filterGradeName, fn($q) => $q->where('GRADE_NAME', $filterGradeName))
                ->when($filterFrom, fn($q) => $q->whereHas('curriculum', fn($c) => $c->where('SY_FROM', $filterFrom)))
                ->when($filterTo, fn($q) => $q->whereHas('curriculum', fn($c) => $c->where('SY_TO', $filterTo)))
                ->when($filterSem !== null, fn($q) => $q->whereHas('curriculum', fn($c) => $c->where('SEMESTER', $filterSem)))
                ->get();
        }

        $allGrades = $termGrades->merge($finalGrades);

        $availableTerms = $allGrades->pluck('curriculum')->filter()->unique(fn($c) => $c->SY_FROM . '-' . $c->SY_TO . '-' . $c->SEMESTER)->sortByDesc('SY_FROM');

        // Group and sort
        $bySchoolYear = $allGrades
            ->filter(fn($g) => $g->curriculum)
            ->groupBy(function ($item) {
                $sy = $item->curriculum->SY_FROM ?? '0000';
                $to = $item->curriculum->SY_TO ?? '0000';
                return "{$sy}-{$to}";
            });

        $sortedSchoolYears = $bySchoolYear->sortKeysDesc();

        $finalGrouped = collect();
        foreach ($sortedSchoolYears as $syKey => $gradesInYear) {
            $bySem = $gradesInYear->groupBy(fn($g) => $g->curriculum->SEMESTER ?? 1);
            foreach ([0, 2, 1] as $sem) {
                if (!isset($bySem[$sem])) continue;

                $sortedGrades = $bySem[$sem]->sortBy(function ($grade) {
                    return match ($grade->GRADE_NAME) {
                        'Final' => 0,
                        'Semi-Final' => 1,
                        'Midterm' => 2,
                        'Prelim' => 3,
                        default => 4,
                    };
                });

                $semLabel = match ((int)$sem) {
                    0 => 'Summer',
                    2 => 'Second Semester',
                    1 => 'First Semester',
                    default => 'Unknown',
                };

                $finalGrouped->put("{$syKey}-{$semLabel}", $sortedGrades);
            }
        }

        return view('dashboard', [
            'user' => $user,
            'availableTerms' => $availableTerms,
            'filterFrom' => $filterFrom,
            'filterTo' => $filterTo,
            'filterSem' => $filterSem,
            'filterGradeName' => $filterGradeName,
            'finalGroupedGrades' => $finalGrouped
        ]);
    }
}
