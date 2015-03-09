<?php

namespace Message\Mothership\ReferAFriend\Referral\Referrer;

use Message\Mothership\ReferAFriend\Referral\EntityLoaderInterface;
use Message\Mothership\ReferAFriend\Referral\ReferralProxy;
use Message\User\Loader as UserLoader;

/**
 * Class Loader
 * @package Message\Mothership\ReferAFriend\Referral\Referrer
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Wrapper around the user loader to allow users to be lazy loaded by the ReferralProxy object
 */
class Loader implements EntityLoaderInterface
{
	/**
	 * @var UserLoader
	 */
	private $_userLoader;

	final public function __construct(UserLoader $userLoader)
	{
		$this->_userLoader = $userLoader;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'referrer';
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ReferralProxy $referral)
	{
		$referrerID = $referral->getReferrerID();

		return $this->_userLoader->getByID($referrerID);
	}
}