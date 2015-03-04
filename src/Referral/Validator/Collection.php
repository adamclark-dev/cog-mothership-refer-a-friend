<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

use Message\Cog\ValueObject\Collection as BaseCollection;
use Message\Mothership\ReferAFriend\Referral\ReferralInterface;

class Collection extends BaseCollection implements ValidatorInterface
{
	private $_message;

	public function _configure()
	{
		$this->addValidator(function ($item) {
			if (!$item instanceof ValidatorInterface) {
				throw new \InvalidArgumentException('Must be an instance of ValidatorInterface');
			}
		});
	}

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

	public function getMessage()
	{
		return $this->_message;
	}
}