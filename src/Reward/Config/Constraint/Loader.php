<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Constraint;

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
	private $_constraints;

	/**
	 * @var array
	 */
	private $_columns = [
		'reward_config_id',
		'name',
		'value'
	];

	final public function __construct(QueryBuilderFactory $qbFactory, Collection $constraints)
	{
		$this->_qbFactory   = $qbFactory;
		$this->_constraints = $constraints;
	}

	public function getName()
	{
		return 'constraint';
	}

	public function load(ConfigProxy $config)
	{
		$result = $this->_qbFactory
			->getQueryBuilder()
			->select($this->_columns)
			->from('refer_a_friend_reward_constraint')
			->where('reward_config_id = :id?i', ['id' => $config->getID()])
			->getQuery()
			->run()
		;

		$constraints = new Collection;

		foreach ($result as $row) {
			$constraint = $this->_constraints->get($row->name);
			$constraint->setValue($row->value);
			$constraints->add($this->_constraints->get($row->name));
		}

		return $constraints;
	}
}