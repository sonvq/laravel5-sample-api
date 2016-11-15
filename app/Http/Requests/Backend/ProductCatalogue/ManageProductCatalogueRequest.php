<?php

namespace App\Http\Requests\Backend\ProductCatalogue;

use App\Http\Requests\Request;

/**
 * Class ManageProductCatalogueRequest
 * @package App\Http\Requests\Backend\ProductCatalogue
 */
class ManageProductCatalogueRequest extends Request
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
