<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\Entity\EntityLoaderInterface as BaseInterface;

/**
 * Interface EntityLoaderInterface
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Interface for lazy loading entities into the ReferralInterface
 */
interface EntityLoaderInterface extends BaseInterface
{
	/**
	 * Get the name of the type of entity being loaded into the ReferralProxy
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Load the appropriate entities into the ReferralProxy
	 *
	 * @param ReferralProxy $referral
	 */
	public function load(ReferralProxy $referral);
}