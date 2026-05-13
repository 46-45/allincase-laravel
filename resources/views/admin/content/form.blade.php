@extends('layouts.admin-tailwick')
@section('title', 'Content Pages')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endsection

@section('content')
<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">Content Editor</h4>

    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a href="/admin/dashboard" class="text-sm font-medium text-default-700">Dashboard</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Content Pages</a>
    </div>
</div>
<!-- Page Title End -->

@if($success)
<div class="mb-4 p-3 bg-success/10 border border-success/20 rounded text-success text-sm flex items-center gap-2">
    <i data-lucide="check-circle-2" class="size-4"></i>
    Content berhasil disimpan!
</div>
@endif

<!-- Tabs Navigation -->
<div class="card mb-5">
    <div class="px-4">
        <nav class="flex gap-1 flex-wrap" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
            <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-primary hs-tab-active:text-primary py-3 px-4 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap text-default-500 hover:text-primary focus:outline-hidden focus:text-primary {{ $slug === 'terms' ? 'active' : '' }}" id="termsTab" aria-selected="{{ $slug === 'terms' ? 'true' : 'false' }}" data-hs-tab="#termsContent" aria-controls="termsContent" role="tab">
                <i data-lucide="file-text" class="size-4"></i>
                Syarat & Ketentuan
            </button>

            <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-primary hs-tab-active:text-primary py-3 px-4 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap text-default-500 hover:text-primary focus:outline-hidden focus:text-primary {{ $slug === 'privacy' ? 'active' : '' }}" id="privacyTab" aria-selected="{{ $slug === 'privacy' ? 'true' : 'false' }}" data-hs-tab="#privacyContent" aria-controls="privacyContent" role="tab">
                <i data-lucide="shield" class="size-4"></i>
                Kebijakan Privasi
            </button>
        </nav>
    </div>
</div>

<!-- Tab: Syarat & Ketentuan -->
<div id="termsContent" role="tabpanel" aria-labelledby="termsTab" class="{{ $slug === 'privacy' ? 'hidden' : '' }}">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-1">Syarat & Ketentuan</h5>
            <p class="text-default-400 mb-4 text-sm">Edit konten Syarat & Ketentuan menggunakan editor di bawah.</p>

            <form method="POST" action="/admin/content/terms" id="terms-form">
                @csrf
                <div class="mb-4">
                    <label class="inline-block mb-2 font-medium text-sm">Title</label>
                    <input type="text" name="title" class="form-input" value="{{ $termsPage?->title ?? 'Syarat & Ketentuan' }}" required>
                </div>

                <div class="mb-4">
                    <label class="inline-block mb-2 font-medium text-sm">Content</label>
                    <div id="terms-editor" style="height: 350px">{!! $termsPage?->content ?? '' !!}</div>
                    <input type="hidden" name="content" id="terms-content-input">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit" class="btn bg-primary text-white">
                        <i data-lucide="save" class="size-4 me-1"></i> Save Syarat & Ketentuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tab: Kebijakan Privasi -->
<div id="privacyContent" role="tabpanel" aria-labelledby="privacyTab" class="{{ $slug === 'terms' ? 'hidden' : '' }}">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-1">Kebijakan Privasi</h5>
            <p class="text-default-400 mb-4 text-sm">Edit konten Kebijakan Privasi menggunakan editor di bawah.</p>

            <form method="POST" action="/admin/content/privacy" id="privacy-form">
                @csrf
                <div class="mb-4">
                    <label class="inline-block mb-2 font-medium text-sm">Title</label>
                    <input type="text" name="title" class="form-input" value="{{ $privacyPage?->title ?? 'Kebijakan Privasi' }}" required>
                </div>

                <div class="mb-4">
                    <label class="inline-block mb-2 font-medium text-sm">Content</label>
                    <div id="privacy-editor" style="height: 350px">{!! $privacyPage?->content ?? '' !!}</div>
                    <input type="hidden" name="content" id="privacy-content-input">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit" class="btn bg-primary text-white">
                        <i data-lucide="save" class="size-4 me-1"></i> Save Kebijakan Privasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill editors
    var termsQuill = new Quill('#terms-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    var privacyQuill = new Quill('#privacy-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    // On form submit, copy editor content to hidden input
    document.getElementById('terms-form').addEventListener('submit', function() {
        document.getElementById('terms-content-input').value = termsQuill.root.innerHTML;
    });

    document.getElementById('privacy-form').addEventListener('submit', function() {
        document.getElementById('privacy-content-input').value = privacyQuill.root.innerHTML;
    });
});
</script>
@endsection
