@extends('layouts.admin-tailwick')
@section('title', 'Push Notification')

@section('content')
<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">Push Notification</h4>
    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a href="/admin/dashboard" class="text-sm font-medium text-default-700">Dashboard</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Push Notification</a>
    </div>
</div>

@if(session('success'))
<div class="mb-4 p-3 bg-success/10 border border-success/20 rounded text-success text-sm flex items-center gap-2">
    <i data-lucide="check-circle-2" class="size-4"></i>
    {{ session('success') }}
</div>
@endif

<div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
    <!-- Send Form -->
    <div class="lg:col-span-5 col-span-1">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title flex items-center gap-2">
                    <i data-lucide="send" class="size-4 text-primary"></i>
                    Kirim Notifikasi
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/notifications/send">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-default-700">Judul</label>
                        <input type="text" name="title" class="form-input" placeholder="Judul notifikasi" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-default-700">Pesan</label>
                        <textarea name="body" class="form-textarea" rows="4" placeholder="Isi pesan notifikasi..." required></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-default-700">Kirim ke</label>
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-3 p-3 border border-default-200 rounded-lg cursor-pointer hover:bg-default-50 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="radio" name="target" value="all" class="form-radio text-primary" checked>
                                <div>
                                    <p class="text-sm font-medium text-default-800">Semua Pengguna</p>
                                    <p class="text-xs text-default-500">Lawyer dan Klien</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 border border-default-200 rounded-lg cursor-pointer hover:bg-default-50 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="radio" name="target" value="lawyer" class="form-radio text-primary">
                                <div>
                                    <p class="text-sm font-medium text-default-800">Lawyer</p>
                                    <p class="text-xs text-default-500">Hanya akun lawyer</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 border border-default-200 rounded-lg cursor-pointer hover:bg-default-50 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="radio" name="target" value="client" class="form-radio text-primary">
                                <div>
                                    <p class="text-sm font-medium text-default-800">Klien</p>
                                    <p class="text-xs text-default-500">Hanya akun klien</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-primary text-white w-full">
                        <i data-lucide="send" class="size-4 me-2"></i>
                        Kirim Notifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification Log -->
    <div class="lg:col-span-7 col-span-1">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title flex items-center gap-2">
                    <i data-lucide="bell" class="size-4 text-primary"></i>
                    Log Broadcast
                </h6>
                <span class="text-xs text-default-500">{{ $logs->total() }} total</span>
            </div>

            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-default-200 dark:divide-white/14">
                                <thead class="bg-default-150">
                                    <tr class="text-sm font-normal text-default-700 whitespace-nowrap">
                                        <th scope="col" class="px-3.5 py-3 text-start">Penerima</th>
                                        <th scope="col" class="px-3.5 py-3 text-start">Judul</th>
                                        <th scope="col" class="px-3.5 py-3 text-start">Pesan</th>
                                        <th scope="col" class="px-3.5 py-3 text-start">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                    <tr class="text-default-800 font-normal text-sm">
                                        <td class="px-3.5 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="size-7 flex items-center justify-center rounded-full bg-primary/10 text-primary text-xs font-semibold">
                                                    {{ strtoupper(substr($log->user?->full_name ?? '?', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium">{{ $log->user?->full_name ?? '-' }}</p>
                                                    <p class="text-xs text-default-400">{{ $log->user?->role ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-3">
                                            <p class="text-sm font-medium">{{ $log->title }}</p>
                                        </td>
                                        <td class="px-3.5 py-3 max-w-xs">
                                            <p class="text-xs text-default-500 truncate">{{ $log->body }}</p>
                                        </td>
                                        <td class="px-3.5 py-3 whitespace-nowrap">
                                            <p class="text-xs text-default-500">{{ $log->created_at?->format('d M, H:i') }}</p>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-3.5 py-8 text-center text-default-500">
                                            <div class="flex flex-col items-center gap-2">
                                                <i data-lucide="bell-off" class="size-10 text-default-300"></i>
                                                <p>Belum ada log broadcast</p>
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

            <div class="card-footer flex items-center justify-between">
                <p class="text-default-500 text-sm">Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }}</p>
                {{ $logs->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
