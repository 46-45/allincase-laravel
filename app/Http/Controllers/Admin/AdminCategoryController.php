<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $categories = Category::orderBy('name')->get();
        return view('admin.categories.list', compact('admin', 'categories'));
    }

    public function createPage(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        return view('admin.categories.form', ['admin' => $admin, 'category' => null]);
    }

    public function store(Request $request)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $request->validate(['name' => 'required|string|max:100']);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect('/admin/categories');
    }

    public function editPage(Request $request, int $catId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $category = Category::find($catId);
        if (!$category) return redirect('/admin/categories');

        return view('admin.categories.form', compact('admin', 'category'));
    }

    public function update(Request $request, int $catId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $request->validate(['name' => 'required|string|max:100']);

        $category = Category::find($catId);
        if ($category) {
            $category->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        }

        return redirect('/admin/categories');
    }

    public function toggle(Request $request, int $catId)
    {
        $admin = AdminAuthController::getAdmin($request);
        if (!$admin) return redirect('/admin/login');

        $category = Category::find($catId);
        if ($category) {
            $category->update(['is_active' => !$category->is_active]);
        }

        return redirect('/admin/categories');
    }
}
