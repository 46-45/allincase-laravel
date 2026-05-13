@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')

<!-- Stats Cards -->
<h6 class="mb-3 fw-semibold">Overview</h6>
<div class="row">
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-body p-4">
                <a href="/admin/clients">
                    <div class="d-flex">
                        <div class="icon2 bg-primary text-white my-auto me-3">
                            <i class="fe fe-users"></i>
                        </div>
                        <div>
                            <p class="fs-14 font-weight-semibold mb-1">Total Clients</p>
                            <h5 class="mb-0">{{ number_format($stats['total_clients']) }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-body p-4">
                <a href="/admin/lawyers">
                    <div class="d-flex">
                        <div class="icon2 bg-secondary text-white my-auto me-3">
                            <i class="fe fe-user-check"></i>
                        </div>
                        <div>
                            <p class="fs-14 font-weight-semibold mb-1">Total Lawyers</p>
                            <h5 class="mb-0">{{ number_format($stats['total_lawyers']) }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-body p-4">
                <a href="/admin/cases">
                    <div class="d-flex">
                        <div class="icon2 bg-success text-white my-auto me-3">
                            <i class="fe fe-briefcase"></i>
                        </div>
                        <div>
                            <p class="fs-14 font-weight-semibold mb-1">Total Cases</p>
                            <h5 class="mb-0">{{ number_format($stats['total_cases']) }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex">
                    <div class="icon2 bg-warning text-white my-auto me-3">
                        <i class="fe fe-dollar-sign"></i>
                    </div>
                    <div>
                        <p class="fs-14 font-weight-semibold mb-1">Total Revenue</p>
                        <h5 class="mb-0">Rp {{ number_format($stats['total_revenue']) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h6 class="mb-3 fw-semibold">Cases Status</h6>
<div class="row">
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-body p-4">
                <a href="/admin/cases?status=pending">
                    <div class="d-flex">
                        <div class="icon2 bg-warning text-white my-auto me-3">
                            <i class="fe fe-clock"></i>
                        </div>
                        <div>
                            <p class="fs-14 font-weight-semibold mb-1">Pending Cases</p>
                            <h5 class="mb-0">{{ $stats['pending_cases'] }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-body p-4">
                <a href="/admin/cases?status=completed">
                    <div class="d-flex">
                        <div class="icon2 bg-success text-white my-auto me-3">
                            <i class="fe fe-check-circle"></i>
                        </div>
                        <div>
                            <p class="fs-14 font-weight-semibold mb-1">Completed Cases</p>
                            <h5 class="mb-0">{{ $stats['completed_cases'] }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex">
                    <div class="icon2 bg-info text-white my-auto me-3">
                        <i class="fe fe-credit-card"></i>
                    </div>
                    <div>
                        <p class="fs-14 font-weight-semibold mb-1">Pending Disbursements</p>
                        <h5 class="mb-0">{{ $stats['pending_disbursements'] }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Cases Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Recent Cases</h4>
        <a href="/admin/cases" class="btn btn-primary btn-sm">View All</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Case Number</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCases as $c)
                    <tr>
                        <td><strong>{{ $c->case_number }}</strong></td>
                        <td>
                            @php
                                $colors = [
                                    'pending' => 'warning',
                                    'matched' => 'info',
                                    'waiting_payment' => 'secondary',
                                    'paid' => 'primary',
                                    'in_progress' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    'expired' => 'dark',
                                ];
                                $color = $colors[$c->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $c->status)) }}</span>
                        </td>
                        <td>{{ $c->total_price ? 'Rp '.number_format($c->total_price) : '-' }}</td>
                        <td>{{ $c->created_at?->format('d M Y, H:i') }}</td>
                        <td>
                            <a href="/admin/cases/{{ $c->id }}" class="btn btn-sm btn-outline-primary">
                                <i class="fe fe-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada case</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
