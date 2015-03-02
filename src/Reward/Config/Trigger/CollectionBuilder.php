<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger;

use Message\Mothership\ReferAFriend\Reward\AbstractEntityCollectionBuilder;
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
			if (!$entity instanceof TriggerInterface) {
				throw new \LogicException('Entity must be an instance of TriggerInterface!');
			}

			if (in_array($entity->getName(), $type->validConstraints())) {
				$collection->add($entity);
			}
		}

		return $collection;
	}
}