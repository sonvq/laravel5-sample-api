<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model {
      
    /**
     * @var array
     * Array date fields need to convert from date to timestamp
     */
    protected $dateFields = ['created_at', 'updated_at', 'deleted_at', 'dtmDateCreated', 'dtmDateEdit'];
    
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

        return array_merge($attributes, $this->relationsToArray());
    }    
}
