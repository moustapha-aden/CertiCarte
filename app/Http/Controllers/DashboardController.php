<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard with statistics and recent activity.
     *
     * Provides comprehensive overview including:
     * - Total counts for students, classes, and users
     * - Current school year information
     * - Recent activity metrics (daily, weekly, monthly)
     *
     * @return \Illuminate\View\View The dashboard view with statistics data
     */
    public function index()
    {
        // Basic counts
        $totalStudents = Student::count();
        $totalClasses = Classe::count();
        $totalUsers = User::count();

       // ✅ Récupérer la dernière année scolaire
        $currentYear = SchoolYear::orderBy('year', 'desc')->first();
        $currentYearLabel = $currentYear ? $currentYear->year : 'N/A';

    $recentActivity = [
        'new_students_today' => Student::whereDate('created_at', Carbon::today())->count(),
        'new_classes_this_week' => Classe::where('created_at', '>=', Carbon::now()->subWeek())->count(),
        'total_registrations_this_month' => Student::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
    ];

        // Recent activity data
        $recentActivity = [
            'new_students_today' => Student::whereDate('created_at', Carbon::today())->count(),
            'new_classes_this_week' => Classe::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'total_registrations_this_month' => Student::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
        ];

        return view('dashboard', compact(
            'totalStudents',
            'totalClasses',
            'totalUsers',
            'currentYearLabel',
            'recentActivity'
        ));
    }
}
