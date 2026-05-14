<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LawyerProfile;
use App\Models\LawyerDocument;
use App\Models\User;
use App\Services\FileService;
use App\Services\GeoService;
use Illuminate\Http\Request;

class LawyerController extends Controller
{
    public function getMyProfile(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'lawyer') {
            return response()->json(['detail' => 'Akses hanya untuk Lawyer'], 403);
        }

        $profile = LawyerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            return response()->json(['detail' => 'Profil lawyer tidak ditemukan'], 404);
        }

        // Calculate real total_earned from completed cases
        $totalEarned = \App\Models\LegalCase::where('lawyer_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_price');

        $totalCases = \App\Models\LegalCase::where('lawyer_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $data = $profile->toArray();
        $data['total_earned'] = number_format((float) $totalEarned, 2, '.', '');
        $data['total_cases'] = (string) $totalCases;

        return response()->json($data);
    }

    public function updateMyProfile(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'lawyer') {
            return response()->json(['detail' => 'Akses hanya untuk Lawyer'], 403);
        }

        $profile = LawyerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            return response()->json(['detail' => 'Profil lawyer tidak ditemukan'], 404);
        }

        if ($request->has('bio')) {
            $profile->bio = $request->bio;
        }
        $profile->save();

        return response()->json($profile);
    }

    public function updateBankInfo(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'lawyer') {
            return response()->json(['detail' => 'Akses hanya untuk Lawyer'], 403);
        }

        $request->validate([
            'bank_name' => 'required|string',
            'bank_account_number' => 'required|string',
            'bank_account_name' => 'required|string',
        ]);

        $profile = LawyerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            return response()->json(['detail' => 'Profil lawyer tidak ditemukan'], 404);
        }

        $profile->update([
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name' => $request->bank_account_name,
        ]);

        return response()->json($profile);
    }

    public function setAvailability(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'lawyer') {
            return response()->json(['detail' => 'Akses hanya untuk Lawyer'], 403);
        }

        $request->validate([
            'is_available' => 'required|boolean',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $profile = LawyerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            return response()->json(['detail' => 'Profil lawyer tidak ditemukan'], 404);
        }

        if ($request->is_available) {
            if (!$request->lat || !$request->lng) {
                return response()->json(['detail' => 'Koordinat GPS diperlukan saat set Online'], 400);
            }

            $geoData = GeoService::reverseGeocode($request->lat, $request->lng);

            $profile->update([
                'last_lat' => $request->lat,
                'last_lng' => $request->lng,
                'last_location_at' => now(),
                'sub_district' => $geoData['sub_district'] ?? null,
                'city_district' => $geoData['city_district'] ?? null,
                'province' => $geoData['province'] ?? null,
                'is_available' => true,
            ]);
        } else {
            $profile->update(['is_available' => false]);
        }

        return response()->json([
            'is_available' => $profile->is_available,
            'sub_district' => $profile->sub_district,
            'city_district' => $profile->city_district,
            'province' => $profile->province,
        ]);
    }

    public function getMyDocuments(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'lawyer') {
            return response()->json(['detail' => 'Akses hanya untuk Lawyer'], 403);
        }

        $docs = LawyerDocument::where('lawyer_id', $user->id)->get();
        return response()->json($docs);
    }

    public function uploadDocument(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'lawyer') {
            return response()->json(['detail' => 'Akses hanya untuk Lawyer'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file',
        ]);

        $fileData = FileService::saveDocument($request->file('file'));

        $doc = LawyerDocument::create([
            'lawyer_id' => $user->id,
            'title' => $request->title,
            'file_url' => $fileData['file_url'],
            'file_type' => $fileData['file_type'],
            'file_size' => $fileData['file_size'],
        ]);

        return response()->json($doc, 201);
    }

    public function deleteDocument(Request $request, int $docId)
    {
        $user = $request->user();
        if ($user->role !== 'lawyer') {
            return response()->json(['detail' => 'Akses hanya untuk Lawyer'], 403);
        }

        $doc = LawyerDocument::where('id', $docId)
            ->where('lawyer_id', $user->id)
            ->first();

        if (!$doc) {
            return response()->json(['detail' => 'Dokumen tidak ditemukan'], 404);
        }

        FileService::deleteFile($doc->file_url);
        $doc->delete();

        return response()->noContent();
    }

    public function publicProfile(int $lawyerId)
    {
        $user = User::with(['lawyerProfile', 'lawyerDocuments'])
            ->where('id', $lawyerId)
            ->where('role', 'lawyer')
            ->first();

        if (!$user) {
            return response()->json(['detail' => 'Lawyer tidak ditemukan'], 404);
        }

        $profile = $user->lawyerProfile;
        $documents = $user->lawyerDocuments->map(function ($doc) {
            return [
                'id' => $doc->id,
                'title' => $doc->title,
                'file_url' => $doc->file_url,
                'file_type' => $doc->file_type,
                'uploaded_at' => $doc->uploaded_at,
            ];
        });

        return response()->json([
            'id' => $user->id,
            'full_name' => $user->full_name,
            'avatar_url' => $user->avatar_url,
            'bar_number' => $profile?->bar_number,
            'specializations' => $profile?->specializations,
            'years_of_experience' => $profile?->years_of_experience ?? 0,
            'bio' => $profile?->bio,
            'rating_avg' => $profile ? (float) $profile->rating_avg : 0,
            'total_cases' => $profile?->total_cases ?? 0,
            'city_district' => $profile?->city_district,
            'province' => $profile?->province,
            'documents' => $documents,
        ]);
    }
}
