<?php

namespace App\Repositories\Api\V1\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Api\V1\EloquentBaseRepository;
use App\Models\CustomerContactMethod;
use App\Repositories\Api\V1\Customer\CustomerContactMethod\EloquentCustomerContactMethodRepository;

/**
 * Class EloquentCustomerRepository
 * @package App\Repositories\Api\V1\Customer;
 */
class EloquentCustomerRepository extends EloquentBaseRepository {

    protected $table = 'tblCustomer';
    protected $model = \App\Models\Customer::class;
    protected $connection_index = 'erp_live';
    protected $connection_store = 'erp_temp';
    protected $primaryKey = 'strCustomerID';

    protected function onPreQuery(\Illuminate\Database\Query\Builder $query, &$where = null, &$sort = null, &$fields = array()) {

        $additionalSelectField = array('r.dtmDateEdit', 'r.dtmDateCreated', 'r.ysnInactive', 'r.strContact', 'r.strAddress', 'r.strTelephone', 'strEmail');
        $this->addSelectFieldQuery($fields, $query, $additionalSelectField);
        
        if (isset($where['syncDate'])) {
            if (!empty($where['syncDate'])) {
                $syncDate = $where['syncDate'];
                $query->where(function ($query) use ($syncDate) {
                    $query->where('r.dtmDateCreated', '>=', date("Y-m-d H:i:s", $syncDate));
                    $query->orWhere('r.dtmDateEdit', '>=', date("Y-m-d H:i:s", $syncDate));
                });                
            }
            unset($where['syncDate']);
        }
        
        if (isset($where['strCustomerID'])) {
            $query->where('r.strCustomerID', 'like', $where['strCustomerID'] . '%');
            unset($where['strCustomerID']);
        }
        
        if (isset($where['strStatus'])) {
            if ($where['strStatus'] == Customer::STATUS_PROSPECTS) {
                $query->where('strCustomerID', 'LIKE', 'zzz%');
            }
            
            if ($where['strStatus'] == Customer::STATUS_ACTIVE) {
                $query->where('strCustomerID', 'NOT LIKE', 'zzz%');
                $query->where('ysnInactive', '!=', 1);
            }
            
            if ($where['strStatus'] == Customer::STATUS_INACTIVE) {
                $query->where('strCustomerID', 'NOT LIKE', 'zzz%');
                $query->where('ysnInactive', '=', 1);
            }
            unset($where['strStatus']);
        }
        
        if (isset($where['strCompany'])) {
            $query->where('r.strCompany', 'like', $where['strCompany'] . '%');
            unset($where['strCompany']);
        }
        
        $query->where('strCustomerID', '!=', 'zBlank');
    }    

    public function autoGeneratedStrCompanyID($companyName) {
        $firstLetterCompanyName = substr($companyName, 0, 1);
        $latestCustomer = Customer::select('strCompany', 'strCustomerID')->where('strCustomerID', 'LIKE', $firstLetterCompanyName . '%')->orderBy('strCustomerID', 'DESC')->first();
        $strNewCustomerID = '';

        if ($latestCustomer) {
            // Get the number from $latestCustomer
            $number = (int) filter_var($latestCustomer->strCustomerID, FILTER_SANITIZE_NUMBER_INT);

            $increasedNumber = $number + 1;

            if ($increasedNumber < 10) {
                $strNewCustomerID = $firstLetterCompanyName . '00' . $increasedNumber;
            } else if ($increasedNumber >= 10 && $increasedNumber < 100) {
                $strNewCustomerID = $firstLetterCompanyName . '0' . $increasedNumber;
            } else if ($increasedNumber >= 100 && $increasedNumber < 1000) {
                $strNewCustomerID = $firstLetterCompanyName . $increasedNumber;
            } else {
                $strNewCustomerID = $firstLetterCompanyName . $increasedNumber;
            }
        } else {
            $strNewCustomerID = $firstLetterCompanyName . '001';
        }

        return $strNewCustomerID;
    }
    
    public function create($input) {       
            
        $customer = $this->createCustomerStub($input, new Customer, 'create');
        $customerContactEloquent = new EloquentCustomerContactMethodRepository();
        $arrayContactList = array();
        
        if (is_array($input['contact_list']) && count($input['contact_list']) > 0) {
            foreach($input['contact_list'] as $singleContact) {
                if (isset($singleContact['isMainContact']) && ($singleContact['isMainContact']) == 1) {
                    // Update info from main contact to tblCustomer
                    $customer = $this->mapMainContactToCustomer($customer, $singleContact);
                    $mainContact = $singleContact;
                } else {
                    $contactObject = $customerContactEloquent->createCustomerContactMethodStub($singleContact, new CustomerContactMethod, 'create');
                    $arrayContactList[] = $contactObject;
                }
            } 
        }
		DB::transaction(function() use ($customer, $arrayContactList) {
			if (!$customer->save()) {                 
               return false;
			}
            
            if (count($arrayContactList) > 0) {
                foreach($arrayContactList as $singleContactObject) {
                    $singleContactObject->strCustomerID = $customer->strCustomerID;
                    if (!$singleContactObject->save()) {                 
                        return false;
                    }
                }
            }
            
            if (count($arrayContactList) > 0) {            
                foreach ($arrayContactList as $singleContactReturn) {
                    $singleContactReturn['isMainContact'] = 0;

                    /*
                     * TODO sonvq remove this strAddress, need to return real strAddress
                     */                    
                    $singleContactReturn['strAddress'] = null;
                }
            }            
            
            $customer->contact_list = $arrayContactList;
		});
        
        return $customer;
    }
    
