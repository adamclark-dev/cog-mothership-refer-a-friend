<?php

namespace Message\Mothership\ReferAFriend\Reward;

use Message\User\UserInterface;

/**
 * Interface RewardInterface
 * @package Message\Mothership\ReferAFriend\Reward
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
interface RewardInterface
{
	/**
	 * @return Type\Type
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