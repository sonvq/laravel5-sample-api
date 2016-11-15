<?php 

namespace App\Models;

use App\Models\Common\BaseModel;

/**
 * Class CustomerContactMethod
 * package App
 */
class CustomerContactMethod extends BaseModel {
        
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tblCustomerContactMethod';
    
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
    
    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function postProcessModel()
    {        
        $attributes = $this->attributesToArray();
        
        foreach ($attributes as $keyAttribute => $singleAttribute) {    
            if (in_array($keyAttribute, $this->dateFields) && !empty($singleAttribute)) {
                $attributes[$keyAttribute] = strtotime($singleAttribute);
            }
        }
        
        $arrayCombined = array_merge($attributes, $this->relationsToArray());
        /*
         * TODO SONVQ modify strAddress here
         */
        $arrayCombined['strAddress'] = null;
        $arrayCombined['isMainContact'] = 0;
                
        return $arrayCombined;
    }    
}

