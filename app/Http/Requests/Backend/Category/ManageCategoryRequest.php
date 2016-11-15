<?php

namespace App\Http\Requests\Backend\Category;

use App\Http\Requests\Request;

/**
 * Class ManageCategoryRequest
 * @package App\Http\Requests\Backend\Category
 */
class ManageCategoryRequest extends Request
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
			//
		];
	}
}
