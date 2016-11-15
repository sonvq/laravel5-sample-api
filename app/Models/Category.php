<?php 

namespace App\Models;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category\Traits\CategoryAttribute;
use App\Models\Common\BaseModel;

/**
 * Class Category
 * package App
 */
class Category extends BaseModel {

    use SoftDeletes, CategoryAttribute;
        
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'category';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'user_id'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function user() {
		return $this->hasOne(User::class, 'id', 'user_id');
	}
    
    public function postProcessModel()
    {        
        $attributes = $this->attributesToArray();
        
        foreach ($attributes as $keyAttribute => $singleAttribute) {    
            if (in_array($keyAttribute, $this->dateFields) && !empty($singleAttribute)) {
                $attributes[$keyAttribute] = strtotime($singleAttribute);
            }
            
            if ($keyAttribute == 'image' || $keyAttribute == 'thumbnail') {
                $attributes[$keyAttribute] = asset('/') . config('app.general_upload_folder') . '/' .  config('category.upload_path') . '/' . $singleAttribute;
            }                        
                        
        }

        return array_merge($attributes, $this->relationsToArray());
    }   
    
    public function product_catalogue()
    {
        return $this->hasMany(ProductCatalogue::class, 'category_id', 'id');
    }

}