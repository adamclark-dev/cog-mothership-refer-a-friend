<?php

namespace Message\Mothership\ReferAFriend\Referral\Trigger;

use Message\Mothership\ReferAFriend\Referral\ReferralEntityInterface;

interface TriggerInterface extends ReferralEntityInterface
{
	/**
	 * @return string
	 */
	public function getEventName();
}