<?php

namespace Message\Mothership\ReferAFriend\Referral\Referrer;

use Message\Mothership\ReferAFriend\Referral\EntityLoaderInterface;
use Message\Mothership\ReferAFriend\Referral\ReferralProxy;
use Message\User\Loader as UserLoader;

class Loader implements EntityLoaderInterface
{
	final public function __construct(UserLoader $userLoader)
	{
		$this->_userLoader = $userLoader;
	}

	public function getName()
	{
		return 'referrer';
	}

	public function load(ReferralProxy $referral)
	{
		$referrerID = $referral->getReferrerID();

		return $this->_userLoader->getByID($referrerID);
	}
}