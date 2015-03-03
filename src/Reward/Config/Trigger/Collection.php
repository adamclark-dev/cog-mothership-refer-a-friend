<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger;

use Message\Mothership\ReferAFriend\Reward\EntityCollectionInterface;
use Message\Mothership\ReferAFriend\Reward\Type\TypeInterface;
use Message\Cog\ValueObject\Collection as BaseCollection;

class Collection extends BaseCollection implements EntityCollectionInterface
{
	protected function _configure()
	{
		$this->addValidator(function($item) {
			if (!$item instanceof TriggerInterface) {
				$type = gettype($item) === 'object' ? get_class($item) : gettype($item);
				throw new \InvalidArgumentException('Item must be an instance of ' . __NAMESPACE__ . '\\TriggerInterface, ' . $type . ' given');
			}
		});

		$this->setKey(function ($item) {
			return $item->getName();
		});
	}

	public function getAvailable(TypeInterface $type)
	{
		$available = [];

		foreach ($this->all() as $trigger) {
			if (in_array($type->getName(), $trigger->getTypes())) {
				$available[] = $trigger;
			}
		}

		return $available;
	}
}