<?php

namespace App\Events\Backend\ProductCatalogue;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class ProductCatalogueDeleted
 * @package App\Events\Backend\ProductCatalogue
 */
class ProductCatalogueDeleted extends Event
{
	use SerializesModels;

	/**
	 * @var $productCatalogue
	 */
	public $productCatalogue;

	/**
	 * @param $productCatalogue
	 */
	public function __construct($productCatalogue)
	{
		$this->productCatalogue = $productCatalogue;
	}
}