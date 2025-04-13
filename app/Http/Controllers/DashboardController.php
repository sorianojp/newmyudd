<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filters from GET request
        $term = $request->input('term');
        $sem = $request->input('sem');
        $syFrom = $request->input('sy_from');
        $syTo = $request->input('sy_to');

        // TERM GRADES (GRADE_SHEET)
        $termGrades = $user->termGrades()
            ->with(['subSection.subject'])
            ->when($term, fn($q) => $q->where('GRADE_NAME', $term))
            ->when($sem, fn($q) => $q->whereHas('subSection', fn($sq) => $sq->where('OFFERING_SEM', $sem)))
            ->when($syFrom && $syTo, fn($q) => $q->whereHas('subSection', function ($sq) use ($syFrom, $syTo) {
                $sq->where('OFFERING_SY_FROM', '>=', $syFrom)
                   ->where('OFFERING_SY_TO', '<=', $syTo);
            }))
            ->where('IS_VALID', 1)
            ->where('IS_DEL', 0)
            ->get();

        // FINAL GRADES (G_SHEET_FINAL) — only if 'Final' is selected or no term filter
        $finalGrades = collect(); // default empty

        if ($term === 'Final' || !$term) {
            $finalGrades = $user->finalGrades()
                ->with(['subSection.subject'])
                ->when($sem, fn($q) => $q->whereHas('subSection', fn($sq) => $sq->where('OFFERING_SEM', $sem)))
                ->when($syFrom && $syTo, fn($q) => $q->whereHas('subSection', function ($sq) use ($syFrom, $syTo) {
                    $sq->where('OFFERING_SY_FROM', '>=', $syFrom)
                       ->where('OFFERING_SY_TO', '<=', $syTo);
                }))
                ->where('IS_VALID', 1)
                ->where('IS_DEL', 0)
                ->get();
        }

        return view('dashboard', compact('user', 'termGrades', 'finalGrades'));
    }



}
