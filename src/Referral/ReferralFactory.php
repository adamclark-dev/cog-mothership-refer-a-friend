<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\Entity\EntityLoaderCollection;

/**
 * Class ReferralFactory
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for creating new instances of Referral and ReferralProxy
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
	 * Create a new instance of Referral and return it
	 *
	 * @return Referral
	 */
	public function getReferral()
	{
		return new Referral;
	}

	/**
	 * Create a new instance of ReferralProxy and set the entity loaders against it
	 *
	 * @return ReferralProxy
	 */
	public function getReferralProxy()
	{
		$referral = new ReferralProxy;
		$referral->setLoaders($this->_loaders);

		return $referral;
	}

}