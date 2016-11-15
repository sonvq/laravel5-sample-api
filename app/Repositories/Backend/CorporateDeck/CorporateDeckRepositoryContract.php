<?php

namespace App\Repositories\Backend\CorporateDeck;

use App\Models\CorporateDeck;

/**
 * Interface CorporateDeckRepositoryContract
 * @package App\Repositories\Backend\CorporateDeck;
 */
interface CorporateDeckRepositoryContract
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
     * @param  CorporateDeck $corporateDeck
     * @param  $request
     * @return mixed
     */
    public function update(CorporateDeck $corporateDeck, $request);

    /**
     * @param  CorporateDeck $corporateDeck
     * @return mixed
     */
    public function destroy(CorporateDeck $corporateDeck);

}