<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;

interface ValidatorInterface
{
	/**
	 * @param ReferralInterface $referral
	 *
	 * @return bool
	 */
	public function isValid(ReferralInterface $referral);

	/**
	 * @return string
	 */
	public function getMessage();
}