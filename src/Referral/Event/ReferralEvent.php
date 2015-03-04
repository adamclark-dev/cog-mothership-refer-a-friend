<?php

namespace Message\Mothership\ReferAFriend\Referral\Event;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;
use Message\Cog\Event\Event;

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