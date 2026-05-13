<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use Illuminate\Http\Request;

class AdminContentController extends Controller
{
    const CONTENT_SLUGS = [
        'terms' => 'Syarat & Ketentuan',
        'privacy' => 'Kebijakan Privasi',
    ];

    public function edit(Request $request, string $slug)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        if (!isset(self::CONTENT_SLUGS[$slug])) {
            return redirect('/admin/dashboard');
        }

        $termsPage = ContentPage::where('slug', 'terms')->first();
        $privacyPage = ContentPage::where('slug', 'privacy')->first();

        return view('admin.content.form', [
            'admin' => $admin,
            'termsPage' => $termsPage,
            'privacyPage' => $privacyPage,
            'slug' => $slug,
            'success' => false,
        ]);
    }

    public function update(Request $request, string $slug)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        if (!isset(self::CONTENT_SLUGS[$slug])) {
            return redirect('/admin/dashboard');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $page = ContentPage::where('slug', $slug)->first();

        if ($page) {
            $page->update([
                'title' => $request->title,
                'content' => $request->content,
                'updated_by' => $admin->id,
                'updated_at' => now(),
            ]);
        } else {
            $page = ContentPage::create([
                'slug' => $slug,
                'title' => $request->title,
                'content' => $request->content,
                'updated_by' => $admin->id,
                'updated_at' => now(),
            ]);
        }

        $termsPage = ContentPage::where('slug', 'terms')->first();
        $privacyPage = ContentPage::where('slug', 'privacy')->first();

        return view('admin.content.form', [
            'admin' => $admin,
            'termsPage' => $termsPage,
            'privacyPage' => $privacyPage,
            'slug' => $slug,
            'success' => true,
        ]);
    }
}
