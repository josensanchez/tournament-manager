<?php

namespace App\Http\Requests;

use App\Models\States\Match\Finished;
use App\Models\States\Match\InProgress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class MatchGameTransitionRequest
 *
 * @property string $state
 * @property int|null $score
 */
class MatchGameTransitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'state' => ['required', 'string', Rule::in([InProgress::$name, Finished::$name])],
            'score' => ['sometimes', 'required_if:state,' . Finished::$name . '', 'integer', 'min:0'],
        ];
    }
}
