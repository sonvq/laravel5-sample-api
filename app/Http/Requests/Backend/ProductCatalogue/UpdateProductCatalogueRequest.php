<?php

namespace App\Http\Requests\Backend\ProductCatalogue;

use App\Http\Requests\Request;

/**
 * Class UpdateProductCatalogueRequest
 * @package App\Http\Requests\Backend\ProductCatalogue
 */
class UpdateProductCatalogueRequest extends Request
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
            'category_id'           => 'required|exists:category,id',
            'description'           => 'required|max:255',
            'status'                => 'required|in:"published","draft","pending"',
            'image'                 => 'mimes:jpeg,bmp,png,gif|max:8000'
        ];
    }
}
