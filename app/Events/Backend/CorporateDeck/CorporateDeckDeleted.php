<?php

namespace App\Events\Backend\CorporateDeck;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class CorporateDeckDeleted
 * @package App\Events\Backend\CorporateDeck
 */
class CorporateDeckDeleted extends Event
{
	use SerializesModels;

	/**
	 * @var $corporateDeck
	 */
	public $corporateDeck;

	/**
	 * @param $corporateDeck
	 */
	public function __construct($corporateDeck)
	{
		$this->corporateDeck = $corporateDeck;
	}
}