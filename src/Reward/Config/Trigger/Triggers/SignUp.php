<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger\Triggers;

use Message\Mothership\ReferAFriend\Reward\Config\Trigger\TriggerInterface;
use Message\User\Event\Event;

class SignUp implements TriggerInterface
{
	public function getName()
	{
		return 'refer_a_friend_sign_up';
	}

	public function getDisplayName()
	{
		return 'ms.refer.reward.triggers.sign_up.name';
	}

	public function getDescription()
	{
		return 'ms.refer.reward.triggers.sign_up.description';
	}

	public function getEventName()
	{
		return Event::LOGIN;
	}
}