<?php

namespace App\Http\Requests\Backend\CorporateDeck;

use App\Http\Requests\Request;

/**
 * Class ManageCorporateDeckRequest
 * @package App\Http\Requests\Backend\CorporateDeck
 */
class ManageCorporateDeckRequest extends Request
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
