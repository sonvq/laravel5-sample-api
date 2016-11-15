<?php

namespace App\Http\Controllers\Api\V1\CorporateDeck;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Repositories\Api\V1\CorporateDeck\EloquentCorporateDeckRepository;
use App\Models\CorporateDeck;
use App\Common\Helper;

class CorporateDeckController extends BaseController {   

    /*
     * Get all corporate deck
     */
    public function index() {
        $eloquentRepository = new EloquentCorporateDeckRepository();
                
        $query = $this->processInput();               

        $corporateDeckList = $eloquentRepository->getAll($query['where'], $query['sort'], $query['limit'], $query['offset']);                
        
        $result = [];
        $result['items'] = $corporateDeckList;
        $result['page_size'] = $query['limit'];
        
        return Helper::okResponse($result);  
    }  

}
