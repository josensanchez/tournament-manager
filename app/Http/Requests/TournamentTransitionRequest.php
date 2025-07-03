<?php

namespace App\Http\Requests;

use App\Models\States\Tournament\Created;
use App\Models\States\Tournament\InProgress;
use App\Models\States\Tournament\Ready;
use App\Models\States\Tournament\Registering;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TournamentTransitionRequest extends FormRequest
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
            'state' => ['required', 'string', Rule::in([Created::$name, Registering::$name, Ready::$name, InProgress::$name])],
        ];
    }
}
