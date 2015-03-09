<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;
use Message\User\Loader;

/**
 * Class UserExists
 * @package Message\Mothership\ReferAFriend\Referral\Validator
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Validator for ensuring that the referred email address is not already registered to the site
 */
class UserExists implements ValidatorInterface
{
	/**
	 * @var Loader
	 */
	private $_userLoader;

	public function __construct(Loader $userLoader)
	{
		$this->_userLoader = $userLoader;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid(ReferralInterface $referral)
	{
		$users = $this->_userLoader->getByEmail($referral->getReferredEmail());

		return empty($users);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMessage()
	{
		return 'ms.refer.validator.user_exists';
	}
}