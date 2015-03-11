<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger;

use Message\Mothership\ReferAFriend\Reward\RewardEntityInterface;

/**
 * Interface TriggerInterface
 * @package Message\Mothership\ReferAFriend\Reward\Config\Trigger
 *
 * Interface representing a trigger. A trigger determines which event
 */
interface TriggerInterface extends RewardEntityInterface
{
	/**
	 * @return string
	 */
	public function getEventName();
}