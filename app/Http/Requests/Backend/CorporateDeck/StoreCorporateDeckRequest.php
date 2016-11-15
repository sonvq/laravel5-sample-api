<?php

namespace App\Http\Requests\Backend\CorporateDeck;

use App\Http\Requests\Request;

/**
 * Class StoreCorporateDeckRequest
 * @package App\Http\Requests\Backend\CorporateDeck
 */
class StoreCorporateDeckRequest extends Request
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
            'description'           => 'required|max:255',
            'status'                => 'required|in:"published","draft","pending"',
            'pdf'                   => 'required|mimes:pdf|max:8000'
        ];
    }
}
