
@extends('layouts.lumeria')

@section('title', $title)

@push('styles')
<style>
    .quiz-wrapper {
        max-width: 850px;
        margin: 0 auto;
    }

    .quiz-card {
        border: 1px solid #e8e5df;
        border-radius: 18px;
        background: #fff;
        padding: 2rem;
        box-shadow: 0 8px 28px rgba(0, 0, 0, .04);
    }

    .quiz-option {
        display: block;
        width: 100%;
        text-align: left;
        padding: 1rem 1.2rem;
        margin-bottom: .75rem;
        border: 1px solid #dedbd5;
        border-radius: 12px;
        background: #fff;
        color: #292929;
        transition: .2s;
    }

    .quiz-option:hover:not(:disabled) {
        border-color: var(--bs-primary);
        background: #f8f7f3;
    }

    .quiz-option:disabled {
        opacity: 1;
        cursor: default;
    }

    .quiz-option.selected {
        border-color: #386b50;
        background: #edf5ef;
    }

    .quiz-feedback {
        border-radius: 12px;
        padding: 1.2rem;
        margin-top: 1.25rem;
    }

    .quiz-progress {
        height: 9px;
        border-radius: 20px;
    }

    @media (max-width: 576px) {
        .quiz-card {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<section class="py-5">
    <div class="container quiz-wrapper">

        <div class="mb-4">
            <a href="{{ route('islamic') }}" class="text-decoration-none">
                &larr; Kembali ke Materi PAIBP
            </a>
        </div>

        <div class="text-center mb-4">
            <span class="badge rounded-pill text-bg-light border mb-3">
                Pendidikan Agama Islam dan Budi Pekerti
            </span>

            <h1 class="fw-bold mb-2">{{ $title }}</h1>

            <p class="text-muted">
                Jawab 18 pertanyaan untuk menguji pemahamanmu.
                Pembahasan akan muncul setelah setiap jawaban.
            </p>
        </div>

        <div class="quiz-card">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold" id="progress-label">
                    Progres pengerjaan
                </span>

                <span class="text-muted small" id="progress-count"></span>
            </div>

            <div class="progress quiz-progress mb-4">
                <div
                    id="progress-bar"
                    class="progress-bar"
                    role="progressbar"
                    style="width: 0%"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    aria-valuenow="0">
                </div>
            </div>

            <div id="quiz-content" aria-live="polite">
                <div class="text-muted small mb-2" id="question-number"></div>

                <h2 class="h5 fw-bold mb-4" id="question-text"></h2>

                <div id="options-container"></div>

                <div id="feedback-container" class="d-none"></div>

                <div class="d-flex justify-content-end mt-4">
                    <button
                        type="button"
                        id="next-button"
                        class="btn btn-lumeria d-none">
                        Soal Berikutnya
                    </button>
                </div>
            </div>

            <div id="loading-message" class="text-center py-4 d-none">
                <div class="spinner-border mb-3" role="status">
                    <span class="visually-hidden">Memproses...</span>
                </div>
                <p class="text-muted mb-0">Sedang memproses jawaban...</p>
            </div>

            <div id="finish-container" class="text-center d-none">
                <div class="mb-3">
                    <span class="display-4 text-lumeria fw-bold" aria-hidden="true">&#10003;</span>
                </div>

                <h2 class="h4 fw-bold">Semua soal telah dijawab!</h2>

                <p class="text-muted">
                    Tekan tombol berikut untuk menghitung nilai dan menyimpan hasil kuis.
                </p>

                <button
                    type="button"
                    id="finish-button"
                    class="btn btn-lumeria px-4">
                    Selesaikan Kuis
                </button>
            </div>

            <div id="error-message" class="alert alert-danger d-none mt-3" role="alert"></div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const questions = @json($questions->values());
    const answered = @json($answered);

    const answerUrl = @json(route('quiz.answer'));
    const finishUrl = @json(route('quiz.finish'));

    const csrfToken = @json(csrf_token());

    let currentIndex = questions.findIndex(function (question) {
        return !Object.prototype.hasOwnProperty.call(answered, question.id);
    });

    const progressBar = document.getElementById('progress-bar');
    const progressCount = document.getElementById('progress-count');
    const questionNumber = document.getElementById('question-number');
    const questionText = document.getElementById('question-text');
    const optionsContainer = document.getElementById('options-container');
    const feedbackContainer = document.getElementById('feedback-container');

    const nextButton = document.getElementById('next-button');
    const finishButton = document.getElementById('finish-button');

    const quizContent = document.getElementById('quiz-content');
    const finishContainer = document.getElementById('finish-container');
    const loadingMessage = document.getElementById('loading-message');
    const errorMessage = document.getElementById('error-message');

    function getAnsweredCount() {
        return Object.keys(answered).length;
    }

    function updateProgress() {
        const total = questions.length;
        const completed = getAnsweredCount();
        const percentage = Math.round((completed / total) * 100);

        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);

        progressCount.textContent = completed + ' / ' + total + ' soal';
    }

    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.classList.remove('d-none');
    }

    function hideError() {
        errorMessage.textContent = '';
        errorMessage.classList.add('d-none');
    }

    function showFinishScreen() {
        quizContent.classList.add('d-none');
        finishContainer.classList.remove('d-none');
        updateProgress();
    }

    function renderQuestion() {
        hideError();
        updateProgress();

        if (currentIndex === -1 || currentIndex >= questions.length) {
            showFinishScreen();
            return;
        }

        const question = questions[currentIndex];

        questionNumber.textContent =
            'Pertanyaan ' + (currentIndex + 1) + ' dari ' + questions.length;

        questionText.textContent = question.question;

        optionsContainer.replaceChildren();
        feedbackContainer.replaceChildren();
        feedbackContainer.classList.add('d-none');
        nextButton.classList.add('d-none');

        Object.entries(question.options).forEach(function ([value, label]) {
            const button = document.createElement('button');

            button.type = 'button';
            button.className = 'quiz-option';
            button.textContent = value + '. ' + label;

            button.addEventListener('click', function () {
                submitAnswer(question.id, value, button);
            });

            optionsContainer.appendChild(button);
        });
    }

    async function submitAnswer(questionId, selectedAnswer, selectedButton) {
        hideError();

        const optionButtons = optionsContainer.querySelectorAll('button');

        optionButtons.forEach(function (button) {
            button.disabled = true;
        });

        selectedButton.classList.add('selected');

        try {
            const response = await fetch(answerUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    question_id: questionId,
                    selected_answer: selectedAnswer
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Jawaban gagal diproses.');
            }

            answered[questionId] = {
                selected: selectedAnswer,
                correct: data.is_correct
            };

            showFeedback(data);
            updateProgress();

        } catch (error) {
            optionButtons.forEach(function (button) {
                button.disabled = false;
            });

            selectedButton.classList.remove('selected');

            showError(error.message);
        }
    }

    function showFeedback(data) {
        const feedback = document.createElement('div');

        feedback.className = 'quiz-feedback ' +
            (data.is_correct
                ? 'alert alert-success'
                : 'alert alert-danger');

        const heading = document.createElement('h3');
        heading.className = 'h6 fw-bold';

        heading.textContent = data.is_correct
            ? 'Jawaban benar!'
            : 'Jawaban belum tepat.';

        const correctAnswer = document.createElement('p');
        correctAnswer.className = 'mb-2';

        correctAnswer.textContent =
            'Jawaban yang benar: ' + data.correct_answer;

        const explanationHeading = document.createElement('strong');
        explanationHeading.textContent = 'Pembahasan:';

        const explanation = document.createElement('p');
        explanation.className = 'mb-2';
        explanation.textContent = data.explanation;

        const reference = document.createElement('p');
        reference.className = 'small mb-0 text-muted';
        reference.textContent = 'Referensi: ' + data.reference;

        feedback.append(
            heading,
            correctAnswer,
            explanationHeading,
            explanation,
            reference
        );

        feedbackContainer.replaceChildren(feedback);
        feedbackContainer.classList.remove('d-none');

        nextButton.textContent =
            getAnsweredCount() === questions.length
                ? 'Lihat Hasil Kuis'
                : 'Soal Berikutnya';

        nextButton.classList.remove('d-none');
    }

    nextButton.addEventListener('click', function () {
        currentIndex = questions.findIndex(function (question) {
            return !Object.prototype.hasOwnProperty.call(answered, question.id);
        });

        renderQuestion();
    });

    finishButton.addEventListener('click', async function () {
        hideError();

        finishButton.disabled = true;
        quizContent.classList.add('d-none');
        finishContainer.classList.add('d-none');
        loadingMessage.classList.remove('d-none');

        try {
            const response = await fetch(finishUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Kuis gagal diselesaikan.');
            }

            window.location.href = data.redirect_url;

        } catch (error) {
            loadingMessage.classList.add('d-none');
            finishContainer.classList.remove('d-none');
            finishButton.disabled = false;
            showError(error.message);
        }
    });

    if (questions.length === 0) {
        showError('Belum ada pertanyaan yang tersedia.');
        quizContent.classList.add('d-none');
    } else {
        renderQuestion();
    }
});
</script>
@endpush
