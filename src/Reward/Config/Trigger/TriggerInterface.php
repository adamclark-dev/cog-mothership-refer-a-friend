<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger;

use Message\Mothership\ReferAFriend\Reward\RewardEntityInterface;

interface TriggerInterface extends RewardEntityInterface
{
	/**
	 * @return string
	 */
	public function getEventName();
}