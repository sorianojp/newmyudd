<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectLoadController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrolled = DB::table('ENRL_FINAL_CUR_LIST as e')
            ->join('E_SUB_SECTION as s', 'e.SUB_SEC_INDEX', '=', 's.SUB_SEC_INDEX')
            ->join('SUBJECT as sub', 's.SUB_INDEX', '=', 'sub.SUB_INDEX')
            ->leftJoin('E_ROOM_ASSIGN as sched', 's.SUB_SEC_INDEX', '=', 'sched.SUB_SEC_INDEX')
            ->leftJoin('FACULTY_LOAD as fload', 's.SUB_SEC_INDEX', '=', 'fload.SUB_SEC_INDEX')
            ->leftJoin('USER_TABLE as u', 'fload.USER_INDEX', '=', 'u.USER_INDEX')
            ->where('e.USER_INDEX', $user->USER_INDEX)
            ->where('e.IS_VALID', 1)
            ->where('e.IS_DEL', 0)
            ->select(
                'e.SY_FROM',
                'e.SY_TO',
                'e.CURRENT_SEMESTER',
                'sub.SUB_CODE',
                'sub.SUB_NAME',
                'sub.tot_acad_unit',
                'sched.WEEK_DAY',
                'sched.HOUR_FROM_24',
                'sched.HOUR_TO_24',
                DB::raw("CONCAT(u.LNAME, ', ', u.FNAME, ' ', u.MNAME) as faculty_name")
            )
            ->get();

        // Group by school year and semester
        $grouped = $enrolled->groupBy(function ($item) {
            return $item->SY_FROM . '-' . $item->SY_TO . '-S' . $item->CURRENT_SEMESTER;
        });

        // Sort first by year descending, then semester: Summer > 2nd > 1st
        $sortedGrouped = collect($grouped->keys())
            ->map(function ($key) {
                preg_match('/^(\d{4})-(\d{4})-S(\d)$/', $key, $matches);
                $syFrom = (int)$matches[1];
                $syTo = (int)$matches[2];
                $sem = (int)$matches[3];

                $semOrder = match ($sem) {
                    0 => 0, // Summer
                    2 => 1, // 2nd Sem
                    1 => 2, // 1st Sem
                    default => 3
                };

                // Use descending year and custom sem order for sorting
                return ['key' => $key, 'year' => $syFrom, 'semOrder' => $semOrder];
            })
            ->sortByDesc('year')
            ->groupBy('year')
            ->map(function ($yearGroup) {
                return $yearGroup->sortBy('semOrder');
            })
            ->flatten(1)
            ->pluck('key')
            ->mapWithKeys(function ($key) use ($grouped) {
                return [$key => $grouped[$key]];
            });

        return view('subject-load', [
            'enrolledSubjects' => $sortedGrouped
        ]);
    }
}