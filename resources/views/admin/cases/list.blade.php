@extends('layouts.admin-tailwick')
@section('title', 'Cases')

@section('content')
<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">Cases</h4>

    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a href="/admin/dashboard" class="text-sm font-medium text-default-700">Dashboard</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Cases</a>
    </div>
</div>
<!-- Page Title End -->

<div class="card">
    <div class="card-header">
        <h6 class="card-title">Cases List</h6>
    </div>

    <!-- Status Filter Tabs -->
    <div class="card-header">
        <div class="flex items-center flex-wrap gap-3">
            <a href="/admin/cases" class="btn btn-sm {{ !$status ? 'bg-primary text-white' : 'text-default-500 hover:text-primary border-0 bg-transparent' }} font-medium">
                <i data-lucide="boxes" class="size-4 me-1"></i>All Cases
            </a>
            <a href="/admin/cases?status=pending" class="btn btn-sm {{ $status === 'pending' ? 'bg-primary text-white' : 'text-default-500 hover:text-primary border-0 bg-transparent' }} font-medium">
                <i data-lucide="loader" class="size-4 me-1"></i>Pending
            </a>
            <a href="/admin/cases?status=matched" class="btn btn-sm {{ $status === 'matched' ? 'bg-primary text-white' : 'text-default-500 hover:text-primary border-0 bg-transparent' }} font-medium">
                <i data-lucide="user-check" class="size-4 me-1"></i>Matched
            </a>
            <a href="/admin/cases?status=paid" class="btn btn-sm {{ $status === 'paid' ? 'bg-primary text-white' : 'text-default-500 hover:text-primary border-0 bg-transparent' }} font-medium">
                <i data-lucide="credit-card" class="size-4 me-1"></i>Paid
            </a>
            <a href="/admin/cases?status=in_progress" class="btn btn-sm {{ $status === 'in_progress' ? 'bg-primary text-white' : 'text-default-500 hover:text-primary border-0 bg-transparent' }} font-medium">
                <i data-lucide="refresh-cw" class="size-4 me-1"></i>In Progress
            </a>
            <a href="/admin/cases?status=completed" class="btn btn-sm {{ $status === 'completed' ? 'bg-primary text-white' : 'text-default-500 hover:text-primary border-0 bg-transparent' }} font-medium">
                <i data-lucide="package-check" class="size-4 me-1"></i>Completed
            </a>
            <a href="/admin/cases?status=cancelled" class="btn btn-sm {{ $status === 'cancelled' ? 'bg-primary text-white' : 'text-default-500 hover:text-primary border-0 bg-transparent' }} font-medium">
                <i data-lucide="package-x" class="size-4 me-1"></i>Cancelled
            </a>
            <a href="/admin/cases?status=expired" class="btn btn-sm {{ $status === 'expired' ? 'bg-primary text-white' : 'text-default-500 hover:text-primary border-0 bg-transparent' }} font-medium">
                <i data-lucide="clock" class="size-4 me-1"></i>Expired
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="card-header">
        <div class="md:flex items-center md:space-y-0 space-y-4 gap-3">
            <div class="relative w-full">
                <input type="text" id="search-cases" class="form-input form-input-sm ps-9" placeholder="Search by case number...">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                    <i data-lucide="search" class="size-3.5 flex items-center text-default-500 fill-default-100"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-default-200 dark:divide-white/14">
                        <thead class="bg-default-150">
                            <tr class="text-sm font-normal text-default-700 whitespace-nowrap">
                                <th scope="col" class="px-3.5 py-3 text-start">#</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Case Number</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Client</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Status</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Total Price</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Created</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($cases as $index => $c)
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
                            <tr class="text-default-800 font-normal text-sm whitespace-nowrap case-row"
                                data-case="{{ strtolower($c->case_number) }}">
                                <td class="px-3.5 py-3 text-sm">{{ $cases->firstItem() + $index }}</td>
                                <td class="px-3.5 py-3 font-semibold">
                                    <a href="/admin/cases/{{ $c->id }}" class="text-default-800 hover:text-primary">{{ $c->case_number }}</a>
                                </td>
                                <td class="px-3.5 py-3">{{ $c->client?->full_name ?? '-' }}</td>
                                <td class="px-3.5 py-3">
                                    <span class="py-0.5 px-2.5 inline-flex items-center text-xs font-medium {{ $statusClass }} rounded">
                                        {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                                    </span>
                                </td>
                                <td class="px-3.5 py-3">{{ $c->total_price ? 'Rp '.number_format($c->total_price) : '-' }}</td>
                                <td class="px-3.5 py-3">{{ $c->created_at?->format('d M, Y') }}</td>
                                <td class="px-3.5 py-3">
                                    <a href="/admin/cases/{{ $c->id }}" class="btn size-7.5 bg-default-200 hover:bg-default-600 text-default-500">
                                        <i data-lucide="eye" class="size-4"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-3.5 py-8 text-center text-default-500">
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

    <div class="card-footer flex items-center justify-between">
        <p class="text-default-500 text-sm">Showing {{ $cases->firstItem() ?? 0 }} to {{ $cases->lastItem() ?? 0 }} of {{ $cases->total() }} cases</p>
        {{ $cases->links('pagination::tailwind') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-cases');
    const rows = document.querySelectorAll('.case-row');

    searchInput.addEventListener('input', function() {
        const search = this.value.toLowerCase();
        rows.forEach(row => {
            const caseNum = row.dataset.case;
            row.style.display = (!search || caseNum.includes(search)) ? '' : 'none';
        });
    });
});
</script>
@endsection
