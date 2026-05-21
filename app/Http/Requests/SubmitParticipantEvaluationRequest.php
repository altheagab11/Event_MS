<?php

namespace App\Http\Requests;

use App\Models\EvaluationQuestion;
use App\Services\EventEvaluationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SubmitParticipantEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['nullable', 'string', 'max:2000'],
            'ratings' => ['required', 'array'],
            'ratings.*' => ['nullable', 'integer', 'min:1', 'max:5'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $token = (string) $this->route('token');
            $context = app(EventEvaluationService::class)->resolveFormContext($token);

            if (! ($context['valid'] ?? false)) {
                $error = (string) ($context['error'] ?? EventEvaluationService::ERROR_INVALID_TOKEN);
                $messages = app(EventEvaluationService::class)->errorMessages();
                $validator->errors()->add('form', $messages[$error] ?? 'Unable to submit evaluation.');

                return;
            }

            /** @var \Illuminate\Support\Collection<int, EvaluationQuestion> $questions */
            $questions = $context['questions'] ?? collect();
            $ratings = (array) $this->input('ratings', []);

            foreach ($questions->where('question_type', 'rating') as $question) {
                $questionId = (int) $question->question_id;
                $value = (int) ($ratings[$questionId] ?? $ratings[(string) $questionId] ?? 0);

                if ($question->is_required && ($value < 1 || $value > 5)) {
                    $validator->errors()->add(
                        "ratings.{$questionId}",
                        'Please provide a rating for: '.$question->question_text
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'ratings.required' => 'Please answer the rating questions before submitting.',
            'ratings.*.integer' => 'Each rating must be a whole number from 1 to 5.',
            'ratings.*.min' => 'Each rating must be at least 1 star.',
            'ratings.*.max' => 'Each rating cannot exceed 5 stars.',
        ];
    }
}
