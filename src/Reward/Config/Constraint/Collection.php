<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Constraint;

use Message\Mothership\ReferAFriend\Reward\EntityCollectionInterface;
use Message\Cog\ValueObject\Collection as BaseCollection;

/**
 * Class Collection
 * @package Message\Mothership\ReferAFriend\Reward\Config\Constraint
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Collection of constraints. Used both for registering constraints in the service container, and for storing against a
 * reward configuration
 */
class Collection extends BaseCollection implements EntityCollectionInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected function _configure()
	{
		$this->addValidator(function($item) {
			if (!$item instanceof ConstraintInterface) {
				$type = gettype($item) === 'object' ? get_class($item) : gettype($item);
				throw new \InvalidArgumentException('Item must be an instance of ' . __NAMESPACE__ . '\\ConstraintInterface, ' . $type . ' given');
			}
		});

		$this->setKey(function($item) {
			return $item->getName();
		});
	}

}