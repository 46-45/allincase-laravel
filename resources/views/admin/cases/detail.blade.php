@extends('layouts.admin-tailwick')
@section('title', 'Case Detail')

@section('content')
<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">Overview</h4>

    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a href="/admin/dashboard" class="text-sm font-medium text-default-700">Dashboard</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="/admin/cases" class="text-sm font-medium text-default-700">Cases</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">{{ $case->case_number }}</a>
    </div>
</div>
<!-- Page Title End -->

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
    $statusClass = $statusColors[$case->status] ?? 'bg-default-200 text-default-600';
@endphp

<div class="grid lg:grid-cols-3 grid-cols-1 lg:gap-5">
    <!-- Left Column - Lawyer Photo & Info -->
    <div class="col-span-1">
        <div class="sticky top-24">
            <div class="card mb-5">
                <div class="card-body">
                    <!-- Lawyer Photo - Main -->
                    <div class="grid grid-cols-1 gap-5 mb-5">
                        <div class="col-span-1">
                            <div class="rounded-md bg-default-100 dark:bg-default-200 flex items-center justify-center p-8">
                                @if($case->lawyer?->avatar_url)
                                    <img src="{{ $case->lawyer->avatar_url }}" alt="{{ $case->lawyer->full_name }}" class="rounded-md max-h-64 object-cover">
                                @elseif($case->lawyer?->lawyerProfile?->photo_url)
                                    <img src="{{ $case->lawyer->lawyerProfile->photo_url }}" alt="{{ $case->lawyer->full_name }}" class="rounded-md max-h-64 object-cover">
                                @else
                                    <div class="size-48 flex items-center justify-center rounded-full bg-primary/10 text-primary text-6xl font-bold">
                                        {{ strtoupper(substr($case->lawyer?->full_name ?? '?', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-2">
                        <a href="/admin/cases" class="border border-primary w-full rounded btn text-primary hover:bg-primary/10 border-dashed">
                            <i data-lucide="arrow-left" class="size-3"></i>
                            Back to List
                        </a>

                        @if($case->lawyer)
                        <a href="/admin/lawyers/{{ $case->lawyer->id }}" class="bg-primary w-full rounded btn text-white hover:bg-primary/90">
                            View Lawyer
                        </a>
                        @else
                        <button class="bg-default-200 w-full rounded btn text-default-500" disabled>
                            No Lawyer
                        </button>
                        @endif
                    </div>

                    <!-- Quick Links -->
                    <div class="flex items-center gap-3 mt-3 justify-evenly">
                        @if($case->client)
                        <a href="/admin/clients/{{ $case->client->id }}" class="flex items-center gap-1.25 text-default-700 text-sm transition-all duration-300 hover:text-primary">
                            <i data-lucide="user" class="size-3"></i>
                            <span class="align-middle">View Client</span>
                        </a>
                        @endif

                        <a href="/admin/cases" class="flex items-center gap-1.25 text-default-700 text-sm transition-all duration-300 hover:text-primary">
                            <i data-lucide="list" class="size-3"></i>
                            <span class="align-middle">All Cases</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lawyer Store-like Card -->
            @if($case->lawyer)
            <div class="card">
                <div class="card-body border-b border-b-default-200">
                    <div class="flex justify-between flex-wrap gap-5">
                        <h6 class="text-default-800 font-semibold text-[15px] flex items-center gap-1.5">
                            <i data-lucide="scale" class="size-4"></i>
                            {{ $case->lawyer->full_name }}
                        </h6>

                        <span class="flex items-center gap-1.25 text-default-700">
                            <i data-lucide="briefcase" class="size-4 text-primary"></i>
                            {{ $case->lawyer->lawyerProfile?->years_of_experience ?? 0 }} yrs
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="flex gap-5 justify-between items-center">
                        <h6 class="text-default-700 text-sm flex items-center gap-1.5">
                            <i data-lucide="mail" class="size-4 text-default-500"></i>
                            {{ $case->lawyer->email }}
                        </h6>
                    </div>
                </div>
            </div>
            @endif

            <!-- Client Card -->
            <div class="card mt-5">
                <div class="card-body border-b border-b-default-200">
                    <div class="flex justify-between flex-wrap gap-5">
                        <h6 class="text-default-800 font-semibold text-[15px] flex items-center gap-1.5">
                            <i data-lucide="user" class="size-4"></i>
                            {{ $case->client?->full_name ?? 'Unknown Client' }}
                        </h6>
                    </div>
                </div>

                <div class="card-body">
                    <div class="flex flex-col gap-2">
                        <p class="text-default-700 text-sm flex items-center gap-1.5">
                            <i data-lucide="mail" class="size-4 text-default-500"></i>
                            {{ $case->client?->email ?? '-' }}
                        </p>
                        <p class="text-default-700 text-sm flex items-center gap-1.5">
                            <i data-lucide="phone" class="size-4 text-default-500"></i>
                            {{ $case->client?->phone ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Case Details -->
    <div class="lg:col-span-2 col-span-1">
        <div class="card">
            <div class="card-body">
                <!-- Header with status badge -->
                <div class="flex justify-between items-center">
                    <span class="px-2.5 py-0.5 text-xs inline-block font-semibold rounded {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $case->status)) }}
                    </span>

                    <div class="hs-dropdown relative inline-flex">
                        <button type="button" class="hs-dropdown-toggle btn size-7.5 bg-default-200 hover:bg-default-600 text-default-500 hover:text-white" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                            <i class="iconify lucide--ellipsis size-4"></i>
                        </button>

                        <div class="hs-dropdown-menu" role="menu">
                            <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded" href="/admin/cases">
                                <i data-lucide="list" class="size-3"></i>
                                All Cases
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <h5 class="mt-3 mb-1 text-xl text-default-800 font-semibold">{{ $case->case_number }}</h5>

                <!-- Meta info -->
                <ul class="flex flex-wrap text-sm items-center gap-4 mb-5 text-default-500">
                    @if($case->client)
                    <li><a href="/admin/clients/{{ $case->client->id }}" class="font-medium underline text-primary text-sm">{{ $case->client->full_name }}</a></li>
                    @endif
                    <li>Lawyer: <span class="font-medium text-sm">{{ $case->lawyer?->full_name ?? 'Not assigned' }}</span></li>
                    <li>Created: <span class="font-medium text-sm">{{ $case->created_at?->format('d M, Y') }}</span></li>
                </ul>

                <!-- Price Section -->
                @if($case->total_price)
                <div class="mb-4">
                    <p class="mb-1 text-success text-sm">Total Price</p>
                    <h4 class="text-default-900 font-semibold text-xl">Rp {{ number_format($case->total_price) }}</h4>
                </div>
                @endif

                <!-- Category -->
                <h6 class="mb-3 text-[15px] font-semibold text-default-800">Category</h6>
                <div class="flex gap-2 mb-5">
                    <span class="px-3 py-1.5 text-xs btn border border-primary bg-primary/10 text-primary rounded">
                        {{ $case->category?->name ?? 'Uncategorized' }}
                    </span>
                </div>

                <!-- Info Cards like Estimated Delivery -->
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-3 my-5">
                    <div class="flex items-center gap-4 p-4 border rounded-md border-default-200 dark:border-white/14">
                        <div class="flex items-center justify-center">
                            <i data-lucide="map-pin" class="size-6 text-default-500 fill-default-200"></i>
                        </div>
                        <div class="text-default-700 text-sm">
                            <h6 class="mb-1 text-default-800 font-semibold">Meeting Address</h6>
                            <p class="text-xs">{{ $case->meeting_address ?? 'Not set' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 border rounded-md border-default-200 dark:border-white/14">
                        <div class="flex items-center justify-center">
                            <i data-lucide="calendar" class="size-6 text-default-500 fill-default-200"></i>
                        </div>
                        <div class="text-default-700 text-sm">
                            <h6 class="mb-1 text-default-800 font-semibold">Meeting Time</h6>
                            <p class="text-xs">{{ $case->meeting_datetime ?? 'Not scheduled' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 border rounded-md border-default-200 dark:border-white/14">
                        <div class="flex items-center justify-center">
                            <i data-lucide="navigation" class="size-6 text-default-500 fill-default-200"></i>
                        </div>
                        <div class="text-default-700 text-sm">
                            <h6 class="mb-1 text-default-800 font-semibold">Distance</h6>
                            <p class="text-xs">{{ $case->distance_km ? $case->distance_km . ' km' : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Details Section -->
                <div class="mt-5">
                    <h6 class="card-title mb-3">Payment Details</h6>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <tbody>
                                <tr>
                                    <th class="px-3.5 py-2.5 font-semibold text-start text-sm w-48 text-default-500">Total Price</th>
                                    <td class="px-3.5 py-2.5 text-sm text-default-700">{{ $case->total_price ? 'Rp '.number_format($case->total_price) : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="px-3.5 py-2.5 font-semibold text-start text-sm w-48 text-default-500">Payment ID</th>
                                    <td class="px-3.5 py-2.5 text-sm text-default-700">{{ $case->payment_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="px-3.5 py-2.5 font-semibold text-start text-sm w-48 text-default-500">Paid At</th>
                                    <td class="px-3.5 py-2.5 text-sm text-default-700">{{ $case->paid_at ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="px-3.5 py-2.5 font-semibold text-start text-sm w-48 text-default-500">Status</th>
                                    <td class="px-3.5 py-2.5 text-sm text-default-700">
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $case->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disbursement Card -->
        @if($case->disbursement)
        <div class="card mt-5">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h6 class="card-title flex items-center gap-2">
                        <i data-lucide="banknote" class="size-5 text-success"></i>
                        Disbursement
                    </h6>
                    @php
                        $disbColors = [
                            'pending' => 'bg-warning/10 text-warning',
                            'processing' => 'bg-info/10 text-info',
                            'completed' => 'bg-success/10 text-success',
                            'failed' => 'bg-danger/10 text-danger',
                        ];
                        $disbClass = $disbColors[$case->disbursement->status] ?? 'bg-default-200 text-default-600';
                    @endphp
                    <span class="px-2.5 py-0.5 text-xs font-medium rounded {{ $disbClass }}">
                        {{ ucfirst($case->disbursement->status) }}
                    </span>
                </div>

                <div class="grid lg:grid-cols-3 grid-cols-1 gap-3 mb-4">
                    <div class="flex items-center gap-4 p-4 border rounded-md border-default-200 dark:border-white/14">
                        <div class="flex items-center justify-center">
                            <i data-lucide="wallet" class="size-6 text-success fill-success/20"></i>
                        </div>
                        <div class="text-default-700 text-sm">
                            <h6 class="mb-1 text-default-800 font-semibold">Amount</h6>
                            <p class="text-xs font-semibold">Rp {{ number_format($case->disbursement->amount) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 border rounded-md border-default-200 dark:border-white/14">
                        <div class="flex items-center justify-center">
                            <i data-lucide="landmark" class="size-6 text-default-500 fill-default-200"></i>
                        </div>
                        <div class="text-default-700 text-sm">
                            <h6 class="mb-1 text-default-800 font-semibold">Bank</h6>
                            <p class="text-xs">{{ $case->disbursement->bank_name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 border rounded-md border-default-200 dark:border-white/14">
                        <div class="flex items-center justify-center">
                            <i data-lucide="credit-card" class="size-6 text-default-500 fill-default-200"></i>
                        </div>
                        <div class="text-default-700 text-sm">
                            <h6 class="mb-1 text-default-800 font-semibold">Account</h6>
                            <p class="text-xs">{{ $case->disbursement->bank_account_number }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    @if($case->disbursement->status === 'pending')
                    <form method="POST" action="/admin/cases/{{ $case->id }}/disbursement/process">
                        @csrf
                        <button type="submit" class="btn btn-sm bg-warning/10 text-warning hover:bg-warning hover:text-white transition-all">
                            <i data-lucide="refresh-cw" class="size-4 me-1"></i> Process Disbursement
                        </button>
                    </form>
                    @endif

                    @if(in_array($case->disbursement->status, ['pending', 'processing']))
                    <form method="POST" action="/admin/cases/{{ $case->id }}/disbursement/complete">
                        @csrf
                        <button type="submit" class="btn btn-sm bg-success/10 text-success hover:bg-success hover:text-white transition-all">
                            <i data-lucide="check-circle-2" class="size-4 me-1"></i> Mark Complete
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
