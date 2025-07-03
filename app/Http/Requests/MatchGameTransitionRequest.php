<?php

namespace App\Http\Requests;

use App\Models\States\MatchGame\Finished;
use App\Models\States\MatchGame\InProgress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class MatchGameTransitionRequest
 *
 * @property string $state
 * @property array<int,string>|null $score
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
            'score' => ['required_if:state,' . Finished::$name, 'array', 'min:3', 'max:5'],
            'score.*' => ['required', 'string', 'regex:/([0-7]+)\-([0-7]+)/'],
        ];
    }
}
