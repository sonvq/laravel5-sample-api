<?php

namespace App\Listeners\Backend\Category;

/**
 * Class CategoryEventListener
 * @package App\Listeners\Backend\Category
 */
class CategoryEventListener
{
	/**
	 * @var string
	 */
	private $history_slug = 'Category';

	/**
	 * @param $event
	 */
	public function onCreated($event) {
		history()->log(
			$this->history_slug,
			'trans("history.backend.category.created") <strong>'.$event->category->name.'</strong>',
			$event->category->id,
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
			'trans("history.backend.category.updated") <strong>'.$event->category->name.'</strong>',
			$event->category->id,
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
			'trans("history.backend.category.deleted") <strong>'.$event->category->name.'</strong>',
			$event->category->id,
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
            \App\Events\Backend\Category\CategoryCreated::class,
			'App\Listeners\Backend\Category\CategoryEventListener@onCreated'
		);

		$events->listen(
            \App\Events\Backend\Category\CategoryUpdated::class,
			'App\Listeners\Backend\Category\CategoryEventListener@onUpdated'
		);

		$events->listen(
            \App\Events\Backend\Category\CategoryDeleted::class,
			'App\Listeners\Backend\Category\CategoryEventListener@onDeleted'
		);
	}
}