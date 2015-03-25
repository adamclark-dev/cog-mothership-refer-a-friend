<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

/**
 * Class CollectionFactory
 * @package Message\Mothership\ReferAFriend\Referral\Validator
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for cloning instances of the validator collection to avoid messages being wrongly inherited
 */
class CollectionFactory
{
	/**
	 * @var Collection
	 */
	private $_collection;

	/**
	 * @param Collection $collection
	 */
	public function __construct(Collection $collection)
	{
		$this->_collection = $collection;
	}

	/**
	 * Get a fresh copy of the validator collection to be used as a validator itself
	 *
	 * @return Collection
	 */
	public function getValidator()
	{
		return clone $this->_collection;
	}
}