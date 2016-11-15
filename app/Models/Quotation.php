<?php 

namespace App\Models;

use App\Models\Common\BaseModel;

/**
 * Class Quotation
 * package App
 */
class Quotation extends BaseModel {
    
    /*
     * Const variables
     */
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    
    /*
     * Date field need to be converted to timestamp
     */
    protected $dateFields = ['created_at', 'updated_at', 'deleted_at', 'dtmDateCreated', 'dtmDateEdit'];
    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tblQuotation';
    
    /**
	 * The database connection used by the model.
	 *
	 * @var string
	 */
    protected $connection = 'erp_live';
	
    public function postProcessModel()
    {        
        $attributes = $this->attributesToArray();
        
        foreach ($attributes as $keyAttribute => $singleAttribute) {    
            if (in_array($keyAttribute, $this->dateFields) && !empty($singleAttribute)) {
                $attributes[$keyAttribute] = strtotime($singleAttribute);
            }
            
            if ($keyAttribute == 'strInvoiceNo') {
                $attributes['strPdfDetail'] = 'http://www.sharp-world.com/products/img/copier/products/ar_6031n_6026n_6023n_6020n_6023d_6020d_6023_6020/brochure/AR-6023D_6020D_BRO_S.pdf';
                $attributes[$keyAttribute] = '#QN' . $singleAttribute;
            }                           
        }

        $arrayCombined = array_merge($attributes, $this->relationsToArray());
        
        if (isset($arrayCombined['ysnCompleted']) && isset($arrayCombined['ysnDO'])) {
            if ($arrayCombined['ysnCompleted'] == 0 && $arrayCombined['ysnDO'] == 0) {
                $arrayCombined['strStatus'] = Quotation::STATUS_PENDING;
            }
            
            if ($arrayCombined['ysnCompleted'] == 1 && $arrayCombined['ysnDO'] == 0) {
                $arrayCombined['strStatus'] = Quotation::STATUS_ACCEPTED;
            }
            
            if ($arrayCombined['ysnCompleted'] == 0 && $arrayCombined['ysnDO'] == 1) {
                $arrayCombined['strStatus'] = Quotation::STATUS_DECLINED;
            }
        }
        return $arrayCombined;
    }   
    
}