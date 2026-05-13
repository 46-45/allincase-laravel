<?php

namespace App\Services;

use App\Models\PricingConfig;

class PricingService
{
    public static function getActivePricing(): ?PricingConfig
    {
        return PricingConfig::where('is_active', true)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Calculate price based on distance from lawyer to meeting point.
     *
     * Formula:
     * - distance <= base_km: flat base_price
     * - distance > base_km: base_price + ceil(extra_km) * price_per_km
     */
    public static function calculatePrice(float $distanceKm, PricingConfig $config): array
    {
        $baseKm = (float) $config->base_km;
        $basePrice = (float) $config->base_price;
        $pricePerKm = (float) $config->price_per_km;
        $serviceFee = (float) $config->service_fee;

        if ($distanceKm <= $baseKm) {
            $calculatedBase = $basePrice;
        } else {
            $extraKm = $distanceKm - $baseKm;
            $extraKmCeil = ceil($extraKm);
            $calculatedBase = $basePrice + ($extraKmCeil * $pricePerKm);
        }

        $total = $calculatedBase + $serviceFee;

        return [
            'distance_km' => round($distanceKm, 2),
            'base_price' => $calculatedBase,
            'service_fee' => $serviceFee,
            'total_price' => $total,
        ];
    }
}
