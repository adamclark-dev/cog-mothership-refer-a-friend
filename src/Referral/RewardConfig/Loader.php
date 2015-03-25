<?php

namespace Message\Mothership\ReferAFriend\Referral\RewardConfig;

use Message\Mothership\ReferAFriend\Reward\Config\Loader as RewardConfigLoader;
use Message\Mothership\ReferAFriend\Referral\ReferralProxy;
use Message\Mothership\ReferAFriend\Referral\EntityLoaderInterface;

/**
 * Class Loader
 * @package Message\Mothership\ReferAFriend\Referral\RewardConfig
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Wrapper around the reward config Loader to allow configs to be lazy loaded by the ReferralProxy object
 */
class Loader implements EntityLoaderInterface
{
	/**
	 * @var RewardConfigLoader
	 */
	private $_rewardConfigLoader;

	final public function __construct(RewardConfigLoader $rewardConfigLoader)
	{
		$this->_rewardConfigLoader = $rewardConfigLoader;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'reward_config';
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ReferralProxy $referral)
	{
		return $this->_rewardConfigLoader->getByID($referral->getRewardConfigID());
	}
}