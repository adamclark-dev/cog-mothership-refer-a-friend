<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;
use Message\User\Loader;

class UserExists implements ValidatorInterface
{
	private $_userLoader;

	public function __construct(Loader $userLoader)
	{
		$this->_userLoader = $userLoader;
	}

	public function isValid(ReferralInterface $referral)
	{
		$users = $this->_userLoader->getByEmail($referral->getReferredEmail());

		return empty($users);
	}

	public function getMessage()
	{
		return 'ms.refer.validator.user_exists';
	}
}