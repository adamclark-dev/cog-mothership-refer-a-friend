<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\RewardOption;

use Message\Mothership\ReferAFriend\Reward\AbstractEntityCollectionBuilder;
use Message\Mothership\ReferAFriend\Reward\Type;

/**
 * Class CollectionBuilder
 * @package Message\Mothership\ReferAFriend\Reward\Config\RewardOption
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for creating a collection of reward options against a specific type of reward configuration
 */
class CollectionBuilder extends AbstractEntityCollectionBuilder
{
	/**
	 * {@inheritDoc}
	 */
	public function getCollection()
	{
		return new Collection;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCollectionFromType(Type\TypeInterface $type)
	{
		$collection = $this->getCollection();

		foreach ($this->_completeCollection as $entity) {
			if (!$entity instanceof RewardOptionInterface) {
				throw new \LogicException('Entity must be an instance of RewardOptionInterface!');
			}

			if (in_array($entity->getName(), $type->validRewardOptions())) {
				$collection->add($entity);
			}
		}

		return $collection;
	}
}