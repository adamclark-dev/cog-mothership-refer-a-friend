<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\RewardOption;

use Message\Mothership\ReferAFriend\Reward\EntityCollectionInterface;
use Message\Cog\ValueObject\Collection as BaseCollection;

class Collection extends BaseCollection implements EntityCollectionInterface
{
	protected function _configure()
	{
		$this->addValidator(function ($item) {
			if (!$item instanceof RewardOptionInterface) {
				$type = gettype($item) === 'object' ? get_class($item) : gettype($item);
				throw new \InvalidArgumentException('Option must be an instance of RewardOptionInterface, ' . $type . ' given');
			}
		});

		$this->setKey(function ($item) {
			return $item->getName();
		});
	}
}