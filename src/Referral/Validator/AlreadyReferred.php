<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;
use Message\Mothership\ReferAFriend\Referral\Loader;

class AlreadyReferred implements ValidatorInterface
{
	private $_loader;

	public function __construct(Loader $loader)
	{
		$this->_loader = $loader;
	}

	public function isValid(ReferralInterface $referral)
	{
		$existing = $this->_loader->getByEmail($referral->getReferredEmail());

		return count($existing) <= 0;
	}

	public function getMessage()
	{
		return 'ms.refer.validator.already_referred';
	}
}