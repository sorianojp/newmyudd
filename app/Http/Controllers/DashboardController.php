<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $finalGrades = $user->finalGrades()
            ->with(['subSection.subject'])
            ->where('IS_VALID', 1)
            ->where('IS_DEL', 0)
            ->get();

        $termGrades = $user->termGrades()
            ->with(['subSection.subject'])
            ->where('IS_VALID', 1)
            ->where('IS_DEL', 0)
            ->get();

        return view('dashboard', compact('user', 'finalGrades', 'termGrades'));
    }
}
