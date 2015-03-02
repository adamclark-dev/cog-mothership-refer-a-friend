<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Constraint;

use Message\Mothership\ReferAFriend\Referral\AbstractEntityCollectionBuilder;
use Message\Mothership\ReferAFriend\Reward\Type;

class CollectionBuilder extends AbstractEntityCollectionBuilder
{
	public function getCollection()
	{
		return new Collection;
	}

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