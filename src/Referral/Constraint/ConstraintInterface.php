<?php

namespace Message\Mothership\ReferAFriend\Referral\Constraint;

use Message\Mothership\ReferAFriend\Referral\ReferralEntityInterface;
use Message\Mothership\ReferAFriend\Referral\ReferralInterface;

interface ConstraintInterface extends ReferralEntityInterface
{
	/**
	 * @param ReferralInterface $referral
	 */
	public function validate(ReferralInterface $referral);
}