    public function update($input, $customerEdit, $arrayCustomerContactMethod) {
        $customer = $this->createCustomerStub($input, $customerEdit, 'update');
        $customerContactEloquent = new EloquentCustomerContactMethodRepository();
        $arrayContactList = array();
        $arrayExistingCustomerContactID = array();
        $arrayContactListDelete = array();
        $arrayContactListAdd = array();
        $arrayContactListEdit = array();
        
        if (count($arrayCustomerContactMethod) > 0) {
            foreach ($arrayCustomerContactMethod as $singleContact) {
                $arrayExistingCustomerContactID[] = $singleContact['cntID'];
            }
        }
        
        if (is_array($input['contact_list']) && count($input['contact_list']) > 0) {
            foreach($input['contact_list'] as $singleContact) {
                if (isset($singleContact['isMainContact']) && ($singleContact['isMainContact']) == 1) {
                    // Update info from main contact to tblCustomer
                    $customer = $this->mapMainContactToCustomer($customer, $singleContact);
                } else {                                        
                    // List contact method add
                    if (!isset($singleContact['cntID']) || empty($singleContact['cntID'])) {
                        $contactObject = $customerContactEloquent->createCustomerContactMethodStub($singleContact, new CustomerContactMethod, 'create');                                                
                        $arrayContactListAdd[] = $contactObject;
                    }
                    // List contact method edit                    
                    else if (in_array($singleContact['cntID'], $arrayExistingCustomerContactID)) {
                        $existingId = $singleContact['cntID'];
                        $contactObject = $customerContactEloquent->createCustomerContactMethodStub($singleContact, $arrayCustomerContactMethod[$existingId], 'update');                                                
                        $arrayContactListEdit[] = $contactObject;
                        unset($arrayCustomerContactMethod[$existingId]);
                    }

                }
            } 
            // List contact method delete
            $arrayContactListDelete = $arrayCustomerContactMethod;
        }

		DB::transaction(function() use ($customer, $arrayContactListAdd, $arrayContactListEdit, $arrayContactListDelete) {
			if (!$customer->save()) {                 
               return false;
			}
            
            // Add new customer contact method
            if (count($arrayContactListAdd) > 0) {
                foreach($arrayContactListAdd as $singleContactAdd) {
                    $singleContactAdd->strCustomerID = $customer->strCustomerID;
                    if (!$singleContactAdd->save()) {                 
                        return false;
                    }
                }
            }
            
            // Edit existing customer contact method
            if (count($arrayContactListEdit) > 0) {
                foreach($arrayContactListEdit as $singleContactEdit) {
                    if (!$singleContactEdit->save()) {                 
                        return false;
                    }
                }
            }
            
            // Delete redundant contact method
            if (count($arrayContactListDelete) > 0) {
                foreach($arrayContactListDelete as $singleContactDelete) {
                    if (!$singleContactDelete->delete()) {                 
                        return false;
                    }
                }
            }
            
            $arrayContactList = array_merge($arrayContactListEdit, $arrayContactListAdd);
            if (count($arrayContactList) > 0) {            
                foreach ($arrayContactList as $singleContactReturn) {
                    $singleContactReturn['isMainContact'] = 0;

                    /*
                     * TODO sonvq remove this strAddress, need to return real strAddress
                     */                    
                    $singleContactReturn['strAddress'] = null;
                }
            }           
            
            $customer->contact_list = $arrayContactList;
		});
        
        return $customer;
    }
            
    private function mapMainContactToCustomer($customer, $mainContact) {
        if (isset($mainContact['strContact'])) {
            $customer->strContact = $mainContact['strContact'];    
        }
        
        if (isset($mainContact['strPhoneNumber'])) {
            $customer->strTelephone = $mainContact['strPhoneNumber'];
        }
        
        if (isset($mainContact['strEmail'])) {
            $customer->strEmail = $mainContact['strEmail'];    
        }
        
        if (isset($mainContact['strAddress'])) {
            $customer->strAddress = $mainContact['strAddress'];    
        }
        
        if (isset($mainContact['strTitle'])) {
            
        }
        
        return $customer;
    }
    
    /**
     * @param  $input
     * @return mixed
     */
    private function createCustomerStub($input, $customer, $action)
    {
        $customer->strCompany = $input['strCompany'];
        $customer->strIndustrialCode = $input['strIndustrialCode'];
        $customer->strRemarks = isset($input['strRemarks']) ? $input['strRemarks'] : '';
        
        if ($action == 'create') {
            $newCompanyID = $this->autoGeneratedStrCompanyID($input['strCompany']);
            $customer->strCustomerID = $newCompanyID;
            $customer->dtmDateCreated = date("Y-m-d H:i:s");
        }
        
        if ($action == 'update') {
            $customer->dtmDateEdit = date("Y-m-d H:i:s");
        }
                
        return $customer;
    }

}