<?php

namespace Message\Mothership\ReferAFriend\Reward\Type;

use Message\Cog\ValueObject\Collection as BaseCollection;

class Collection extends BaseCollection
{
	protected function _configure()
	{
		$this->addValidator(function(TypeInterface $item) {
			if (!$item instanceof TypeInterface) {
				$type = gettype($item) === 'object' ? get_class($item) : gettype($item);
				throw new \InvalidArgumentException('Item must be an instance of ' . __NAMESPACE__ . '\\TypeInterface, ' . $type . ' given');
			}
		});

		$this->setKey(function($item) {
			return $item->getName();
		});
	}
}