<?php

namespace Message\Mothership\ReferAFriend\Reward\Type;

/**
 * Class NoRewardType
 * @package Message\Mothership\ReferAFriend\Reward\Type
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * A default reward type. Does not generate a reward of any type, it merely invites the referred email to visit the
 * website. It will be marked as complete if a user logs in with the referred email address.
 */
class NoRewardType implements TypeInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'no_reward';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDisplayName()
	{
		return 'ms.refer.reward.types.no_reward.name';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDescription()
	{
		return 'ms.refer.reward.types.no_reward.description';
	}

	/**
	 * {@inheritDoc}
	 */
	public function validConstraints()
	{
		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function validTriggers()
	{
		return [
			'refer_a_friend_sign_up',
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function validRewardOptions()
	{
		return [];
	}
}