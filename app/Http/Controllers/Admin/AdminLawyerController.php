<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LawyerProfile;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminLawyerController extends Controller
{
    public function index(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $lawyers = User::with('lawyerProfile')
            ->where('role', 'lawyer')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.lawyers.list', compact('admin', 'lawyers'));
    }

    public function createPage(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $categories = Category::where('is_active', true)->get();
        return view('admin.lawyers.create', compact('admin', 'categories'));
    }

    public function store(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'lawyer',
            'is_active' => true,
        ]);

        LawyerProfile::create([
            'user_id' => $user->id,
            'bar_number' => $request->bar_number,
            'years_of_experience' => $request->years_of_experience ?? 0,
            'bio' => $request->bio,
            'specializations' => $request->specializations ?? '[]',
        ]);

        return redirect('/admin/lawyers');
    }

    public function show(Request $request, int $lawyerId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $lawyer = User::with(['lawyerProfile', 'lawyerDocuments'])
            ->where('id', $lawyerId)
            ->where('role', 'lawyer')
            ->first();

        if (!$lawyer) return redirect('/admin/lawyers');

        $categories = Category::where('is_active', true)->get();
        $specIds = $lawyer->lawyerProfile?->specialization_ids ?? [];

        return view('admin.lawyers.detail', compact('admin', 'lawyer', 'categories', 'specIds'));
    }

    public function toggleActive(Request $request, int $lawyerId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $lawyer = User::where('id', $lawyerId)->where('role', 'lawyer')->first();
        if ($lawyer) {
            $lawyer->update(['is_active' => !$lawyer->is_active]);
        }

        return redirect("/admin/lawyers/{$lawyerId}");
    }

    public function updateProfile(Request $request, int $lawyerId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $profile = LawyerProfile::where('user_id', $lawyerId)->first();
        if ($profile) {
            $profile->update([
                'bar_number' => $request->bar_number,
                'years_of_experience' => $request->years_of_experience ?? 0,
                'bio' => $request->bio,
                'specializations' => $request->specializations ?? '[]',
            ]);
        }

        return redirect("/admin/lawyers/{$lawyerId}");
    }
}
