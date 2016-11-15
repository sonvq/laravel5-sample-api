<?php

namespace App\Repositories\Backend\Category;

use App\Models\Category;

/**
 * Interface CategoryRepositoryContract
 * @package App\Repositories\Backend\Category;
 */
interface CategoryRepositoryContract
{

	/**
     * @return mixed
     */
    public function getCount();

	/**
     * @return mixed
     */
    public function getForDataTable();

    /**
     * @param  $request
     * @return mixed
     */
    public function create($request);

    /**
     * @param  Category $category
     * @param  $request
     * @return mixed
     */
    public function update(Category $category, $request);

    /**
     * @param  Category $category
     * @return mixed
     */
    public function destroy(Category $category);

}