<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize ()
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            'name' => 'required|unique:menus|max:100',
            'description' => 'required|max:250'
        ];
    }

    public function messages ()
    {
        return [
            'name.required' => __('Name is required'),
            'name.unique' => __('Name already exists'),
            'name.max' => __('Name only accept max. 100 characters'),
            'description.required' => __('Description is required'),
            'description.max' => __('Description only accept max. 250 characters')
        ];
    }
}
