<?php

namespace App\Repositories\Api\V1\CorporateDeck;

use App\Models\CorporateDeck;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Events\Backend\CorporateDeck\CorporateDeckCreated;
use App\Events\Backend\CorporateDeck\CorporateDeckDeleted;
use App\Events\Backend\CorporateDeck\CorporateDeckUpdated;
use App\Repositories\Api\V1\EloquentBaseRepository;
/**
 * Class EloquentCorporateDeckRepository
 * @package App\Repositories\Api\V1\CorporateDeck;
 */
class EloquentCorporateDeckRepository extends EloquentBaseRepository
{
    protected $table = 'corporate_deck';
    protected $model = \App\Models\CorporateDeck::class;
	
    protected function onPreQuery(\Illuminate\Database\Query\Builder $query, &$where = null, &$sort = null, &$fields = array())
    {
        if (isset($where['updated_at'])) {
            if (!empty($where['updated_at'])) {        
                $query->where('updated_at', '>=', date("Y-m-d H:i:s", $where['updated_at']));            
            }
            unset($where['updated_at']);
        }
    }
    
}