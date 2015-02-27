<?php

namespace Message\Mothership\ReferAFriend\Referral\RewardConfig;

use Message\Mothership\ReferAFriend\Reward\Config\Loader as RewardConfigLoader;
use Message\Mothership\ReferAFriend\Referral\ReferralProxy;
use Message\Mothership\ReferAFriend\Referral\EntityLoaderInterface;

class Loader implements EntityLoaderInterface
{
	private $_rewardConfigLoader;

	public function __construct(RewardConfigLoader $rewardConfigLoader)
	{
		$this->_rewardConfigLoader = $rewardConfigLoader;
	}

	public function getName()
	{
		return 'reward_config';
	}

	public function load(ReferralProxy $referral)
	{
		return $this->_rewardConfigLoader->getByID($referral->getRewardConfigID());
	}
}