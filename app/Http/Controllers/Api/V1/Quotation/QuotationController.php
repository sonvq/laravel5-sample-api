<?php

namespace App\Http\Controllers\Api\V1\Quotation;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Repositories\Api\V1\Quotation\EloquentQuotationRepository;
use App\Models\Quotation;
use App\Common\Helper;

class QuotationController extends BaseController {   

    /*
     * Get all quotation
     */
    public function index() {
            
        $eloquentQuotationRepository = new EloquentQuotationRepository();
        
        $query = $this->processInput();               
        
        $quotationList = $eloquentQuotationRepository->getAll($query['where'], $query['sort'], $query['limit'], $query['offset'], $query['fields']);  
        $quotationListCount = $eloquentQuotationRepository->getCount($query['where'], $query['fields']);
        
        $result = [];
        $result['items'] = $quotationList;
        $result['page_size'] = $query['limit'];
        $result['page_total'] = $quotationListCount;
        
        return Helper::okResponse($result);  
    }  

}
