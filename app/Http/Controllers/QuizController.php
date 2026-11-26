<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $questions = collect(config('paibp_quiz.questions'))
            ->map(function ($question) {
                return [
                    'id' => $question['id'],
                    'group' => $question['group'],
                    'question' => $question['question'],
                    'options' => $question['options'],
                    'reference' => $question['reference'],
                ];
            })
            ->shuffle()
            ->values();

        // Periksa apakah sesi kuis masih valid.
        $questionIds = session('paibp_quiz_question_ids');

        $currentIds = $questions->pluck('id')->all();

        $validSession = is_array($questionIds)
            && count($questionIds) === 18
            && count(array_diff($questionIds, $currentIds)) === 0
            && count(array_diff($currentIds, $questionIds)) === 0;

        if (!$validSession) {
            $questionIds = $questions->pluck('id')->all();

            session([
                'paibp_quiz_question_ids' => $questionIds,
            ]);

            session()->forget('paibp_quiz_answers');
        } else {
            $questions = $questions
                ->sortBy(fn ($question) => array_search($question['id'], $questionIds, true))
                ->values();
        }
        return view('pages.quiz', [
        'title' => config('paibp_quiz.title'),
        'questions' => $questions,
        'answered' => session('paibp_quiz_answers', []),
]);
    }

    public function answer(Request $request)
    {
        $validated = $request->validate([
            'question_id' => ['required', 'string'],
            'selected_answer' => ['required', 'in:A,B,C,D'],
        ]);

        $questionIds = session('paibp_quiz_question_ids', []);

        if (!in_array($validated['question_id'], $questionIds, true)) {
            return response()->json([
                'message' => 'Soal tidak termasuk dalam sesi kuis aktif.',
            ], 422);
        }

        $questions = collect(config('paibp_quiz.questions'));

        $question = $questions->firstWhere('id', $validated['question_id']);

        if (!$question) {
            return response()->json([
                'message' => 'Soal tidak ditemukan.',
            ], 404);
        }

        $answers = session('paibp_quiz_answers', []);

        if (array_key_exists($question['id'], $answers)) {
            return response()->json([
                'message' => 'Soal ini sudah dijawab.',
            ], 422);
        }

        $isCorrect = $validated['selected_answer'] === $question['answer'];

        $answers[$question['id']] = [
            'selected' => $validated['selected_answer'],
            'correct' => $isCorrect,
        ];

        session(['paibp_quiz_answers' => $answers]);

        return response()->json([
            'is_correct' => $isCorrect,
            'correct_answer' => $question['answer'],
            'explanation' => $question['explanation'],
            'reference' => $question['reference'],
        ]);
    }

    public function finish(Request $request)
    {
        $questionIds = session('paibp_quiz_question_ids', []);
        $answers = session('paibp_quiz_answers', []);

        $totalQuestions = count($questionIds);

        if ($totalQuestions !== 18) {
            return response()->json([
                'message' => 'Sesi kuis tidak valid. Silakan mulai ulang.',
            ], 422);
        }

                if (
            count($answers) !== $totalQuestions
            || count(array_intersect(array_keys($answers), $questionIds)) !== $totalQuestions
        ) {
            return response()->json([
                'message' => 'Selesaikan semua soal sebelum mengakhiri kuis.',
            ], 422);
        }

        $correctAnswers = collect($answers)
            ->where('correct', true)
            ->count();

        $score = (int) round(
            ($correctAnswers / $totalQuestions) * 100
        );

        $attempt = $request->user()->quizAttempts()->create([
            'quiz_key' => 'paibp-business',
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'answers' => $answers,
            'completed_at' => now(),
        ]);

        session()->forget([
            'paibp_quiz_answers',
            'paibp_quiz_question_ids',
        ]);

        return response()->json([
            'message' => 'Kuis berhasil diselesaikan.',
            'score' => $score,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'attempt_id' => $attempt->id,
            'redirect_url' => route('quiz.show', $attempt),
        ]);
    }

    public function history(Request $request)
    {
        $attempts = $request->user()
            ->quizAttempts()
            ->where('quiz_key', 'paibp-business')
            ->latest('completed_at')
            ->paginate(10);

        return view('pages.quiz-history', [
            'attempts' => $attempts,
        ]);
    }

    public function show(Request $request, QuizAttempt $attempt)
    {
        // Pastikan hasil kuis hanya bisa dilihat pemiliknya.
        abort_unless(
            $attempt->user_id === $request->user()->id
            && $attempt->quiz_key === 'paibp-business',
            404
        );

        $questions = collect(config('paibp_quiz.questions'));

        $details = collect($attempt->answers)->map(function ($answer, $questionId) use ($questions) {
            $question = $questions->firstWhere('id', $questionId);

            if (!$question) {
                return null;
            }

            return [
                'question' => $question['question'],
                'options' => $question['options'],
                'selected' => $answer['selected'],
                'correct_answer' => $question['answer'],
                'is_correct' => $answer['correct'],
                'explanation' => $question['explanation'],
                'reference' => $question['reference'],
            ];
        })->filter()->values();

        return view('pages.quiz-show', [
            'attempt' => $attempt,
            'details' => $details,
        ]);
    }
}
