<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingConfig;
use Illuminate\Http\Request;

class AdminPricingController extends Controller
{
    public function index(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $config = PricingConfig::where('is_active', true)
            ->orderByDesc('id')
            ->first();

        return view('admin.pricing.form', ['admin' => $admin, 'config' => $config, 'success' => false]);
    }

    public function store(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $request->validate([
            'base_price' => 'required|numeric',
            'price_per_km' => 'required|numeric',
        ]);

        // Deactivate all existing
        PricingConfig::where('is_active', true)->update(['is_active' => false]);

        $config = PricingConfig::create([
            'base_price' => $request->base_price,
            'base_km' => $request->base_km ?? 5.0,
            'price_per_km' => $request->price_per_km,
            'service_fee' => $request->service_fee ?? 0,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        return view('admin.pricing.form', ['admin' => $admin, 'config' => $config, 'success' => true]);
    }
}
