<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoService
{
    /**
     * Calculate distance between two coordinates in km (Haversine formula).
     */
    public static function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $R = 6371; // Earth radius in km
        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $dphi = deg2rad($lat2 - $lat1);
        $dlambda = deg2rad($lng2 - $lng1);

        $a = sin($dphi / 2) ** 2 + cos($phi1) * cos($phi2) * sin($dlambda / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }

    /**
     * Reverse geocode coordinates to administrative area names.
     */
    public static function reverseGeocode(float $lat, float $lng): array
    {
        $provider = config('services.geocoding.provider', 'nominatim');

        if ($provider === 'google' && config('services.geocoding.google_api_key')) {
            return self::reverseGeocodeGoogle($lat, $lng);
        }

        return self::reverseGeocodeNominatim($lat, $lng);
    }

    private static function reverseGeocodeNominatim(float $lat, float $lng): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => 'allincase-app/1.0'])
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat' => $lat,
                    'lon' => $lng,
                    'format' => 'json',
                    'accept-language' => 'id',
                ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            $address = $data['address'] ?? [];

            $subDistrict = $address['suburb']
                ?? $address['quarter']
                ?? $address['neighbourhood']
                ?? $address['village']
                ?? null;

            $cityDistrict = $address['city_district']
                ?? $address['county']
                ?? $address['city']
                ?? $address['town']
                ?? null;

            $province = $address['state'] ?? $address['province'] ?? null;

            return [
                'sub_district' => $subDistrict,
                'city_district' => $cityDistrict,
                'province' => $province,
            ];
        } catch (\Exception $e) {
            Log::warning("Geocoding failed for ({$lat}, {$lng}): " . $e->getMessage());
            return [];
        }
    }

    private static function reverseGeocodeGoogle(float $lat, float $lng): array
    {
        try {
            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => "{$lat},{$lng}",
                'key' => config('services.geocoding.google_api_key'),
                'language' => 'id',
                'result_type' => 'administrative_area_level_3|administrative_area_level_2|administrative_area_level_1',
            ]);

            $data = $response->json();
            if (($data['status'] ?? '') !== 'OK') {
                return [];
            }

            $result = [];
            foreach ($data['results'][0]['address_components'] ?? [] as $component) {
                $types = $component['types'] ?? [];
                if (in_array('administrative_area_level_3', $types)) {
                    $result['sub_district'] = $component['long_name'];
                } elseif (in_array('administrative_area_level_2', $types)) {
                    $result['city_district'] = $component['long_name'];
                } elseif (in_array('administrative_area_level_1', $types)) {
                    $result['province'] = $component['long_name'];
                }
            }

            return $result;
        } catch (\Exception $e) {
            Log::error("Google geocoding error: " . $e->getMessage());
            return [];
        }
    }
}
