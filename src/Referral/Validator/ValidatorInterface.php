<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;

/**
 * Interface ValidatorInterface
 * @package Message\Mothership\ReferAFriend\Referral\Validator
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Interface representing a referral validator.
 */
interface ValidatorInterface
{
	/**
	 * Method for determining if a referral is valid for the rule(s) that the validator represents.
	 *
	 * @param ReferralInterface $referral
	 *
	 * @return bool
	 */
	public function isValid(ReferralInterface $referral);

	/**
	 * Method for returning a translation string for an error message to display if the `isValid()` method returns false.
	 *
	 * @return string
	 */
	public function getMessage();
}