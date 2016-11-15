<?php

namespace App\Models\Access\User;

use App\Models\Access\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Access\User\Traits\Attribute\UserAttribute;
use App\Models\Access\User\Traits\Relationship\UserRelationship;

/**
 * Class User
 * @package App\Models\Access\User
 */
class User extends Authenticatable
{

    use SoftDeletes, UserAccess, UserAttribute, UserRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'status', 'confirmation_code', 'confirmed'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * @var array
     * Array date fields need to convert from date to timestamp
     */
    protected $dateFields = ['created_at', 'updated_at', 'deleted_at'];
    
    public static function getUpdateProfileRules($input) {
        return [            
            'password'                  => 'min:128|confirmed',
            'password_confirmation'     => 'min:128',
        ];
    }
    
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
