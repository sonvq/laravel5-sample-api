<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\Backend\ProductCatalogue\ManageProductCatalogueRequest;
use App\Http\Requests\Backend\ProductCatalogue\UpdateProductCatalogueRequest;
use App\Http\Requests\Backend\ProductCatalogue\StoreProductCatalogueRequest;
use App\Repositories\Backend\ProductCatalogue\ProductCatalogueRepositoryContract;
use Illuminate\Http\Request;
use App\Models\ProductCatalogue;
use App\Models\Category;

/**
 * Class ProductCatalogueController
 * @package App\Http\Controllers\Backend
 */
class ProductCatalogueController extends Controller
{
    
    /**
     * @var ProductCatalogueRepositoryContract
     */
    protected $productCatalogue;
        
    /**
     * @param ProductCatalogueRepositoryContract $productCatalogue
     */
    public function __construct(ProductCatalogueRepositoryContract $productCatalogue)
    {
        $this->productCatalogue = $productCatalogue;
    }
    
    /**
     * @param ManageProductCatalogueRequest $request
     * @return mixed
     */
    public function get(ManageProductCatalogueRequest $request) {
        return Datatables::of($this->productCatalogue->getForDataTable())            
            ->addColumn('actions', function($productCatalogue) {
                return $productCatalogue->action_buttons;
            })
            ->make(true);
    }
    
	/**
     * @param ManageProductCatalogueRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageProductCatalogueRequest $request)
    {        
        return view('backend.product-catalogue.index');
    }
    
	/**
     * @param ManageProductCatalogueRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ManageProductCatalogueRequest $request)
    {
        $productCatalogue = new ProductCatalogue();
        $category = Category::select('id', 'name')->whereNull('deleted_at')->get();
        $arrayCategory = [];
        if (count($category) > 0) {
            foreach ($category as $singleCategory) {
                $arrayCategory[$singleCategory->id] = $singleCategory->name;
            }
        }
        
        return view('backend.product-catalogue.create')
            ->with(['productCatalogue' => $productCatalogue, 'arrayCategory' => $arrayCategory]);
    }
    
    /**
     * @param ProductCatalogue $product_catalogue
     * @param ManageProductCatalogueRequest $request
     * @return mixed
     */
    public function edit(ProductCatalogue $product_catalogue, ManageProductCatalogueRequest $request)
    {       
        $category = Category::select('id', 'name')->whereNull('deleted_at')->get();
        $arrayCategory = [];
        if (count($category) > 0) {
            foreach ($category as $singleCategory) {
                $arrayCategory[$singleCategory->id] = $singleCategory->name;
            }
        }
        
        return view('backend.product-catalogue.edit')
            ->with(['product_catalogue' => $product_catalogue, 'arrayCategory' => $arrayCategory]);
    }
    
    /**
     * @param ProductCatalogue $product_catalogue
     * @param UpdateProductCatalogueRequest $request
     * @return mixed
     */
    public function update(ProductCatalogue $product_catalogue, UpdateProductCatalogueRequest $request)
    {
        $this->productCatalogue->update($product_catalogue, $request);
        return redirect()->route('admin.product-catalogue.index')->withFlashSuccess(trans('alerts.backend.product-catalogue.updated'));
    }

    /**
     * @param StoreProductCatalogueRequest $request
     * @return mixed
     */
    public function store(StoreProductCatalogueRequest $request)
    {
        $this->productCatalogue->create($request);
        
        return redirect()->route('admin.product-catalogue.index')->withFlashSuccess(trans('alerts.backend.product-catalogue.created'));
    }
    
    /**
     * @param ProductCatalogue $product_catalogue
     * @param ManageProductCatalogueRequest $request
     * @return mixed
     */
    public function destroy(ProductCatalogue $product_catalogue, ManageProductCatalogueRequest $request)
    {
        $this->productCatalogue->destroy($product_catalogue);
        return redirect()->back()->withFlashSuccess(trans('alerts.backend.product-catalogue.deleted'));
    }

}