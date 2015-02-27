<?php

namespace Message\Mothership\ReferAFriend\Referral\Constraint;

use Message\Mothership\ReferAFriend\Referral\ReferralEntityCollectionInterface;
use Message\Cog\ValueObject\Collection as BaseCollection;

class Collection extends BaseCollection implements ReferralEntityCollectionInterface
{
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