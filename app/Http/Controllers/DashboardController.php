<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatrixResource;
use App\Models\Matrix;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getMatricesDashboard(Request $request)
    {
        $user = $request->user();

        // Total Number of user matrices
        $totalUserMatrices = Matrix::query()->where('user_id', $user->id)->count();

        // Total Number of public matrices
        $totalPublicMatrices = Matrix::query()
            ->where('user_id', $user->id)
            ->where('is_public', true)
            ->count();

        // Latest 5 matrices
        $latestUserMatrices = Matrix::query()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return [
            'totalUserMatrices' => $totalUserMatrices,
            'totalPublicMatrices' => $totalPublicMatrices,
            'latestUserMatrices' => MatrixResource::collection($latestUserMatrices)
        ];
    }
}
