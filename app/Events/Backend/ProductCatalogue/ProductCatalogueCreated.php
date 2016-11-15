<?php

namespace App\Events\Backend\ProductCatalogue;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class ProductCatalogueCreated
 * @package App\Events\Backend\ProductCatalogue
 */
class ProductCatalogueCreated extends Event
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