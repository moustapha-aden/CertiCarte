<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic counts
        $totalStudents = Student::count();
        $totalClasses = Classe::count();
        $totalUsers = User::count();

        // Get current school year
        $currentYear = SchoolYear::latest()->first();
        $currentYearLabel = $currentYear ? $currentYear->year : 'N/A';

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
