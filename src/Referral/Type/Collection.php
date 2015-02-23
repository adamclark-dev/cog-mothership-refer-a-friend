<?php

namespace Message\Mothership\ReferAFriend\Referral\Type;

use Message\Cog\ValueObject\Collection as BaseCollection;
use Message\Mothership\ReferAFriend\Form\ReferralType\AbstractForm as TypeForm;

class Collection extends BaseCollection
{
	protected function _configure()
	{
		$self = $this;

		$this->addValidator(function(TypeInterface $item) use ($self) {
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