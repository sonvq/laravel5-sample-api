<?php

namespace App\Listeners\Backend\ProductCatalogue;

/**
 * Class ProductCatalogueEventListener
 * @package App\Listeners\Backend\ProductCatalogue
 */
class ProductCatalogueEventListener
{
	/**
	 * @var string
	 */
	private $history_slug = 'ProductCatalogue';

	/**
	 * @param $event
	 */
	public function onCreated($event) {
		history()->log(
			$this->history_slug,
			'trans("history.backend.product-catalogue.created") <strong>'.$event->productCatalogue->name.'</strong>',
			$event->productCatalogue->id,
			'plus',
			'bg-green'
		);
	}

	/**
	 * @param $event
	 */
	public function onUpdated($event) {
		history()->log(
			$this->history_slug,
			'trans("history.backend.product-catalogue.updated") <strong>'.$event->productCatalogue->name.'</strong>',
			$event->productCatalogue->id,
			'save',
			'bg-aqua'
		);
	}

	/**
	 * @param $event
	 */
	public function onDeleted($event) {
		history()->log(
			$this->history_slug,
			'trans("history.backend.product-catalogue.deleted") <strong>'.$event->productCatalogue->name.'</strong>',
			$event->productCatalogue->id,
			'trash',
			'bg-maroon'
		);
	}

	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param  \Illuminate\Events\Dispatcher  $events
	 */
	public function subscribe($events)
	{
		$events->listen(
            \App\Events\Backend\ProductCatalogue\ProductCatalogueCreated::class,
			'App\Listeners\Backend\ProductCatalogue\ProductCatalogueEventListener@onCreated'
		);

		$events->listen(
            \App\Events\Backend\ProductCatalogue\ProductCatalogueUpdated::class,
			'App\Listeners\Backend\ProductCatalogue\ProductCatalogueEventListener@onUpdated'
		);

		$events->listen(
            \App\Events\Backend\ProductCatalogue\ProductCatalogueDeleted::class,
			'App\Listeners\Backend\ProductCatalogue\ProductCatalogueEventListener@onDeleted'
		);
	}
}