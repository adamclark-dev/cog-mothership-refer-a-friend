<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger;

use Message\Mothership\ReferAFriend\Reward\RewardEntityInterface;

/**
 * Interface TriggerInterface
 * @package Message\Mothership\ReferAFriend\Reward\Config\Trigger
 *
 * Interface representing a trigger. A trigger determines which event causes a referral to be marked as complete.
 */
interface TriggerInterface extends RewardEntityInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getEventName();
}