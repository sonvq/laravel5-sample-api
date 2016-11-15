<?php 

namespace App\Models;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common\BaseModel;
use App\Models\ProductCatalogue\Traits\ProductCatalogueAttribute;

/**
 * Class ProductCatalogue
 * package App
 */
class ProductCatalogue extends BaseModel {

    use SoftDeletes, ProductCatalogueAttribute;
    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'product_catalogue';

    protected $statusProductCatalogue = [
        'published' => 'Published',
        'draft' => 'Draft',
        'pending' => 'Pending'
    ];

    public function getStatusProductCatalogue()
    {
        return $this->statusProductCatalogue;
    }
    
    public function getDefaultStatusProductCatalogue() {
        return current(array_keys($this->statusProductCatalogue));
    }
    
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'category_id', 'description', 'status', 'user_id', 'image', 'thumbnail'];

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
                $attributes[$keyAttribute] = asset('/') . config('app.general_upload_folder') . '/' .  config('product-catalogue.upload_path') . '/' . $singleAttribute;
            }                        
                        
        }

        return array_merge($attributes, $this->relationsToArray());
    }   
}