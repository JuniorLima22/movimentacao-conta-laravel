<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestHistorico extends FormRequest
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
            'conta_id' => ['required'],
            'valor' => ['required', 'numeric'],
            'tipo' => ['required'],
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'pessoa_id.required' => 'O campo pessoa é obrigatório.',
            'conta_id.required' => 'O campo número é obrigatório.',
            'tipo.required' => 'O campo depositar/retirar é obrigatório.',
        ];
    }
}
