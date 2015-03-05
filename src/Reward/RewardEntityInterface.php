<?php

namespace Message\Mothership\ReferAFriend\Reward;

/**
 * Interface ReferralEntityInterface
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@message.co.uk>
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