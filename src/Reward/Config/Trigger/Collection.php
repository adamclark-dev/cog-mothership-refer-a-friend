<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger;

use Message\Mothership\ReferAFriend\Reward\EntityCollectionInterface;
use Message\Mothership\ReferAFriend\Reward\Type\TypeInterface;
use Message\Cog\ValueObject\Collection as BaseCollection;

/**
 * Class Collection
 * @package Message\Mothership\ReferAFriend\Reward\Config\Trigger
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Collection of triggers to be called from the service container, or to be set against a reward configuration
 */
class Collection extends BaseCollection implements EntityCollectionInterface
{
	/**
	 * {@inheritDoc}
	 */
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

	/**
	 * Get all triggers on the reward configuration that trigger on a specific event
	 *
	 * @param $eventName
	 * @return Collection
	 */
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