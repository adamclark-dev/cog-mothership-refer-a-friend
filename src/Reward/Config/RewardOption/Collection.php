<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\RewardOption;

use Message\Mothership\ReferAFriend\Reward\EntityCollectionInterface;
use Message\Cog\ValueObject\Collection as BaseCollection;

/**
 * Class Collection
 * @package Message\Mothership\ReferAFriend\Reward\Config\RewardOption
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class representing a collection of reward options, either to be registered in the service container, or stored against
 * a reward configuration.
 */
class Collection extends BaseCollection implements EntityCollectionInterface
{
	/**
	 * {@inheritDoc}
	 */
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