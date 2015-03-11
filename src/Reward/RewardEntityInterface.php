<?php

namespace Message\Mothership\ReferAFriend\Reward;

/**
 * Interface ReferralEntityInterface
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * An interface representing a reward entity. A reward entity is generally used when configuring a type of reward, used
 * to determine how a reward should be validated and managed.
 */
interface RewardEntityInterface
{
	/**
	 * Get the name of the reward entity. Must be unique within its own collection.
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get a translation string for the human readable name of the reward entity.
	 *
	 * @return string
	 */
	public function getDisplayName();

	/**
	 * Get a translation string for the description of the reward entity.
	 *
	 * @return string
	 */
	public function getDescription();
}