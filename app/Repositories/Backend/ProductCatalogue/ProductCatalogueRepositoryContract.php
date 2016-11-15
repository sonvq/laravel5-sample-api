<?php

namespace App\Repositories\Backend\ProductCatalogue;

use App\Models\ProductCatalogue;

/**
 * Interface ProductCatalogueRepositoryContract
 * @package App\Repositories\Backend\ProductCatalogue;
 */
interface ProductCatalogueRepositoryContract
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
     * @param  ProductCatalogue $productCatalogue
     * @param  $request
     * @return mixed
     */
    public function update(ProductCatalogue $productCatalogue, $request);

    /**
     * @param  ProductCatalogue $productCatalogue
     * @return mixed
     */
    public function destroy(ProductCatalogue $productCatalogue);

}