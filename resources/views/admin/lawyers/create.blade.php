@extends('layouts.admin-tailwick')
@section('title', 'Create Lawyer')

@section('content')
<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">Create Lawyer</h4>

    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a href="/admin/dashboard" class="text-sm font-medium text-default-700">Dashboard</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="/admin/lawyers" class="text-sm font-medium text-default-700">Lawyers</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Create</a>
    </div>
</div>
<!-- Page Title End -->

@if($errors->any())
<div class="mb-4 p-3 bg-danger/10 border border-danger/20 rounded text-danger text-sm">
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="/admin/lawyers/create" id="create-lawyer-form">
    @csrf
    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-8 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title">Account Information</h6>

                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                        <div class="col-span-1">
                            <label for="full_name" class="inline-block mb-2 text-sm text-default-800 font-medium">Full Name <span class="text-danger">*</span></label>
                            <input id="full_name" name="full_name" type="text" class="form-input" placeholder="Enter full name" value="{{ old('full_name') }}" required>
                        </div>

                        <div class="col-span-1">
                            <label for="email" class="inline-block mb-2 text-sm text-default-800 font-medium">Email <span class="text-danger">*</span></label>
                            <input id="email" name="email" type="email" class="form-input" placeholder="Enter email address" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                        <div class="col-span-1">
                            <label for="phone" class="inline-block mb-2 text-sm text-default-800 font-medium">Phone <span class="text-danger">*</span></label>
                            <input id="phone" name="phone" type="text" class="form-input" placeholder="Enter phone number" value="{{ old('phone') }}" required>
                        </div>

                        <div class="col-span-1">
                            <label for="password" class="inline-block mb-2 text-sm text-default-800 font-medium">Password <span class="text-danger">*</span></label>
                            <input id="password" name="password" type="password" class="form-input" placeholder="Minimum 8 characters" required>
                            <p class="mt-1 text-default-400 text-xs">Minimum 8 characters</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-6">
                <div class="card-body">
                    <h6 class="mb-4 card-title">Professional Information</h6>

                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                        <div class="col-span-1">
                            <label for="bar_number" class="inline-block mb-2 text-sm text-default-800 font-medium">Bar Number</label>
                            <input id="bar_number" name="bar_number" type="text" class="form-input" placeholder="Enter bar number" value="{{ old('bar_number') }}">
                        </div>

                        <div class="col-span-1">
                            <label for="years_of_experience" class="inline-block mb-2 text-sm text-default-800 font-medium">Years of Experience</label>
                            <input id="years_of_experience" name="years_of_experience" type="number" class="form-input" placeholder="0" value="{{ old('years_of_experience', 0) }}" min="0">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 mb-5">
                        <label for="bio" class="inline-block mb-2 text-sm text-default-800 font-medium">Bio</label>
                        <textarea id="bio" name="bio" class="form-textarea" placeholder="Enter lawyer bio / description" rows="4">{{ old('bio') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title">Specializations</h6>
                    <p class="text-default-500 text-xs mb-3">Select one specialization</p>

                    <div class="flex flex-wrap gap-2">
                        @forelse($categories as $cat)
                        <button type="button" class="spec-btn px-3 py-1.5 text-xs text-default-500 btn border border-default-200 dark:border-white/14 hover:bg-primary/10 hover:border-primary hover:text-primary focus:bg-primary/10 focus:border-primary rounded transition-all" data-id="{{ $cat->id }}">
                            {{ $cat->name }}
                        </button>
                        @empty
                        <p class="text-default-500 text-sm">No categories available. <a href="/admin/categories" class="text-primary">Create one</a> first.</p>
                        @endforelse
                    </div>

                    <input type="hidden" name="specializations" id="specializations_input" value="[]">
                </div>
            </div>

            <div class="card mt-6">
                <div class="card-body">
                    <h6 class="mb-4 card-title">Actions</h6>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="btn bg-primary text-white w-full">
                            <i data-lucide="plus" class="size-4 me-1"></i>
                            Create Lawyer
                        </button>

                        <a href="/admin/lawyers" class="btn bg-default-200 text-default-700 w-full">
                            <i data-lucide="x" class="size-4 me-1"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.spec-btn');
    let selectedId = null;

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all
            buttons.forEach(b => {
                b.classList.remove('bg-primary/10', 'border-primary', 'text-primary');
                b.classList.add('text-default-500', 'border-default-200');
            });

            // Toggle selection
            const id = parseInt(this.dataset.id);
            if (selectedId === id) {
                selectedId = null;
            } else {
                selectedId = id;
                this.classList.add('bg-primary/10', 'border-primary', 'text-primary');
                this.classList.remove('text-default-500', 'border-default-200');
            }
        });
    });

    document.getElementById('create-lawyer-form').addEventListener('submit', function() {
        const ids = selectedId ? [selectedId] : [];
        document.getElementById('specializations_input').value = JSON.stringify(ids);
    });
});
</script>
@endsection
