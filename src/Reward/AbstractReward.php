<?php

namespace Message\Mothership\ReferAFriend\Reward;

use Message\User;

/**
 * Class AbstractReward
 * @package Message\Mothership\ReferAFriend\Reward
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
abstract class AbstractReward implements RewardInterface
{
	/**
	 * @var Type\TypeInterface
	 */
	private $_type;

	/**
	 * @var string
	 */
	private $_status;

	/**
	 * @var User\UserInterface
	 */
	private $_referrer;

	/**
	 * @var string
	 */
	private $_referredEmail;

	/**
	 * @var Constraint\Collection
	 */
	private $_constraints;

	public function __construct(Type\TypeInterface $type)
	{
		$this->_type = $type;

		$this->_constraints = new Constraint\Collection;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setStatus($status)
	{
		if (!in_array($status, Statuses::getStatuses())) {
			if (is_string($status) || is_numeric($status)) {
				throw new \LogicException('Status of `' . $status . '` does not exist!');
			} else {
				throw new \InvalidArgumentException('Status must be a string, ' . gettype($status) . ' given');
			}
		}

		$this->_status = $status;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getStatus()
	{
		if (null === $this->_status) {
			throw new \LogicException('Status is not set!');
		}

		return $this->_status;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setReferrer(User\UserInterface $referrer)
	{
		if ($referrer instanceof User\AnonymousUser) {
			throw new \LogicException('Referrer must not be a registered user');
		};

		$this->_referrer = $referrer;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getReferrer()
	{
		if (null === $this->_referrer) {
			throw new \LogicException('Referrer not set!');
		}

		return $this->_referrer;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setReferredEmail($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			if (is_string($email) || is_numeric($email)) {
				throw new \LogicException('`' . $email . '` is not a valid email address!');
			} else {
				throw new \InvalidArgumentException('Email must be a string, ' . gettype($email) . ' given');
			}
		}

		$this->_referredEmail = $email;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getReferredEmail()
	{
		if (null === $this->_referredEmail) {
			throw new \LogicException('Referred email not set!');
		}

		return $this->_referredEmail;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConstraints()
	{
		return $this->_constraints;
	}

	/**
	 * {@inheritDoc}
	 */
	public function addConstraint(Constraint\ConstraintInterface $constraint)
	{
		$this->_constraints->add($constraint);
	}

	/**
	 * {@inheritDoc}
	 */
	public function isPending()
	{
		return $this->_status === Statuses::PENDING;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isActive()
	{
		return $this->_status === Statuses::ACTIVE;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isUsed()
	{
		return $this->_status === Statuses::USED;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isExpired()
	{
		return $this->_status === Statuses::EXPIRED;
	}
}