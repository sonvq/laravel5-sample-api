<?php

namespace App\Http\Requests\Backend\Category;

use App\Http\Requests\Request;

/**
 * Class StoreCategoryRequest
 * @package App\Http\Requests\Backend\Category
 */
class StoreCategoryRequest extends Request
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
            'image'                 => 'required|mimes:jpeg,bmp,png,gif|max:8000',
        ];
    }
}
