<?php

namespace Message\Mothership\ReferAFriend\Reward;

/**
 * Interface ReferralEntityInterface
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 */
interface RewardEntityInterface
{
	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string
	 */
	public function getDisplayName();

	/**
	 * @return string
	 */
	public function getDescription();
}