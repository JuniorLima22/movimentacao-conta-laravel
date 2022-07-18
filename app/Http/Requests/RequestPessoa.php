<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelLegends\PtBrValidator\Rules\FormatoCpf;

class RequestPessoa extends FormRequest
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
            'nome' => ['required', "unique:pessoas,nome,{$id}", 'min:3', 'max:200', 'regex:/^([A-zÀ-ú]|-|_|\s)+$/'],
            'cpf' => ['required', 'cpf'],
            'cep' => ['required', 'formato_cep'],
            'numero' => ['string', 'nullable'],
            'logradouro' => ['required', 'string'],
            'bairro' => ['required', 'string'],
            'estado' => ['required', 'string'],
            'municipio' => ['required', 'string'],
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'nome.regex' => 'O campo nome só pode conter letras.',
        ];
    }
}
