@extends('layouts.admin-tailwick')
@section('title', 'Client Detail')

@section('content')
<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">Client Detail</h4>

    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a href="/admin/dashboard" class="text-sm font-medium text-default-700">Dashboard</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="/admin/clients" class="text-sm font-medium text-default-700">Clients</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">{{ $client->full_name }}</a>
    </div>
</div>
<!-- Page Title End -->

<div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-8 col-span-1">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-4 card-title">Account Information</h6>

                <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                    <div class="col-span-1">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Full Name</label>
                        <input type="text" class="form-input bg-default-100" value="{{ $client->full_name }}" disabled>
                    </div>

                    <div class="col-span-1">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Email</label>
                        <input type="text" class="form-input bg-default-100" value="{{ $client->email }}" disabled>
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                    <div class="col-span-1">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Phone</label>
                        <input type="text" class="form-input bg-default-100" value="{{ $client->phone ?? '-' }}" disabled>
                    </div>

                    <div class="col-span-1">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Registered</label>
                        <input type="text" class="form-input bg-default-100" value="{{ $client->created_at?->format('d M Y, H:i') ?? '-' }}" disabled>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="/admin/clients" class="btn bg-default-200 text-default-700">
                        <i data-lucide="arrow-left" class="size-4 me-1"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Cases History -->
        <div class="card mt-6">
            <div class="card-header">
                <h6 class="card-title">Cases History</h6>
            </div>

            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-default-200 dark:divide-white/14">
                                <thead class="bg-default-150">
                                    <tr class="text-sm font-normal text-default-700 whitespace-nowrap">
                                        <th scope="col" class="px-3.5 py-3 text-start">Case Number</th>
                                        <th scope="col" class="px-3.5 py-3 text-start">Status</th>
                                        <th scope="col" class="px-3.5 py-3 text-start">Total Price</th>
                                        <th scope="col" class="px-3.5 py-3 text-start">Created</th>
                                        <th scope="col" class="px-3.5 py-3 text-start">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cases as $c)
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-warning/10 text-warning',
                                            'matched' => 'bg-info/10 text-info',
                                            'waiting_payment' => 'bg-default-200 text-default-600',
                                            'paid' => 'bg-primary/10 text-primary',
                                            'in_progress' => 'bg-primary/10 text-primary',
                                            'completed' => 'bg-success/10 text-success',
                                            'cancelled' => 'bg-danger/10 text-danger',
                                            'expired' => 'bg-default-200 text-default-600',
                                        ];
                                        $statusClass = $statusColors[$c->status] ?? 'bg-default-200 text-default-600';
                                    @endphp
                                    <tr class="text-default-800 font-normal text-sm whitespace-nowrap">
                                        <td class="px-3.5 py-3 font-semibold">{{ $c->case_number }}</td>
                                        <td class="px-3.5 py-3">
                                            <span class="py-0.5 px-2.5 inline-flex items-center text-xs font-medium {{ $statusClass }} rounded">
                                                {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-3">{{ $c->total_price ? 'Rp '.number_format($c->total_price) : '-' }}</td>
                                        <td class="px-3.5 py-3">{{ $c->created_at?->format('d M, Y') }}</td>
                                        <td class="px-3.5 py-3">
                                            <a href="/admin/cases/{{ $c->id }}" class="text-primary text-sm hover:underline">
                                                <i data-lucide="eye" class="size-3 inline"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-3.5 py-8 text-center text-default-500">
                                            <div class="flex flex-col items-center gap-2">
                                                <i data-lucide="briefcase" class="size-10 text-default-300"></i>
                                                <p>No cases found</p>
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

            @if($cases->count())
            <div class="card-footer">
                <p class="text-default-500 text-sm">Showing <b>{{ $cases->count() }}</b> cases</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-4 col-span-1">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h6 class="card-title">Status</h6>
                    @if($client->is_active)
                    <span class="py-0.5 px-2.5 inline-flex items-center gap-x-1 text-xs font-medium bg-success/10 text-success rounded-full">
                        <span class="size-1.5 bg-success rounded-full"></span>
                        Active
                    </span>
                    @else
                    <span class="py-0.5 px-2.5 inline-flex items-center gap-x-1 text-xs font-medium bg-danger/10 text-danger rounded-full">
                        <span class="size-1.5 bg-danger rounded-full"></span>
                        Inactive
                    </span>
                    @endif
                </div>

                <p class="text-default-500 text-xs mb-4">
                    @if($client->is_active)
                        This client is currently active and can create cases.
                    @else
                        This client is inactive and cannot access the platform.
                    @endif
                </p>
            </div>
            <div class="card-footer">
                <form method="POST" action="/admin/clients/{{ $client->id }}/toggle-active">
                    @csrf
                    @if($client->is_active)
                    <button type="submit" class="btn btn-sm w-full bg-danger/10 text-danger hover:bg-danger hover:text-white transition-all">
                        <i data-lucide="user-x" class="size-4 me-1"></i> Deactivate Client
                    </button>
                    @else
                    <button type="submit" class="btn btn-sm w-full bg-success/10 text-success hover:bg-success hover:text-white transition-all">
                        <i data-lucide="user-check" class="size-4 me-1"></i> Activate Client
                    </button>
                    @endif
                </form>
            </div>
        </div>

        <div class="card mt-6">
            <div class="card-body">
                <h6 class="mb-3 card-title">Summary</h6>

                <div class="flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <span class="text-default-500 text-sm">Total Cases</span>
                        <span class="text-default-800 font-semibold text-sm">{{ $cases->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-default-500 text-sm">Completed</span>
                        <span class="text-success font-semibold text-sm">{{ $cases->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-default-500 text-sm">In Progress</span>
                        <span class="text-primary font-semibold text-sm">{{ $cases->where('status', 'in_progress')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-default-500 text-sm">Cancelled</span>
                        <span class="text-danger font-semibold text-sm">{{ $cases->where('status', 'cancelled')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
