<?php

namespace App\Http\Requests\Backend\Category;

use App\Http\Requests\Request;

/**
 * Class UpdateCategoryRequest
 * @package App\Http\Requests\Backend\Category
 */
class UpdateCategoryRequest extends Request
{
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
            'name'                  => 'required|max:100',
            'image'                 => 'mimes:jpeg,bmp,png,gif|max:8000'
        ];
    }
}
