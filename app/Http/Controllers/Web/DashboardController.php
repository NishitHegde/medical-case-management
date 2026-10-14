<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MedicalCase;
use App\Services\HolidayService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show application dashboard.
     */
    public function index(HolidayService $holidayService): View
    {
        $counts = [
            'total' => MedicalCase::count(),
            'planning' => MedicalCase::where('status', 'Planning')->count(),
            'in_review' => MedicalCase::where('status', 'In Review')->count(),
            'approved' => MedicalCase::where('status', 'Approved')->count(),
            'completed' => MedicalCase::where('status', 'Completed')->count(),
            'draft' => MedicalCase::where('status', 'Draft')->count(),
            'cancelled' => MedicalCase::where('status', 'Cancelled')->count(),
        ];

        $recentCases = MedicalCase::with('creator')->latest('id')->take(5)->get();

        // Optional bonus: fetch upcoming holidays safely
        $upcomingHolidays = array_slice($holidayService->getHolidays(), 0, 3);

        return view('dashboard.index', compact('counts', 'recentCases', 'upcomingHolidays'));
    }
}
