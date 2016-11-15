<?php 

namespace App\Models;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\CorporateDeck\Traits\CorporateDeckAttribute;
use App\Models\Common\BaseModel;

/**
 * Class CorporateDeck
 * package App
 */
class CorporateDeck extends BaseModel {

    use SoftDeletes, CorporateDeckAttribute;
    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'corporate_deck';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'description', 'user_id', 'status', 'pdf'];
       
    protected $statusCorporateDeck = [
        'published' => 'Published',
        'draft' => 'Draft',
        'pending' => 'Pending'
    ];

    public function getStatusCorporateDeck()
    {
        return $this->statusCorporateDeck;
    }
    
    public function getDefaultStatusCorporateDeck() {
        return current(array_keys($this->statusCorporateDeck));
    }
    
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
            if ($keyAttribute == 'pdf' || $keyAttribute == 'thumbnail') {
                $attributes[$keyAttribute] = asset('/') . config('app.general_upload_folder') . '/' .  config('corporate-deck.pdf_upload_path') . '/' . $singleAttribute;
            }                        
                        
        }

        return array_merge($attributes, $this->relationsToArray());
    }   
}