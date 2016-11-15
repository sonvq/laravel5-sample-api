<?php

namespace App\Events\Backend\CorporateDeck;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class CorporateDeckCreated
 * @package App\Events\Backend\CorporateDeck
 */
class CorporateDeckCreated extends Event
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