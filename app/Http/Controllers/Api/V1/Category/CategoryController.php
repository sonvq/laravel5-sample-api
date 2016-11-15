<?php

namespace App\Http\Controllers\Api\V1\Category;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Repositories\Api\V1\Category\EloquentCategoryRepository;
use App\Repositories\Api\V1\ProductCatalogue\EloquentProductCatalogueRepository;
use App\Models\Category;
use App\Common\Helper;

class CategoryController extends BaseController {   

    /*
     * Get all corporate deck
     */
    public function index() {
        $eloquentCategoryRepository = new EloquentCategoryRepository();
        $eloquentProductCatalogueRepository = new EloquentProductCatalogueRepository();
        
        $query = $this->processInput();               

        $categoryList = $eloquentCategoryRepository->getAll($query['where'], $query['sort'], $query['limit'], $query['offset']);  
        
        $arrayCategoryId = [];
        $productByCategory = [];
        if (count($categoryList) > 0) {
            foreach ($categoryList as $singleCorporateDeck) {                
                $arrayCategoryId[] = $singleCorporateDeck['id'];
            }
            if (count($arrayCategoryId) > 0) {
                $queryWhere = $query['where'];                
                $queryWhere['category_id'] = $arrayCategoryId; 
                
                $productCatalogueListByCategory = $eloquentProductCatalogueRepository->getAll($queryWhere, array(), 0, 0);
                if (count($productCatalogueListByCategory) > 0) {                    
                    foreach($productCatalogueListByCategory as $singleProductCatalogue) {
                        $productByCategory[$singleProductCatalogue['category_id']][] = $singleProductCatalogue;
                    }
                }
            }
            
            foreach ($categoryList as $key=>$singleCategory) {
                $singleCategory['product_catalogue_list'] = isset($productByCategory[$singleCategory['id']]) ? $productByCategory[$singleCategory['id']] : [];
                $categoryList[$key] = $singleCategory;
            } 
        }                
        
        $result = [];
        $result['items'] = $categoryList;
        $result['page_size'] = $query['limit'];
        
        return Helper::okResponse($result);  
    }  

}
