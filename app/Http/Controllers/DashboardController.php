<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatrixResource;
use App\Http\Resources\SurveyAnswerResource;
use App\Http\Resources\SurveyResourceDashboard;
use App\Models\Survey;
use App\Models\Matrix;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Total Number of Surveys
        $total = Survey::query()->where('user_id', $user->id)->count();

        // Latest Survey
        $latest = Survey::query()->where('user_id', $user->id)->latest('created_at')->first();

        // Total Number of answers
        $totalAnswers = SurveyAnswer::query()
            ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
            ->where('surveys.user_id', $user->id)
            ->count();

        // Latest 5 answer
        $latestAnswers = SurveyAnswer::query()
            ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
            ->where('surveys.user_id', $user->id)
            ->orderBy('end_date', 'DESC')
            ->limit(5)
            ->getModels('survey_answers.*');

        return [
            'totalSurveys' => $total,
            'latestSurvey' => $latest ? new SurveyResourceDashboard($latest) : null,
            'totalAnswers' => $totalAnswers,
            'latestAnswers' => SurveyAnswerResource::collection($latestAnswers)
        ];
    }

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
