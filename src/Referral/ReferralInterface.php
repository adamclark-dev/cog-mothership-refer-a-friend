<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\User\UserInterface;
use Message\Cog\ValueObject\Authorship;
use Message\Mothership\ReferAFriend\Reward\Config\Config as RewardConfig;

/**
 * Interface ReferralInterface
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Interface representing a referral made to an email address by a logged in user
 */
interface ReferralInterface
{
	/**
	 * Get the ID of the referral
	 *
	 * @return int
	 */
	public function getID();

	/**
	 * Set the ID of the referral
	 *
	 * @param int $id
	 */
	public function setID($id);

	/**
	 * Get the user who made the referral
	 *
	 * @return UserInterface
	 */
	public function getReferrer();

	/**
	 * Set the referring user
	 *
	 * @param UserInterface $referrer
	 */
	public function setReferrer(UserInterface $referrer);

	/**
	 * Get the reward configuration that this referral uses
	 *
	 * @return RewardConfig
	 */
	public function getRewardConfig();

	/**
	 * Set the reward configuration that this referral uses
	 *
	 * @param RewardConfig $rewardConfig
	 */
	public function setRewardConfig(RewardConfig $rewardConfig);

	/**
	 * Get the email address that this referral was set to
	 *
	 * @return string $email
	 */
	public function getReferredEmail();

	/**
	 * Set the email address that this referral was sent to
	 *
	 * @param string $email
	 */
	public function setReferredEmail($email);

	/**
	 * Get the name of the referred person
	 *
	 * @return string
	 */
	public function getReferredName();

	/**
	 * Set the name of the referred person
	 *
	 * @param string $name
	 */
	public function setReferredName($name);

	/**
	 * Get the status of the referral
	 *
	 * @return string
	 */
	public function getStatus();

	/**
	 * Set the status of the referral
	 *
	 * @param string
	 */
	public function setStatus($status);

	/**
	 * Check whether the referral should have been triggered by an event name
	 *
	 * @param string $eventName    The event name that is assigned to the trigger
	 *
	 * @return bool                returns true if triggered
	 */
	public function hasTriggered($eventName);

	/**
	 * Set the time the referral was created
	 *
	 * @param \DateTime $createdAt
	 */
	public function setCreatedAt(\DateTime $createdAt);

	/**
	 * Get the time the referral was created
	 *
	 * @return \DateTime
	 */
	public function getCreatedAt();
}