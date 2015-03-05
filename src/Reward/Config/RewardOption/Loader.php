<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\RewardOption;

use Message\Mothership\ReferAFriend\Referral\ReferralProxy;
use Message\Cog\DB\QueryBuilderFactory;

class Loader
{
	/**
	 * @var QueryBuilderFactory
	 */
	private $_qbFactory;

	/**
	 * @var Collection
	 */
	private $_rewardOptions;

	/**
	 * @var array
	 */
	private $_columns = [
		'reward_config_id',
		'name',
		'value'
	];

	final public function __construct(QueryBuilderFactory $qbFactory, Collection $rewardOptions)
	{
		$this->_qbFactory     = $qbFactory;
		$this->_rewardOptions = $rewardOptions;
	}

	public function getName()
	{
		return 'reward_option';
	}

	public function load(ReferralProxy $referral)
	{
		$result = $this->_qbFactory
			->getQueryBuilder()
			->select($this->_columns)
			->from('refer_a_friend_reward_option')
			->where('reward_config_id = :id?i', ['id' => $referral->getRewardConfig()->getID()])
			->getQuery()
			->run()
		;

		$constraints = new Collection;

		foreach ($result as $row) {
			$constraint = $this->_rewardOptions->get($row->name);
			$constraint->setValue($row->value);
			$constraints->add($this->_rewardOptions->get($row->name));
		}

		return $constraints;
	}
}