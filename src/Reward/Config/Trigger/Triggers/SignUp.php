<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger\Triggers;

use Message\Mothership\ReferAFriend\Reward\Config\Trigger\TriggerInterface;
use Message\User\Event\Event;

/**
 * Class SignUp
 * @package Message\Mothership\ReferAFriend\Reward\Config\Trigger\Triggers
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Trigger for a user signing up to the website
 */
class SignUp implements TriggerInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'refer_a_friend_sign_up';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDisplayName()
	{
		return 'ms.refer.reward.triggers.sign_up.name';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDescription()
	{
		return 'ms.refer.reward.triggers.sign_up.description';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEventName()
	{
		return Event::LOGIN;
	}
}