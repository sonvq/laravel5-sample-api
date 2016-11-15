<?php

namespace App\Http\Controllers\Api\V1\Customer;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Repositories\Api\V1\Customer\EloquentCustomerRepository;
use App\Repositories\Api\V1\Customer\CustomerContactMethod\EloquentCustomerContactMethodRepository;
use App\Models\Customer;
use App\Common\Helper;
use App\Models\CustomerContactMethod;

class CustomerController extends BaseController {   

    /*
     * Get all Customer
     */
    public function index() {

        $eloquentCustomerRepository = new EloquentCustomerRepository();
        $eloquentCustomerContactMethodRepository = new EloquentCustomerContactMethodRepository();
        
        $query = $this->processInput();               

        $customerList = $eloquentCustomerRepository->getAll($query['where'], $query['sort'], $query['limit'], $query['offset'], $query['fields']);          
        
        $arrayCustomerId = [];
        $customerContactMethodByCustomer = [];
        if (count($customerList) > 0) {
            foreach ($customerList as $singleCustomer) {                
                $arrayCustomerId[] = $singleCustomer['strCustomerID'];
            }
            
            if (count($arrayCustomerId) > 0) {
                $queryWhere = array();                
                $queryWhere['strCustomerID'] = $arrayCustomerId; 
                
                $customerContactMethodListByCustomer = $eloquentCustomerContactMethodRepository->getAll($queryWhere, array(), 0, 0);
                if (count($customerContactMethodListByCustomer) > 0) {                    
                    foreach($customerContactMethodListByCustomer as $singleContactMethod) {
                        $customerContactMethodByCustomer[$singleContactMethod['strCustomerID']][] = $singleContactMethod;
                    }
                }                
            }
            
            foreach ($customerList as $key=>$singleCustomer) {
                if (isset($singleCustomer['contact_list']) && is_array($singleCustomer['contact_list'])) {
                    $singleCustomer['contact_list'] = isset($customerContactMethodByCustomer[$singleCustomer['strCustomerID']]) ? array_merge($singleCustomer['contact_list'], $customerContactMethodByCustomer[$singleCustomer['strCustomerID']]) : [];    
                } else {
                    $singleCustomer['contact_list'] = isset($customerContactMethodByCustomer[$singleCustomer['strCustomerID']]) ? $customerContactMethodByCustomer[$singleCustomer['strCustomerID']] : [];
                }
                
                $customerList[$key] = $singleCustomer;
            } 
        }   
        
        $result = [];
        $result['items'] = $customerList;
        $result['page_size'] = $query['limit'];
        
        return Helper::okResponse($result);  
    }  
        
    public function store(Request $request) {
        $input = $request->all();

        $eloquentCustomerRepository = new EloquentCustomerRepository();
        $customer = new Customer();

        $validator = Validator::make($input, $customer->getCreateRules($input));

        if ($validator->passes()) {           
            
            $customerCreate = $eloquentCustomerRepository->create($input);
            
            if ($customerCreate) {
                return Helper::okResponse($customerCreate->postProcessModel());
            } else {
                return Helper::internalServerErrorResponse(Helper::INTERNAL_SERVER_ERROR, Helper::INTERNAL_SERVER_ERROR_MSG);
            }
        } else {
            return Helper::validationErrorResponse(Helper::VALIDATION_ERROR, Helper::VALIDATION_ERROR_MSG, $validator->messages()->toArray());
        }
    }
    
    public function update(Request $request) {
        $input = $request->all();

        $eloquentCustomerRepository = new EloquentCustomerRepository();
        $eloquentCustomerContactMethodRepository = new EloquentCustomerContactMethodRepository();
        $customer = new Customer();

        $validator = Validator::make($input, $customer->getUpdateRules($input));

        if ($validator->passes()) {           
            $customerEdit = Customer::find($input['cntID']);
            if ($customerEdit) {         
                $customerContactMethodList = CustomerContactMethod::where('strCustomerID', '=', $input['strCustomerID'])->get();
                $arrayCustomerContactMethod = array();
                if (count($customerContactMethodList) > 0) {
                    foreach ($customerContactMethodList as $singleContact) {                        
                        $arrayCustomerContactMethod[$singleContact->cntID] = $singleContact;
                    }
                }                
                        
                $customerUpdate = $eloquentCustomerRepository->update($input, $customerEdit, $arrayCustomerContactMethod);
                
                if ($customerUpdate) {
                    return Helper::okResponse($customerUpdate->postProcessModel());
                } else {
                    return Helper::internalServerErrorResponse(Helper::INTERNAL_SERVER_ERROR, Helper::INTERNAL_SERVER_ERROR_MSG);
                }    
            } else {
                return Helper::notFoundErrorResponse(Helper::CUSTOMER_NOT_FOUND, Helper::CUSTOMER_NOT_FOUND_MSG);
            }                        
        } else {
            return Helper::validationErrorResponse(Helper::VALIDATION_ERROR, Helper::VALIDATION_ERROR_MSG, $validator->messages()->toArray());
        }
    }

}
