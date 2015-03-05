<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;

class CurrentUser implements ValidatorInterface
{
	public function isValid(ReferralInterface $referral)
	{
		return $referral->getReferredEmail() !== $referral->getReferrer()->email;
	}

	public function getMessage()
	{
		return 'ms.refer.validator.current_user';
	}
}