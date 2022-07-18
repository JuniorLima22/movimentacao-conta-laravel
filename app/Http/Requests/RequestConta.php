<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestConta extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->id;
        $rules = [
            'pessoa_id' => ['required'],
            'numero' => ['required', 'numeric', 'min:3', "unique:contas,numero,{$id}"],
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'pessoa_id.required' => 'O campo pessoa é obrigatório.',
        ];
    }
}
