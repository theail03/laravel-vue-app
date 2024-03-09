<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatrixResource;
use App\Models\Matrix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getMatricesDashboard(Request $request)
    {
        $user = Auth::user();

        // Total Number of public matrices
        $totalPublicMatrices = Matrix::query()
            ->where('is_public', true)
            ->count();

        if ($user) {
            // Total Number of user matrices
            $totalUserMatrices = Matrix::query()->where('user_id', $user->id)->count();

            // Latest 5 matrices
            $latestUserMatrices = Matrix::query()
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get();

            return [
                'totalUserMatrices' => $totalUserMatrices,
                'totalPublicMatrices' => $totalPublicMatrices,
                'latestMatrices' => MatrixResource::collection($latestUserMatrices)
            ];
        } else {
            // Latest 5 public matrices
            $latestPublicMatrices = Matrix::query()
                ->where('is_public', true)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get();

            // Return the data for the unauthenticated user
            return [
                'totalUserMatrices' => 0,
                'totalPublicMatrices' => $totalPublicMatrices,
                'latestMatrices' => MatrixResource::collection($latestPublicMatrices),
            ];
        }
    }
}
