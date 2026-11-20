<?php

namespace App\Http\Controllers;

use App\Services\BookRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookRecommendationController extends Controller
{
    public function index(): View
    {
        return view('recommendations.index', [
            'results' => null,
            'source' => null,
            'selection' => [],
        ]);
    }

    public function store(Request $request, BookRecommendationService $service): View
    {
        $selection = $request->validate([
            'mood' => ['required', Rule::in(['stressed', 'sad', 'curious', 'motivated'])],
            'goal' => ['required', Rule::in(['relax', 'learn', 'inspire', 'enjoy'])],
            'note' => ['nullable', 'string', 'max:280'],
        ], [
            'mood.required' => 'Vui lòng chọn tâm trạng.',
            'mood.in' => 'Tâm trạng không hợp lệ.',
            'goal.required' => 'Vui lòng chọn mục tiêu đọc.',
            'goal.in' => 'Mục tiêu đọc không hợp lệ.',
            'note.max' => 'Mô tả chỉ được tối đa 280 ký tự.',
        ]);

        $recommendation = $service->recommend(
            $selection['mood'],
            $selection['goal'],
            trim($selection['note'] ?? '')
        );

        return view('recommendations.index', [
            'results' => $recommendation['books'],
            'source' => $recommendation['source'],
            'selection' => $selection,
        ]);
    }
}
