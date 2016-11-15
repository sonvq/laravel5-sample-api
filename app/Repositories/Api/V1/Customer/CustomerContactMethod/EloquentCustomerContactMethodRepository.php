<?php

namespace App\Repositories\Api\V1\Customer\CustomerContactMethod;

use App\Models\CustomerContactMethod;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Api\V1\EloquentBaseRepository;

/**
 * Class EloquentCustomerContactMethodRepository
 * @package App\Repositories\Api\V1\Customer\CustomerContactMethod;
 */
class EloquentCustomerContactMethodRepository extends EloquentBaseRepository {

    protected $table = 'tblCustomerContactMethod';
    protected $model = \App\Models\CustomerContactMethod::class;
    protected $connection_index = 'erp_live';
    protected $connection_store = 'erp_temp';
    protected $primaryKey = 'strCustomerID';

    protected function onPreQuery(\Illuminate\Database\Query\Builder $query, &$where = null, &$sort = null, &$fields = array()) {
        if (isset($where['syncDate'])) {
            unset($where['syncDate']);
        }

        if (empty($sort)) {
            $query->orderBy('cntID', 'Desc');
        }
    }
    
    public function createCustomerContactMethodStub($input, $customerContactMethod, $action)
    {
        $customerContactMethod->strContact = $input['strContact'];
        $customerContactMethod->strTitle = $input['strTitle'];
        $customerContactMethod->strPhoneNumber = $input['strPhoneNumber'];
        $customerContactMethod->strEmail = $input['strEmail'];
                
        return $customerContactMethod;
    }

}
