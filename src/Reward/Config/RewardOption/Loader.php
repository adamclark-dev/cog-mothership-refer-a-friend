<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\RewardOption;

use Message\Mothership\ReferAFriend\Reward\Config\EntityLoaderInterface;
use Message\Mothership\ReferAFriend\Reward\Config\ConfigProxy;
use Message\Cog\DB\QueryBuilderFactory;

/**
 * Class Loader
 * @package Message\Mothership\ReferAFriend\Reward\Config\RewardOption
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for lazy loading the reward options into the RewardProxy
 */
class Loader implements EntityLoaderInterface
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

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'reward_option';
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ConfigProxy $config)
	{
		$result = $this->_qbFactory
			->getQueryBuilder()
			->select($this->_columns)
			->from('refer_a_friend_reward_option')
			->where('reward_config_id = :id?i', ['id' => $config->getID()])
			->getQuery()
			->run()
		;

		$rewardOptions = new Collection;

		foreach ($result as $row) {
			$rewardOption = $this->_rewardOptions->get($row->name);
			$rewardOption->setValue($row->value);
			$rewardOptions->add($this->_rewardOptions->get($row->name));
		}

		return $rewardOptions;
	}
}