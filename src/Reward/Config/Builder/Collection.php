<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Builder;

use Message\Cog\ValueObject\Collection as BaseCollection;

class Collection extends BaseCollection
{
	protected function _configure()
	{
		$this->setKey(function ($item) {
			return $item->getName();
		});

		$this->addValidator(function ($item) {
			if (!$item instanceof BuilderInterface) {
				$type = gettype($item) === 'object' ? get_class($item) : gettype($item);
				throw new \InvalidArgumentException('Item must be an instance of ' . __NAMESPACE__ . '\\BuilderInterface, ' . $type . ' given');
			}
		});
	}
}