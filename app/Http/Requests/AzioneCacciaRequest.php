<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AzioneCacciaRequest extends FormRequest
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
        return [
            'squadra_id' => 'required|gt:0',
            'zone' => 'required',
            'data' => 'required|date_format:d/m/Y',
            'dal' => 'required',
            'al' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'squadra_id.required' => 'Selezionare una squadra',
            'squadra_id.gt' => 'Selezionare una squadra',
            'zone.required' => 'Selezionare almeno un quadrante',
            'data.required' => 'Selezionare la data',
            'data.date_format' => 'La data non è in un formato corretto (gg/mm/aaaa)',
            'dal.required' => 'Selezionare orario dalle',
            'al.required' => 'Selezionare orario alle',
        ];
    }
}
