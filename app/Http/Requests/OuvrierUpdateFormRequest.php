<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class OuvrierUpdateFormRequest extends FormRequest
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
            'country_id' => ['required', 'exists:countries,id'],
            'region_id' => ['required', 'exists:regions,id'],
            'departement_id' => 'required', 'exists:departements,id',
            'metiers' => ['required', 'array', 'exists:metiers,id'],
            'domaines' => ['required', 'array', 'exists:domaines,id'],
            'phone_number' => ['required', 'string', 'max:255', 'unique:ouvriers,phone_number,' . $this->route('ouvrier'),],
            'address' => ['required', 'string', 'max:255'],
            'phone_number_2' => ['nullable', 'string', 'max:255'],
            'user_id' => ['uuid','nullable', 'exists:users,id'],
        ];
    }
}
