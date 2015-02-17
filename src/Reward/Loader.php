<?php

namespace Message\Mothership\ReferAFriend\Reward;

use Message\Cog\DB\QueryBuilderFactory;
use Message\Cog\DB\Entity\EntityLoaderCollection;
use Message\Cog\DB\Result;

class Loader
{
	private $_qbFactory;
	private $_loaders;

	private $_columns = [
		'type',
		'status',
		'referrer_id',
		'referred_email'
	];

	final public function __construct(QueryBuilderFactory $qbFactory, EntityLoaderCollection $loaders)
	{
		$this->_qbFactory     = $qbFactory;
		$this->_entityLoaders = $loaders;
	}

	public function loadAll()
	{
		$result = $this->_getSelect()
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByID($id)
	{
		$result = $this->_getSelect()
			->where('reward_id = :id?i', ['id' => $id])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByType($type)
	{
		$result = $this->_getSelect()
			->where('type = :type?s', ['type' => $type])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	private function _getSelect()
	{
		return $this->_qbFactory
			->getQueryBuilder()
			->select($this->_columns)
			->from('refer_a_friend_reward')
		;
	}

	private function _bind(Result $result)
	{
		$rewards = $result->bindTo('Message\\Mothership\\ReferAFriend\\Reward\\RewardProxy');
		$loaders = $this->_loaders;

		array_walk($rewards, function (&$reward) use ($loaders) {
			$reward->setLoaders($loaders);
		});

		return $rewards;
	}
}