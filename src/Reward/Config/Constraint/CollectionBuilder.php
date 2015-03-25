<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Constraint;

use Message\Mothership\ReferAFriend\Reward\AbstractEntityCollectionBuilder;
use Message\Mothership\ReferAFriend\Reward\Type;

/**
 * Class CollectionBuilder
 * @package Message\Mothership\ReferAFriend\Reward\Config\Constraint
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for creating a collection of constraints against a specific reward type
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
			if (!$entity instanceof ConstraintInterface) {
				throw new \LogicException('Entity must be an instance of ConstraintInterface!');
			}

			if (in_array($entity->getName(), $type->validConstraints())) {
				$collection->add($entity);
			}
		}

		return $collection;
	}
}