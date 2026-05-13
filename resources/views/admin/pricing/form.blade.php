@extends('layouts.admin-tailwick')
@section('title', 'Pricing')

@section('content')
<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">Pricing Configuration</h4>

    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a href="/admin/dashboard" class="text-sm font-medium text-default-700">Dashboard</a>
        <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
        <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Pricing</a>
    </div>
</div>
<!-- Page Title End -->

@if($success)
<div class="mb-4 p-3 bg-success/10 border border-success/20 rounded text-success text-sm flex items-center gap-2">
    <i data-lucide="check-circle-2" class="size-4"></i>
    Pricing berhasil disimpan!
</div>
@endif

<div class="card">
    <div class="card-header">
        <h6 class="card-title">Active Pricing</h6>
        <button class="btn btn-sm bg-primary text-white" aria-haspopup="dialog" aria-expanded="false" aria-controls="pricingEdit" data-hs-overlay="#pricingEdit">
            <i data-lucide="pencil" class="size-4 me-1"></i>Edit Pricing
        </button>
    </div>

    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-default-200 dark:divide-white/14">
                        <thead class="bg-default-100 font-normal whitespace-nowrap">
                            <tr class="text-sm text-default-800">
                                <th scope="col" class="px-3.5 py-3 font-medium text-start">Parameter</th>
                                <th scope="col" class="px-3.5 py-3 font-medium text-start">Value</th>
                                <th scope="col" class="px-3.5 py-3 font-medium text-start">Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-200 dark:divide-white/14">
                            <tr class="text-sm text-default-800">
                                <td class="px-3.5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="size-9 flex items-center justify-center rounded bg-primary/10 text-primary">
                                            <i data-lucide="dollar-sign" class="size-4"></i>
                                        </div>
                                        <span class="font-medium">Base Price</span>
                                    </div>
                                </td>
                                <td class="px-3.5 py-3 font-semibold text-primary">Rp {{ number_format($config?->base_price ?? 0) }}</td>
                                <td class="px-3.5 py-3 text-default-500">Harga dasar konsultasi</td>
                            </tr>
                            <tr class="text-sm text-default-800">
                                <td class="px-3.5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="size-9 flex items-center justify-center rounded bg-info/10 text-info">
                                            <i data-lucide="map-pin" class="size-4"></i>
                                        </div>
                                        <span class="font-medium">Base KM</span>
                                    </div>
                                </td>
                                <td class="px-3.5 py-3 font-semibold">{{ $config?->base_km ?? 5 }} km</td>
                                <td class="px-3.5 py-3 text-default-500">Jarak yang termasuk dalam harga dasar</td>
                            </tr>
                            <tr class="text-sm text-default-800">
                                <td class="px-3.5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="size-9 flex items-center justify-center rounded bg-warning/10 text-warning">
                                            <i data-lucide="navigation" class="size-4"></i>
                                        </div>
                                        <span class="font-medium">Price per KM</span>
                                    </div>
                                </td>
                                <td class="px-3.5 py-3 font-semibold">Rp {{ number_format($config?->price_per_km ?? 0) }}</td>
                                <td class="px-3.5 py-3 text-default-500">Biaya tambahan per kilometer di luar base KM</td>
                            </tr>
                            <tr class="text-sm text-default-800">
                                <td class="px-3.5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="size-9 flex items-center justify-center rounded bg-success/10 text-success">
                                            <i data-lucide="receipt" class="size-4"></i>
                                        </div>
                                        <span class="font-medium">Service Fee</span>
                                    </div>
                                </td>
                                <td class="px-3.5 py-3 font-semibold">Rp {{ number_format($config?->service_fee ?? 0) }}</td>
                                <td class="px-3.5 py-3 text-default-500">Biaya layanan platform</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Pricing Modal -->
<div id="pricingEdit" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="pricingEditLabel">
    <div class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 max-w-md lg:w-full m-3 mx-auto min-h-[calc(100%-56px)] flex items-center">
        <div class="w-full flex flex-col card border border-default-200 dark:border-white/14 shadow-2xs rounded-xl pointer-events-auto">
            <div class="card-header">
                <h3 id="pricingEditLabel" class="font-semibold text-base text-default-800">
                    Edit Pricing Configuration
                </h3>

                <button type="button" aria-label="Close" data-hs-overlay="#pricingEdit">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <form method="POST" action="/admin/pricing">
                @csrf
                <div class="card-body overflow-y-auto">
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
                        <div class="lg:col-span-12">
                            <label for="basePriceInput" class="block mb-2 text-sm font-medium text-default-700">Base Price (Rp)</label>
                            <input type="number" step="0.01" id="basePriceInput" name="base_price" class="form-input" placeholder="0" value="{{ $config?->base_price }}" required>
                        </div>

                        <div class="lg:col-span-6">
                            <label for="baseKmInput" class="block mb-2 text-sm font-medium text-default-700">Base KM</label>
                            <input type="number" step="0.01" id="baseKmInput" name="base_km" class="form-input" placeholder="5" value="{{ $config?->base_km ?? 5 }}">
                        </div>

                        <div class="lg:col-span-6">
                            <label for="pricePerKmInput" class="block mb-2 text-sm font-medium text-default-700">Price per KM (Rp)</label>
                            <input type="number" step="0.01" id="pricePerKmInput" name="price_per_km" class="form-input" placeholder="0" value="{{ $config?->price_per_km }}" required>
                        </div>

                        <div class="lg:col-span-12">
                            <label for="serviceFeeInput" class="block mb-2 text-sm font-medium text-default-700">Service Fee (Rp)</label>
                            <input type="number" step="0.01" id="serviceFeeInput" name="service_fee" class="form-input" placeholder="0" value="{{ $config?->service_fee ?? 0 }}">
                        </div>
                    </div>
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <button type="button" class="btn bg-default-200 text-default-700" data-hs-overlay="#pricingEdit">Cancel</button>
                    <button type="submit" class="btn bg-primary text-white">
                        <i data-lucide="save" class="size-4 me-1"></i> Save Pricing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
