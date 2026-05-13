@extends('layouts.admin-tailwick')
@section('title', 'Categories')

@section('content')
<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">Categories</h4>

    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a href="/admin/dashboard" class="text-sm font-medium text-default-700">Dashboard</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Categories</a>
    </div>
</div>
<!-- Page Title End -->

<div class="card">
    <div class="card-header">
        <h6 class="card-title">Categories {{ $categories->count() > 0 ? '('.$categories->count().')' : '' }}</h6>
        <button class="btn btn-sm bg-primary text-white" aria-haspopup="dialog" aria-expanded="false" aria-controls="categoryAdd" data-hs-overlay="#categoryAdd">
            <i data-lucide="plus" class="size-4 me-1"></i>Add Category
        </button>
    </div>

    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-default-200 dark:divide-white/14">
                        <thead class="bg-default-100 font-normal whitespace-nowrap">
                            <tr class="text-sm text-default-800">
                                <th scope="col" class="px-3.5 py-3 font-medium text-start">#</th>
                                <th scope="col" class="px-3.5 py-3 font-medium text-start">Category</th>
                                <th scope="col" class="px-3.5 py-3 font-medium text-start">Description</th>
                                <th scope="col" class="px-3.5 py-3 font-medium text-start">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-200 dark:divide-white/14">
                            @forelse($categories as $index => $cat)
                            <tr class="text-sm text-default-800">
                                <td class="px-3.5 py-3">{{ $index + 1 }}</td>
                                <td class="px-3.5 py-3 font-medium">{{ $cat->name }}</td>
                                <td class="px-3.5 py-3 text-default-500">{{ $cat->description ?? '-' }}</td>
                                <td class="px-3.5 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="javascript:void(0)" class="btn btn-icon size-8 bg-default-100 hover:bg-primary/10 text-default-600 hover:text-primary rounded" aria-haspopup="dialog" aria-expanded="false" aria-controls="categoryEdit{{ $cat->id }}" data-hs-overlay="#categoryEdit{{ $cat->id }}">
                                            <i data-lucide="pencil" class="size-4"></i>
                                        </a>

                                        <form method="POST" action="/admin/categories/{{ $cat->id }}/toggle" class="inline">
                                            @csrf
                                            @if($cat->is_active)
                                            <button type="submit" class="btn btn-icon size-8 bg-success/10 hover:bg-danger/10 text-success hover:text-danger rounded" title="Active - Click to deactivate">
                                                <i data-lucide="toggle-right" class="size-4"></i>
                                            </button>
                                            @else
                                            <button type="submit" class="btn btn-icon size-8 bg-default-100 hover:bg-success/10 text-default-400 hover:text-success rounded" title="Inactive - Click to activate">
                                                <i data-lucide="toggle-left" class="size-4"></i>
                                            </button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-3.5 py-8 text-center text-default-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <i data-lucide="folder" class="size-10 text-default-300"></i>
                                        <p>No categories found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <p class="text-default-500 text-sm">Showing <b>{{ $categories->count() }}</b> Results</p>
    </div>
</div>

<!-- Add Category Modal -->
<div id="categoryAdd" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="categoryAddLabel">
    <div class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 max-w-md lg:w-full m-3 mx-auto min-h-[calc(100%-56px)] flex items-center">
        <div class="w-full flex flex-col card border border-default-200 dark:border-white/14 shadow-2xs rounded-xl pointer-events-auto">
            <div class="card-header">
                <h3 id="categoryAddLabel" class="font-semibold text-base text-default-800">Add New Category</h3>
                <button type="button" aria-label="Close" data-hs-overlay="#categoryAdd">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <form method="POST" action="/admin/categories/create">
                @csrf
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-default-700">Name</label>
                            <input type="text" name="name" class="form-input" placeholder="Category name" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-default-700">Description</label>
                            <textarea name="description" class="form-textarea" placeholder="Category description (optional)" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <button type="button" class="btn bg-default-200 text-default-700" data-hs-overlay="#categoryAdd">Cancel</button>
                    <button type="submit" class="btn bg-primary text-white">
                        <i data-lucide="plus" class="size-4 me-1"></i> Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modals -->
@foreach($categories as $cat)
<div id="categoryEdit{{ $cat->id }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="categoryEditLabel{{ $cat->id }}">
    <div class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 max-w-md lg:w-full m-3 mx-auto min-h-[calc(100%-56px)] flex items-center">
        <div class="w-full flex flex-col card border border-default-200 dark:border-white/14 shadow-2xs rounded-xl pointer-events-auto">
            <div class="card-header">
                <h3 id="categoryEditLabel{{ $cat->id }}" class="font-semibold text-base text-default-800">Edit Category</h3>
                <button type="button" aria-label="Close" data-hs-overlay="#categoryEdit{{ $cat->id }}">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <form method="POST" action="/admin/categories/{{ $cat->id }}/edit">
                @csrf
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-default-700">Name</label>
                            <input type="text" name="name" class="form-input" value="{{ $cat->name }}" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-default-700">Description</label>
                            <textarea name="description" class="form-textarea" rows="3">{{ $cat->description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <button type="button" class="btn bg-default-200 text-default-700" data-hs-overlay="#categoryEdit{{ $cat->id }}">Cancel</button>
                    <button type="submit" class="btn bg-primary text-white">
                        <i data-lucide="save" class="size-4 me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
