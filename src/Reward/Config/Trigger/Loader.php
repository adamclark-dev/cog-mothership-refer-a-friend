<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger;

use Message\Mothership\ReferAFriend\Reward\Config\EntityLoaderInterface;
use Message\Mothership\ReferAFriend\Reward\Config\ConfigProxy;

use Message\Cog\DB\QueryBuilderFactory;

class Loader implements EntityLoaderInterface
{
	/**
	 * @var QueryBuilderFactory
	 */
	private $_qbFactory;

	/**
	 * @var Collection
	 */
	private $_triggers;

	/**
	 * @var array
	 */
	private $_columns = [
		'reward_config_id',
		'name',
	];

	public function __construct(QueryBuilderFactory $qbFactory, Collection $triggers)
	{
		$this->_qbFactory = $qbFactory;
		$this->_triggers  = $triggers;
	}

	public function getName()
	{
		return 'trigger';
	}

	public function load(ConfigProxy $config)
	{
		$result = $this->_qbFactory
			->getQueryBuilder()
			->select($this->_columns)
			->from('refer_a_friend_reward_trigger')
			->where('reward_config_id = :id?i', ['id' => $config->getID()])
			->getQuery()
			->run()
		;

		$triggers = new Collection;

		foreach ($result as $row) {
			$triggers->add($this->_triggers->get($row->name));
		}

		return $triggers;
	}
}