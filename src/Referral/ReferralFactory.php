<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\Entity\EntityLoaderCollection;

/**
 * Class ReferralFactory
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
class ReferralFactory
{
	/**
	 * @var EntityLoaderCollection
	 */
	private $_loaders;

	final public function __construct(EntityLoaderCollection $loaders)
	{
		$this->_loaders = $loaders;
	}

	/**
	 * @return Referral
	 */
	public function getReferral()
	{
		return new Referral;
	}

	/**
	 * @return ReferralProxy
	 */
	public function getReferralProxy()
	{
		$referral = new ReferralProxy;
		$referral->setLoaders($this->_loaders);

		return $referral;
	}

}