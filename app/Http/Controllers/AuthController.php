<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan untuk mengimpor Auth
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'userid' => 'required|string',
            'password' => 'required|string',
        ]);

        // Kirim request ke API SIHALAL untuk login
        $response = Http::timeout(10)->post('http://lph-api.halal.go.id/auth/signin', [
            'userid' => $credentials['userid'],
            'password' => $credentials['password'],
        ]);

        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['payload']['token']) && isset($responseData['payload']['refreshToken'])) {
                // Pastikan user sudah terautentikasi sebelum menyimpan token
                $user = Auth::user();
                if ($user) {
                    // Simpan access_token dan refresh_token ke database
                    ApiToken::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'access_token' => $responseData['payload']['token'],
                            'refresh_token' => $responseData['payload']['refreshToken'],
                        ]
                    );

                    return response()->json(['message' => 'Login successful'], 200);
                } else {
                    return response()->json(['error' => 'User not authenticated'], 401);
                }
            } else {
                return response()->json(['error' => 'Invalid response from API'], 500);
            }
        } else {
            return response()->json(['error' => 'Login failed'], $response->status());
        }
    }

    public function logout(Request $request)
    {
        // Pastikan user sudah terautentikasi
        $user = Auth::user();
        if ($user) {
            // Ambil refresh token dari database
            $tokenData = ApiToken::where('user_id', $user->id)->first();

            if ($tokenData) {
                // Kirim request ke API SIHALAL untuk logout
                $response = Http::withToken($tokenData->access_token)->post('http://lph-api.halal.go.id/auth/signout');

                if ($response->successful()) {
                    // Hapus token dari database
                    $tokenData->delete();

                    return response()->json(['message' => 'Logout successful'], 200);
                } else {
                    return response()->json(['error' => 'Logout failed'], $response->status());
                }
            } else {
                return response()->json(['error' => 'No token found for logout'], 404);
            }
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }
}
