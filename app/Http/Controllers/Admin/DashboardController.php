<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LegalCase;
use App\Models\Disbursement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) {
            return redirect('/admin/login');
        }

        $stats = [
            'total_clients' => User::where('role', 'client')->count(),
            'total_lawyers' => User::where('role', 'lawyer')->count(),
            'total_cases' => LegalCase::count(),
            'completed_cases' => LegalCase::where('status', 'completed')->count(),
            'pending_cases' => LegalCase::where('status', 'pending')->count(),
            'total_revenue' => LegalCase::whereIn('status', ['paid', 'in_progress', 'completed'])->sum('total_price') ?? 0,
            'pending_disbursements' => Disbursement::where('status', 'pending')->count(),
        ];

        $recentCases = LegalCase::orderByDesc('created_at')->limit(10)->get();

        return view('admin.dashboard-tailwick', compact('admin', 'stats', 'recentCases'));
    }
}
