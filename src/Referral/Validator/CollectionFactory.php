<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

class CollectionFactory
{
	private $_collection;

	public function __construct(Collection $collection)
	{
		$this->_collection = $collection;
	}

	public function getValidator()
	{
		return clone $this->_collection;
	}
}