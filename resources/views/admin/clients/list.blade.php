@extends('layouts.admin-tailwick')
@section('title', 'Clients')

@section('content')
<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">Clients</h4>

    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a href="/admin/dashboard" class="text-sm font-medium text-default-700">Dashboard</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Clients</a>
    </div>
</div>
<!-- Page Title End -->

<div class="card">
    <div class="card-header">
        <h6 class="card-title">Clients List</h6>
    </div>

    <div class="card-header">
        <div class="md:flex items-center md:space-y-0 space-y-4 gap-3">
            <div class="relative w-full">
                <input type="text" id="search-clients" class="form-input form-input-sm ps-9" placeholder="Search by name, email, phone...">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                    <i data-lucide="search" class="size-3.5 flex items-center text-default-500 fill-default-100"></i>
                </div>
            </div>

            <select id="filter-status" class="form-input form-input-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-default-200 dark:divide-white/14">
                        <thead class="bg-default-150">
                            <tr class="text-sm font-normal text-default-700 whitespace-nowrap">
                                <th class="ps-4 text-start">
                                    <input type="checkbox" class="form-checkbox" id="checkbox-all">
                                </th>
                                <th scope="col" class="px-3.5 py-3 text-start">ID</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Name</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Email</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Phone</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Joined</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Status</th>
                                <th scope="col" class="px-3.5 py-3 text-start">Action</th>
                            </tr>
                        </thead>

                        <tbody id="clients-tbody">
                            @forelse($clients as $client)
                            <tr class="text-default-800 font-normal text-sm whitespace-nowrap client-row"
                                data-name="{{ strtolower($client->full_name) }}"
                                data-email="{{ strtolower($client->email) }}"
                                data-phone="{{ strtolower($client->phone ?? '') }}"
                                data-status="{{ $client->is_active ? 'active' : 'inactive' }}">
                                <td class="py-3 ps-4">
                                    <input type="checkbox" class="form-checkbox">
                                </td>
                                <td class="px-3.5 py-3 text-sm text-primary">#{{ $client->id }}</td>
                                <td class="flex py-3 px-3.5 items-center gap-3">
                                    @if($client->avatar_url)
                                    <div class="size-10 rounded-full bg-default-200 overflow-hidden">
                                        <img src="{{ $client->avatar_url }}" alt="{{ $client->full_name }}" class="size-10 rounded-full object-cover">
                                    </div>
                                    @else
                                    <div class="size-10 flex items-center justify-center rounded-full bg-cyan-500/10 text-cyan-600 font-semibold">
                                        {{ strtoupper(substr($client->full_name, 0, 2)) }}
                                    </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-1 font-semibold">
                                            <a href="/admin/clients/{{ $client->id }}" class="text-default-800 hover:text-primary">{{ $client->full_name }}</a>
                                        </h6>
                                    </div>
                                </td>
                                <td class="py-3 px-3.5">{{ $client->email }}</td>
                                <td class="py-3 px-3.5">{{ $client->phone ?? '-' }}</td>
                                <td class="py-3 px-3.5">{{ $client->created_at?->format('d M, Y') ?? '-' }}</td>
                                <td class="px-3.5 py-3">
                                    @if($client->is_active)
                                    <span class="py-0.5 px-2.5 inline-flex items-center gap-x-1 text-xs font-medium bg-success/10 text-success rounded">
                                        <i data-lucide="check-circle-2" class="size-3"></i>
                                        Active
                                    </span>
                                    @else
                                    <span class="py-0.5 px-2.5 inline-flex items-center gap-x-1 text-xs font-medium bg-danger/10 text-danger rounded">
                                        <i data-lucide="x-circle" class="size-3"></i>
                                        Inactive
                                    </span>
                                    @endif
                                </td>
                                <td class="px-3.5 py-3">
                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button" class="hs-dropdown-toggle btn size-7.5 bg-default-200 hover:bg-default-600 text-default-500" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            <i class="iconify lucide--ellipsis size-4"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu">
                                            <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-default-500 hover:bg-default-150 rounded" href="/admin/clients/{{ $client->id }}">
                                                <i data-lucide="eye" class="size-3"></i>
                                                Detail
                                            </a>

                                            <form method="POST" action="/admin/clients/{{ $client->id }}/toggle-active" class="inline">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-default-500 hover:bg-default-150 rounded w-full text-left">
                                                    @if($client->is_active)
                                                    <i data-lucide="x-circle" class="size-3"></i>
                                                    Deactivate
                                                    @else
                                                    <i data-lucide="check-circle-2" class="size-3"></i>
                                                    Activate
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-3.5 py-8 text-center text-default-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <i data-lucide="user" class="size-10 text-default-300"></i>
                                        <p>No clients found</p>
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
        <p class="text-default-500 text-sm">Showing <b>{{ $clients->count() }}</b> clients</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-clients');
    const filterStatus = document.getElementById('filter-status');
    const rows = document.querySelectorAll('.client-row');

    function filterRows() {
        const search = searchInput.value.toLowerCase();
        const status = filterStatus.value;

        rows.forEach(row => {
            const name = row.dataset.name;
            const email = row.dataset.email;
            const phone = row.dataset.phone;
            const rowStatus = row.dataset.status;

            const matchSearch = !search || name.includes(search) || email.includes(search) || phone.includes(search);
            const matchStatus = !status || rowStatus === status;

            row.style.display = (matchSearch && matchStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterRows);
    filterStatus.addEventListener('change', filterRows);
});
</script>
@endsection
