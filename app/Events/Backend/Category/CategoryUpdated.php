<?php

namespace App\Events\Backend\Category;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class CategoryUpdated
 * @package App\Events\Backend\Category
 */
class CategoryUpdated extends Event
{
	use SerializesModels;

	/**
	 * @var $category
	 */
	public $category;

	/**
	 * @param $category
	 */
	public function __construct($category)
	{
		$this->category = $category;
	}
}