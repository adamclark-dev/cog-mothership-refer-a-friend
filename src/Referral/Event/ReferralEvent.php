<?php

namespace Message\Mothership\ReferAFriend\Referral\Event;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;
use Message\Cog\Event\Event;

/**
 * Class ReferralEvent
 * @package Message\Mothership\ReferAFriend\Referral\Event
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Event for carrying a ReferralInterface instance between modules
 */
class ReferralEvent extends Event
{
	/**
	 * @var ReferralInterface
	 */
	private $_referral;

	/**
	 * @param ReferralInterface $referral
	 */
	public function setReferral(ReferralInterface $referral)
	{
		$this->_referral = $referral;
	}

	/**
	 * @return ReferralInterface
	 */
	public function getReferral()
	{
		if (null === $this->_referral) {
			throw new \LogicException('No referral set on event!');
		}

		return $this->_referral;
	}
}