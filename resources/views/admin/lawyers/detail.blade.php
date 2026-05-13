@extends('layouts.admin-tailwick')
@section('title', 'Lawyer Detail')

@section('content')
<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">Lawyer Profile</h4>

    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a href="/admin/dashboard" class="text-sm font-medium text-default-700">Dashboard</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="/admin/lawyers" class="text-sm font-medium text-default-700">Lawyers</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">{{ $lawyer->full_name }}</a>
    </div>
</div>
<!-- Page Title End -->

<!-- Hidden form for profile update -->
<form method="POST" action="/admin/lawyers/{{ $lawyer->id }}/update-profile" id="profile-form">
    @csrf
    <input type="hidden" name="specializations" id="specializations_input" value="{{ $lawyer->lawyerProfile?->specializations ?? '[]' }}">
</form>

<div class="container-fluid">
    <!-- Profile Header Card -->
    <div class="mb-5">
        <div class="card !rounded-none">
            <div class="card-body !px-2.5">
                <div class="grid lg:grid-cols-12 grid-cols-1 gap-5">
                    <!-- Avatar -->
                    <div class="col-span-1">
                        <div class="relative inline-block rounded-full shadow-md size-20 bg-default-100 dark:bg-default-200 profile-user xl:size-28">
                            @if($lawyer->avatar_url)
                                <img src="{{ $lawyer->avatar_url }}" alt="{{ $lawyer->full_name }}" class="object-cover border-0 rounded-full img-thumbnail user-profile-image size-20 xl:size-28">
                            @else
                                <div class="size-20 xl:size-28 flex items-center justify-center rounded-full bg-primary/10 text-primary text-3xl xl:text-4xl font-bold">
                                    {{ strtoupper(substr($lawyer->full_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="lg:col-span-9 col-span-1">
                        <h5 class="mb-1 flex items-center gap-1 text-lg">
                            {{ $lawyer->full_name }}
                            @if($lawyer->is_active)
                                <i data-lucide="badge-check" class="size-4 text-info fill-info/10"></i>
                            @endif
                        </h5>

                        <div class="flex gap-3 mb-4 flex-wrap">
                            <p class="text-default-500 flex gap-1 items-center text-sm">
                                <i data-lucide="scale" class="fill-default-100 size-4"></i>
                                Lawyer
                            </p>

                            <p class="text-default-500 flex gap-1 items-center text-sm">
                                <i data-lucide="mail" class="fill-default-100 size-4"></i>
                                {{ $lawyer->email }}
                            </p>

                            @if($lawyer->phone)
                            <p class="text-default-500 flex gap-1 items-center text-sm">
                                <i data-lucide="phone" class="fill-default-100 size-4"></i>
                                {{ $lawyer->phone }}
                            </p>
                            @endif
                        </div>

                        <ul class="flex flex-wrap gap-3 mt-4 text-center divide-x divide-default-200 dark:divide-white/14">
                            <li class="px-5">
                                <h5 class="text-lg">{{ $lawyer->lawyerProfile?->years_of_experience ?? 0 }}</h5>
                                <p class="text-default-500 text-sm">Years Exp.</p>
                            </li>

                            <li class="px-5">
                                <h5 class="text-lg">{{ $lawyer->lawyerProfile?->bar_number ?? '-' }}</h5>
                                <p class="text-default-500 text-sm">Bar Number</p>
                            </li>

                            <li class="px-5">
                                <h5 class="text-lg">
                                    @if($lawyer->is_active)
                                        <span class="text-success">Active</span>
                                    @else
                                        <span class="text-danger">Inactive</span>
                                    @endif
                                </h5>
                                <p class="text-default-500 text-sm">Status</p>
                            </li>

                            <li class="px-5">
                                <h5 class="text-lg">{{ $lawyer->created_at?->format('d M, Y') }}</h5>
                                <p class="text-default-500 text-sm">Joined</p>
                            </li>
                        </ul>

                        @if($lawyer->lawyerProfile?->bio)
                        <p class="mt-4 text-default-500 text-sm">{{ $lawyer->lawyerProfile->bio }}</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="lg:col-span-2 col-span-1">
                        <div class="flex gap-2 2xl:justify-end flex-wrap">
                            <form method="POST" action="/admin/lawyers/{{ $lawyer->id }}/toggle-active">
                                @csrf
                                @if($lawyer->is_active)
                                <button type="submit" class="btn bg-danger/10 text-danger hover:bg-danger hover:text-white transition-all">
                                    <i data-lucide="user-x" class="size-4 me-1"></i> Deactivate
                                </button>
                                @else
                                <button type="submit" class="btn bg-success/10 text-success hover:bg-success hover:text-white transition-all">
                                    <i data-lucide="user-check" class="size-4 me-1"></i> Activate
                                </button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="!px-2.5">
                <nav class="flex gap-1 flex-wrap" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                    <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-primary hs-tab-active:text-primary py-2 px-4 inline-flex items-center gap-2 border-b border-transparent text-sm whitespace-nowrap text-default-500 hover:text-primary focus:outline-hidden focus:text-primary active" id="profileInfoTab" aria-selected="true" data-hs-tab="#profileInfo" aria-controls="profileInfo" role="tab">
                        Profile Info
                    </button>

                    <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-primary hs-tab-active:text-primary py-2 px-4 inline-flex items-center gap-2 border-b border-transparent text-sm whitespace-nowrap text-default-500 hover:text-primary focus:outline-hidden focus:text-primary" id="documentsTab" aria-selected="false" data-hs-tab="#documents" aria-controls="documents" role="tab">
                        Documents
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <!-- Tab: Profile Info -->
    <div id="profileInfo" role="tabpanel" aria-labelledby="profileInfoTab">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-1 text-[15px]">Professional Information</h6>
                <p class="mb-4 text-default-500 text-sm">Update the lawyer's professional details here.</p>

                <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
                    <div class="lg:col-span-4 col-span-1">
                        <label class="inline-block mb-2 font-medium text-sm">Bar Number</label>
                        <input type="text" name="bar_number" class="form-input" placeholder="Enter bar number" value="{{ $lawyer->lawyerProfile?->bar_number }}" form="profile-form">
                    </div>

                    <div class="lg:col-span-4 col-span-1">
                        <label class="inline-block mb-2 font-medium text-sm">Years of Experience</label>
                        <input type="number" name="years_of_experience" class="form-input" placeholder="0" value="{{ $lawyer->lawyerProfile?->years_of_experience ?? 0 }}" min="0" form="profile-form">
                    </div>

                    <div class="lg:col-span-4 col-span-1">
                        <label class="inline-block mb-2 font-medium text-sm">Specialization</label>
                        <select name="specialization_select" id="specialization_select" class="form-input" form="profile-form">
                            <option value="">Select one specialization</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ in_array($cat->id, $specIds) ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-12 col-span-1">
                        <label class="block mb-2 font-medium text-sm">Bio</label>
                        <textarea name="bio" class="w-full form-textarea" placeholder="Enter lawyer bio / description" rows="4" form="profile-form">{{ $lawyer->lawyerProfile?->bio }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-6 gap-4">
                    <button type="submit" form="profile-form" class="btn bg-primary text-white">Update Profile</button>
                    <a href="/admin/lawyers" class="btn bg-danger/10 text-danger hover:bg-danger hover:text-white">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Documents -->
    <div id="documents" class="hidden" role="tabpanel" aria-labelledby="documentsTab">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-1 text-[15px]">Uploaded Documents</h6>
                <p class="mb-4 text-default-500 text-sm">Documents uploaded by the lawyer for verification.</p>

                @if($lawyer->lawyerDocuments && $lawyer->lawyerDocuments->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200 dark:divide-white/14">
                        <thead class="bg-default-150">
                            <tr class="text-sm font-normal text-default-700 whitespace-nowrap">
                                <th scope="col" class="px-3.5 py-3 text-start">Title</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Type</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Size</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lawyer->lawyerDocuments as $doc)
                            <tr class="text-default-800 font-normal text-sm">
                                <td class="px-3.5 py-3">{{ $doc->title }}</td>
                                <td class="px-3.5 py-3">
                                    <span class="py-0.5 px-2.5 text-xs font-medium bg-primary/10 text-primary rounded">{{ $doc->file_type }}</span>
                                </td>
                                <td class="px-3.5 py-3">{{ $doc->file_size ? number_format($doc->file_size / 1024, 1) . ' KB' : '-' }}</td>
                                <td class="px-3.5 py-3">
                                    <a href="{{ $doc->file_url }}" target="_blank" class="btn btn-sm bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all">
                                        <i data-lucide="external-link" class="size-3 me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <i data-lucide="file-x" class="size-12 text-default-300 mx-auto mb-3"></i>
                    <p class="text-default-500">No documents uploaded yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('specialization_select');

    document.getElementById('profile-form').addEventListener('submit', function() {
        const val = select.value;
        const ids = val ? [parseInt(val)] : [];
        document.getElementById('specializations_input').value = JSON.stringify(ids);
    });
});
</script>
@endsection
