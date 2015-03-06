<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Mothership\ReferAFriend\Reward\Config\Config as RewardConfig;
use Message\User;

/**
 * Class Referral
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
class Referral implements ReferralInterface
{
	/**
	 * @var int
	 */
	private $_id;

	/**
	 * @var string
	 */
	private $_status;

	/**
	 * @var User\UserInterface
	 */
	protected $_referrer;

	/**
	 * @var RewardConfig
	 */
	protected $_rewardConfig;

	/**
	 * @var string
	 */
	private $_referredEmail;

	/**
	 * @var string
	 */
	private $_referredName;

	/**
	 * @var \DateTime
	 */
	private $_createdAt;

	public function setID($id)
	{
		if (!is_numeric($id)) {
			$type = gettype($id) === 'object' ? get_class($id) : gettype($id);
			throw new \InvalidArgumentException('ID must be numeric, ' . $type . ' given');
		}

		$id = (int) $id;

		if ($id <= 0) {
			throw new \InvalidArgumentException('ID must be a number greater than zero, ' . $id . ' given');
		}

		$this->_id = (int) $id;
	}

	public function getID()
	{
		return $this->_id;
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
			throw new \LogicException('Referrer must be a registered user');
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

		$this->_referredEmail = trim($email);
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
	public function setReferredName($name)
	{
		if (!is_string($name)) {
			throw new \InvalidArgumentException('Referred name must be a string');
		}

		$this->_referredName = trim($name);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getReferredName()
	{
		return $this->_referredName;
	}

	/**
	 * @param RewardConfig $rewardConfig
	 */
	public function setRewardConfig(RewardConfig $rewardConfig)
	{
		$this->_rewardConfig = $rewardConfig;
	}

	/**
	 * @return RewardConfig
	 */
	public function getRewardConfig()
	{
		return $this->_rewardConfig;
	}

	/**
	 * @param string $eventName
	 *
	 * @return bool
	 */
	public function hasTriggered($eventName)
	{
		$triggers = $this->getRewardConfig()->getTriggers()->filterByEvent($eventName);

		return count($triggers) > 0;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setCreatedAt(\DateTime $createdAt)
	{
		$this->_createdAt = $createdAt;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCreatedAt()
	{
		return $this->_createdAt;
	}
}