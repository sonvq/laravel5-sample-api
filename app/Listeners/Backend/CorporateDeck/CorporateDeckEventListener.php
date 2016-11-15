<?php

namespace App\Listeners\Backend\CorporateDeck;

/**
 * Class CorporateDeckEventListener
 * @package App\Listeners\Backend\CorporateDeck
 */
class CorporateDeckEventListener
{
	/**
	 * @var string
	 */
	private $history_slug = 'CorporateDeck';

	/**
	 * @param $event
	 */
	public function onCreated($event) {       
		history()->log(
			$this->history_slug,
			'trans("history.backend.corporate-deck.created") <strong>'.$event->corporateDeck->name.'</strong>',
			$event->corporateDeck->id,
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
			'trans("history.backend.corporate-deck.updated") <strong>'.$event->corporateDeck->name.'</strong>',
			$event->corporateDeck->id,
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
			'trans("history.backend.corporate-deck.deleted") <strong>'.$event->corporateDeck->name.'</strong>',
			$event->corporateDeck->id,
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
            \App\Events\Backend\CorporateDeck\CorporateDeckCreated::class,
			'App\Listeners\Backend\CorporateDeck\CorporateDeckEventListener@onCreated'
		);

		$events->listen(
            \App\Events\Backend\CorporateDeck\CorporateDeckUpdated::class,
			'App\Listeners\Backend\CorporateDeck\CorporateDeckEventListener@onUpdated'
		);

		$events->listen(
            \App\Events\Backend\CorporateDeck\CorporateDeckDeleted::class,
			'App\Listeners\Backend\CorporateDeck\CorporateDeckEventListener@onDeleted'
		);
	}
}