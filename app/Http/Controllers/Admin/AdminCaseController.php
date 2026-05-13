<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalCase;
use App\Models\Disbursement;
use Illuminate\Http\Request;

class AdminCaseController extends Controller
{
    public function index(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $status = $request->query('status');
        $query = LegalCase::with('client')->orderByDesc('created_at');

        if ($status) {
            $query->where('status', $status);
        }

        $cases = $query->limit(100)->get();

        $statuses = ['pending', 'matched', 'waiting_payment', 'paid', 'in_progress', 'completed', 'cancelled', 'expired'];

        return view('admin.cases.list', compact('admin', 'cases', 'statuses', 'status'));
    }

    public function show(Request $request, int $caseId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $case = LegalCase::with(['client', 'lawyer', 'category', 'disbursement'])
            ->find($caseId);

        if (!$case) return redirect('/admin/cases');

        return view('admin.cases.detail', compact('admin', 'case'));
    }

    public function processDisbursement(Request $request, int $caseId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $disbursement = Disbursement::where('case_id', $caseId)->first();
        if ($disbursement && $disbursement->status === 'pending') {
            $disbursement->update(['status' => 'processing']);
        }

        return redirect("/admin/cases/{$caseId}");
    }

    public function completeDisbursement(Request $request, int $caseId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $disbursement = Disbursement::where('case_id', $caseId)->first();
        if ($disbursement && in_array($disbursement->status, ['pending', 'processing'])) {
            $disbursement->update([
                'status' => 'completed',
                'processed_at' => now(),
            ]);
        }

        return redirect("/admin/cases/{$caseId}");
    }
}
