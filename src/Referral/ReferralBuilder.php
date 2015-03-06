<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Mothership\ReferAFriend\Reward\Config\Config as RewardConfig;
use Message\User;

class ReferralBuilder
{
	/**
	 * @var ReferralFactory
	 */
	private $_factory;

	/**
	 * @var RewardConfig
	 */
	private $_config;

	/**
	 * @var
	 */
	private $_user;

	final public function __construct(ReferralFactory $factory, RewardConfig $config, User\UserInterface $user)
	{
		$this->_factory      = $factory;
		$this->_config       = $config;
		$this->_user         = $user;
	}

	public function build(array $data)
	{
		if ($this->_user instanceof User\AnonymousUser) {
			throw new \LogicException('User must be logged in to make a referral');
		}

		if (!array_key_exists('email', $data)) {
			throw new \LogicException('Data must contain a value with a key of `email`');
		}

		$referral = $this->_factory->getReferral();

		$referral->setReferrer($this->_user);
		$referral->setRewardConfig($this->_config);
		$referral->setReferredEmail($data['email']);
		$referral->setReferredName($data['name']);
		$referral->setStatus(Statuses::PENDING);
		$referral->setCreatedAt(new \DateTime);

		return $referral;
	}
}