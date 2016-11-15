<?php

namespace App\Repositories\Api\V1\Quotation;

use App\Models\Quotation;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Api\V1\EloquentBaseRepository;
/**
 * Class EloquentQuotationRepository
 * @package App\Repositories\Api\V1\Quotation;
 */
class EloquentQuotationRepository extends EloquentBaseRepository
{
    protected $table = 'tblQuotation';
    protected $model = \App\Models\Quotation::class;
    protected $connection_index = 'erp_live';
    protected $primaryKey = 'strInvoiceNo';
    
    
    protected function onPreQuery(\Illuminate\Database\Query\Builder $query, &$where = null, &$sort = null, &$fields = array())
    {       
        $query->addSelect('qdd.memDescription');  
           
        $additionalSelectField = array('r.ysnCompleted', 'r.ysnDO');
        $this->addSelectFieldQuery($fields, $query, $additionalSelectField);               
        
        //$query->leftJoin('tblQuotationDetailDescription as qdd', 'qdd.strInvoiceNo', '=', 'r.strInvoiceNo');
          
        $query->leftJoin('tblQuotationDetailDescription as qdd', function($join) {
            $join->on('qdd.strInvoiceNo', '=', 'r.strInvoiceNo')->on('qdd.SN', '=', DB::raw('(select top 1 SN FROM tblQuotationDetailDescription WHERE r.strInvoiceNo = tblQuotationDetailDescription.strInvoiceNo)'));
        });

        // Filter quotation by Status: Pending, Accepted
        if (isset($where['ysnConverted'])) {       
            if (is_array($where['ysnConverted'])) {
                $query->whereIn('qdd.ysnConverted', $where['ysnConverted']);            
            } else {
                $query->where('qdd.ysnConverted', '=', $where['ysnConverted']);            
            }
            unset($where['ysnConverted']);
        }
        
        // Search by company Name 
        if (isset($where['strCompany'])) {
            $query->where('r.strCompany', 'like', $where['strCompany'] . '%');
            unset($where['strCompany']);
        }
        
        if (isset($where['strInvoiceNo'])) {         
            $searchInvoiceNo = (int) filter_var($where['strInvoiceNo'], FILTER_SANITIZE_NUMBER_INT);  
            if ($searchInvoiceNo != 0) {
                $query->where('r.strInvoiceNo', 'like', $searchInvoiceNo . '%');
            }
            unset($where['strInvoiceNo']);
        }
        
        if (isset($where['memDescription'])) {
            $query->where('qdd.memDescription', 'like', '%' . $where['memDescription'] . '%');
            unset($where['memDescription']);
        }
        
        if (isset($where['strStatus'])) {            
            if ($where['strStatus'] == Quotation::STATUS_PENDING) {
                $query->where('r.ysnCompleted', '=', 0);  
                $query->where('r.ysnDO', '=', 0);  
            }
            
            if ($where['strStatus'] == Quotation::STATUS_ACCEPTED) {
                $query->where('r.ysnCompleted', '=', 1);  
                $query->where('r.ysnDO', '=', 0);  
            }
            
            if ($where['strStatus'] == Quotation::STATUS_DECLINED) {
                $query->where('r.ysnCompleted', '=', 0);  
                $query->where('r.ysnDO', '=', 1);  
            }
            
            unset($where['strStatus']);
        }
        
//        if (isset($sort['strInvoiceNo'])) {
//            $query->orderBy(DB::raw("CAST(r.strInvoiceNo AS Numeric(10,0))"), $sort['strInvoiceNo']);
//            unset($sort['strInvoiceNo']);
//        }
    }
    
}