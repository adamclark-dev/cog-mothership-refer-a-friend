<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

use Message\Cog\ValueObject\Collection as BaseCollection;
use Message\Mothership\ReferAFriend\Referral\ReferralInterface;

/**
 * Class Collection
 * @package Message\Mothership\ReferAFriend\Referral\Validator
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Meta validator that loops through all registered validators to check the validity of a referral. If the `isValid()`
 * method of one of the registered validators returns false, the `isValid()` method of the collection will also return
 * false, and the collection will inherit the message of the validator that return false.
 */
class Collection extends BaseCollection implements ValidatorInterface
{
	private $_message;

	/**
	 * {@inheritDoc}
	 */
	public function _configure()
	{
		$this->addValidator(function ($item) {
			if (!$item instanceof ValidatorInterface) {
				throw new \InvalidArgumentException('Must be an instance of ValidatorInterface');
			}
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid(ReferralInterface $referral)
	{
		foreach ($this as $validator) {
			if (false === $validator->isValid($referral)) {
				$this->_message = $validator->getMessage();

				return false;
			}
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMessage()
	{
		return $this->_message;
	}
}