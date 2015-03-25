<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;

/**
 * Class CurrentUser
 * @package Message\Mothership\ReferAFriend\Referral\Validator
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Validator for ensuring that the email address being referred does not match that of the current logged in user
 */
class CurrentUser implements ValidatorInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function isValid(ReferralInterface $referral)
	{
		return $referral->getReferredEmail() !== $referral->getReferrer()->email;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMessage()
	{
		return 'ms.refer.validator.current_user';
	}
}