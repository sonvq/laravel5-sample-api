<?php

namespace App\Repositories\Api\V1\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Api\V1\EloquentBaseRepository;
/**
 * Class EloquentCategoryRepository
 * @package App\Repositories\Api\V1\Category;
 */
class EloquentCategoryRepository extends EloquentBaseRepository
{
    protected $table = 'category';
    protected $model = \App\Models\Category::class;
	
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