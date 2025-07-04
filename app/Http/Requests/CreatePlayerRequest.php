<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreatePlayerRequest
 *
 * @property string $name
 * @property string $gender
 * @property int $hability
 * @property int $strength
 * @property int $speed
 */
class CreatePlayerRequest extends FormRequest
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
            'hability' => ['required', 'integer', 'between:1,100'],
            'strength' => ['required', 'integer', 'between:1,100'],
            'speed' => ['required', 'integer', 'between:1,100'],
        ];
    }
}
