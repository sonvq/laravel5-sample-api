<?php

namespace App\Http\Requests\Backend\CorporateDeck;

use App\Http\Requests\Request;

/**
 * Class UpdateCorporateDeckRequest
 * @package App\Http\Requests\Backend\CorporateDeck
 */
class UpdateCorporateDeckRequest extends Request
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
            'pdf'                   => 'mimes:pdf|max:8000'
        ];
    }
}
