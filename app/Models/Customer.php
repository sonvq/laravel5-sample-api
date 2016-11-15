<?php 

namespace App\Models;

use App\Models\Common\BaseModel;

/**
 * Class Customer
 * package App
 */
class Customer extends BaseModel {
        
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tblCustomer';       
    
    /*
     * Const variables
     */
    const STATUS_PROSPECTS = 'prospects';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STRING_FOR_PROSPECTS_STATUS = 'zzz';
            
    /**
	 * The database connection used by the model.
	 *
	 * @var string
	 */
    protected $connection = 'erp_temp';
    
    /*
     * Primary key
     */
    protected $primaryKey = 'cntID';


    /*
     * Indicate this table is not timestamps db
     */
    public $timestamps = false; 
  
    public function postProcessModel()
    {        
        $attributes = $this->attributesToArray();
        
        foreach ($attributes as $keyAttribute => $singleAttribute) {    
            if (in_array($keyAttribute, $this->dateFields) && !empty($singleAttribute)) {
                $attributes[$keyAttribute] = strtotime($singleAttribute);
            }
                        
        }

        $arrayCombined = array_merge($attributes, $this->relationsToArray());
        
        if (isset($arrayCombined['strCustomerID'])) {            
            if (strtolower(substr($arrayCombined['strCustomerID'], 0, 3)) == Customer::STRING_FOR_PROSPECTS_STATUS) {                
                $arrayCombined['strStatus'] = Customer::STATUS_PROSPECTS; 
            } else {
                if (isset($arrayCombined['ysnInactive'])) {
                    if ($arrayCombined['ysnInactive'] == 1) {
                        $arrayCombined['strStatus'] = Customer::STATUS_INACTIVE;         
                    } else {
                        $arrayCombined['strStatus'] = Customer::STATUS_ACTIVE;         
                    }
                }
            }
        }
        
        $arrayCombined = $this->createMainContactFromCustomer($arrayCombined);        
        
        return $arrayCombined;
    } 
    
    public function getCreateRules($input) {
        return array(
            'strCompany' => 'required|max:100',
            'strIndustrialCode' => 'required',
            'contact_list' => 'required|array',
            'contact_list.*.strContact' => 'required|max:50',
            'contact_list.*.strTitle' => 'required|max:50',
            'contact_list.*.strPhoneNumber' => 'required|max:50',
            'contact_list.*.strEmail' => 'required|max:50|email',
            'contact_list.*.strAddress' => 'required|max:255',
            'contact_list.*.isMainContact' => 'required|in:0,1',
        );
    }
    
    public function getUpdateRules($input) {
        return array(
            'cntID' => 'required',
            'strCompany' => 'required|max:100',
            'strIndustrialCode' => 'required',
            'contact_list' => 'required|array',
            'contact_list.*.strContact' => 'required|max:50',
            'contact_list.*.strTitle' => 'required|max:50',
            'contact_list.*.strPhoneNumber' => 'required|max:50',
            'contact_list.*.strEmail' => 'required|max:50|email',
            'contact_list.*.strAddress' => 'required|max:255',
            'contact_list.*.isMainContact' => 'required|in:0,1',
        );
    }
    
    protected function createMainContactFromCustomer($arrayCombined) {
        // Add main contact to customer
        $mainContact = array();
        $mainContact['strContact'] = isset($arrayCombined['strContact']) ? $arrayCombined['strContact'] : null;
        $mainContact['strAddress'] = isset($arrayCombined['strAddress']) ? $arrayCombined['strAddress'] : null;
        $mainContact['strPhoneNumber'] = isset($arrayCombined['strTelephone']) ? $arrayCombined['strTelephone'] : null;
        $mainContact['strEmail'] = isset($arrayCombined['strEmail']) ? $arrayCombined['strEmail'] : null;
        
        /*
         * TODO SONVQ modify strTitle here
         */
        $mainContact['strTitle'] = null;
        $mainContact['isMainContact'] = 1;
        $mainContact['cntID'] = null;
        
        $arrayCombined['contact_list'] = (isset($arrayCombined['contact_list']) && !empty($arrayCombined['contact_list'])) ? $arrayCombined['contact_list'] : array();
        $arrayCombined['contact_list'][] = $mainContact;
        
        return $arrayCombined;
    }
    
}