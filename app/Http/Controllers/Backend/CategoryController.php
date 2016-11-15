<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\Backend\Category\ManageCategoryRequest;
use App\Http\Requests\Backend\Category\UpdateCategoryRequest;
use App\Http\Requests\Backend\Category\StoreCategoryRequest;
use App\Repositories\Backend\Category\CategoryRepositoryContract;
use Illuminate\Http\Request;
use App\Models\Category;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Backend
 */
class CategoryController extends Controller
{
    
    /**
     * @var CategoryRepositoryContract
     */
    protected $category;
        
    /**
     * @param CategoryRepositoryContract $category
     */
    public function __construct(CategoryRepositoryContract $category)
    {
        $this->category = $category;
    }
    
    /**
     * @param ManageCorporateDeckRequest $request
     * @return mixed
     */
    public function get(ManageCategoryRequest $request) {
        return Datatables::of($this->category->getForDataTable())            
            ->addColumn('actions', function($category) {
                return $category->action_buttons;
            })
            ->make(true);
    }
    
	/**
     * @param ManageCategoryRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageCategoryRequest $request)
    {        
        return view('backend.category.index');
    }
    
	/**
     * @param ManageCategoryRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ManageCategoryRequest $request)
    {
        $category = new Category();
        return view('backend.category.create')
            ->with('category', $category);
    }

    /**
     * @param StoreCategoryRequest $request
     * @return mixed
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->category->create($request);
        
        return redirect()->route('admin.category.index')->withFlashSuccess(trans('alerts.backend.category.created'));
    }
    
    /**
     * @param Category $category
     * @param ManageCategoryRequest $request
     * @return mixed
     */
    public function destroy(Category $category, ManageCategoryRequest $request)
    {
        $this->category->destroy($category);
        return redirect()->back()->withFlashSuccess(trans('alerts.backend.category.deleted'));
    }
    
    /**
     * @param Category $category
     * @param ManageCategoryRequest $request
     * @return mixed
     */
    public function edit(Category $category, ManageCategoryRequest $request)
    {       
            
        return view('backend.category.edit')
            ->withCategory($category);
    }

	/**
     * @param Category $category
     * @param UpdateCategoryRequest $request
     * @return mixed
     */
    public function update(Category $category, UpdateCategoryRequest $request)
    {
        $this->category->update($category, $request);
        return redirect()->route('admin.category.index')->withFlashSuccess(trans('alerts.backend.category.updated'));
    }

}