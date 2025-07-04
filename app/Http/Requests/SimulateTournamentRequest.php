<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SimulateTournamentRequest
 *
 * @property string $name
 * @property string $gender
 * @property string $start_date
 * @property string|null $end_date
 * @property array $players
 * @property string $players[].name
 */
class SimulateTournamentRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female'],
            'start_date' => ['required', 'date'],
            'end_date' => ['sometimes', 'date'],
            'players' => ['required', 'array'],
            'players.*.name' => ['required', 'string', 'max:255'],
            'players.*.email' => ['required', 'email', 'max:255', 'unique:players,email'],
            'players.*.gender' => ['required', 'string', 'in:male,female'],
            'players.*.hability' => ['required', 'integer', 'between:1,100'],
            'players.*.strength' => ['required', 'integer', 'between:1,100'],
            'players.*.speed' => ['required', 'integer', 'between:1,100'],
        ];
    }
}
