<?php

namespace Message\Mothership\ReferAFriend\Reward\Type;

use Message\Cog\ValueObject\Collection as BaseCollection;

/**
 * Class Collection
 * @package Message\Mothership\ReferAFriend\Reward\Type
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class representing a collection of reward types
 */
class Collection extends BaseCollection
{
	/**
	 * {@inheritDoc}
	 */
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