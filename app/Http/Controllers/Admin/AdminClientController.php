<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LegalCase;
use Illuminate\Http\Request;

class AdminClientController extends Controller
{
    public function index(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $clients = User::where('role', 'client')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.clients.list', compact('admin', 'clients'));
    }

    public function show(Request $request, int $clientId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $client = User::where('id', $clientId)->where('role', 'client')->first();
        if (!$client) return redirect('/admin/clients');

        $cases = LegalCase::where('client_id', $clientId)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.clients.detail', compact('admin', 'client', 'cases'));
    }

    public function toggleActive(Request $request, int $clientId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $client = User::where('id', $clientId)->where('role', 'client')->first();
        if ($client) {
            $client->update(['is_active' => !$client->is_active]);
        }

        return redirect("/admin/clients/{$clientId}");
    }
}
