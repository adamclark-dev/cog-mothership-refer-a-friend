<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Mothership\ReferAFriend\Reward\Config\Loader as RewardConfigLoader;
use Message\User;

class ReferralBuilder
{
	/**
	 * @var ReferralFactory
	 */
	private $_factory;

	/**
	 * @var RewardConfigLoader
	 */
	private $_configLoader;

	/**
	 * @var
	 */
	private $_user;

	final public function __construct(ReferralFactory $factory, RewardConfigLoader $configLoader, User\UserInterface $user)
	{
		$this->_factory      = $factory;
		$this->_configLoader = $configLoader;
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
		$referral->setRewardConfig($this->_configLoader->getCurrent());
		$referral->setReferredEmail($data['email']);
		$referral->setStatus(Statuses::PENDING);

		return $referral;
	}
}