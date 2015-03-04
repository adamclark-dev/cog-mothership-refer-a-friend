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

	public function filterByEvent($eventName)
	{
		if (!is_string($eventName)) {
			$type = gettype($eventName) === 'object' ? get_class($eventName) : gettype($eventName);
			throw new \InvalidArgumentException('Event name must be a string, ' . $type . ' given');
		}

		$triggers = [];

		foreach ($this as $trigger) {
			if ($eventName === $trigger->getEventName()) {
				$triggers[] = $trigger;
			}
		}

		return new Collection($triggers);
	}
}