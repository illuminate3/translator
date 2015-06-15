<?php
namespace App\Http\Requests\menus;

//use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class FormRequest extends FormRequest {

    public function rules()
    {
        $rules = [
            'name' => 'required|alpha_dash|unique:menus,name,' . $this->id,
        ];
        return $rules;
    }
}
