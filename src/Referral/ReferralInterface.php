<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\User\UserInterface;
use Message\Cog\ValueObject\Authorship;
use Message\Mothership\ReferAFriend\Reward\Config\Config as RewardConfig;

/**
 * Interface ReferralInterface
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
interface ReferralInterface
{
	/**
	 * @return Type\TypeInterface
	 */
	public function getType();

	/**
	 * @return UserInterface
	 */
	public function getReferrer();

	/**
	 * @param UserInterface $referrer
	 */
	public function setReferrer(UserInterface $referrer);

	/**
	 * @return RewardConfig
	 */
	public function getRewardConfig();

	/**
	 * @param RewardConfig $rewardConfig
	 */
	public function setRewardConfig(RewardConfig $rewardConfig);

	/**
	 * @return string $email
	 */
	public function getReferredEmail();

	/**
	 * @param string $email
	 */
	public function setReferredEmail($email);

	/**
	 * @return Constraint\Collection;
	 */
	public function getConstraints();

	/**
	 * @param Constraint\ConstraintInterface $constraint
	 */
	public function addConstraint(Constraint\ConstraintInterface $constraint);

	/**
	 * @return Trigger\Collection
	 */
	public function getTriggers();

	/**
	 * @param Trigger\TriggerInterface $trigger
	 */
	public function addTrigger(Trigger\TriggerInterface $trigger);

	/**
	 * @return string
	 */
	public function getStatus();

	/**
	 * @param string
	 */
	public function setStatus($status);

	/**
	 * @return bool
	 */
	public function isPending();

	/**
	 * @return bool
	 */
	public function isActive();

	/**
	 * @return bool
	 */
	public function isExpired();

	/**
	 * @return bool
	 */
	public function isUsed();
}