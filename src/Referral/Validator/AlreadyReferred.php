<?php

namespace Message\Mothership\ReferAFriend\Referral\Validator;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;
use Message\Mothership\ReferAFriend\Referral\Loader;

/**
 * Class AlreadyReferred
 * @package Message\Mothership\ReferAFriend\Referral\Validator
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Validator for checking if a user has already been referred to the website, and rejects if so
 */
class AlreadyReferred implements ValidatorInterface
{
	/**
	 * @var Loader
	 */
	private $_loader;

	public function __construct(Loader $loader)
	{
		$this->_loader = $loader;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid(ReferralInterface $referral)
	{
		$existing = $this->_loader->getByEmail($referral->getReferredEmail());

		return count($existing) <= 0;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMessage()
	{
		return 'ms.refer.validator.already_referred';
	}
}