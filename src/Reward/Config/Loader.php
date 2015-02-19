<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Referral\Type\Collection as ReferralTypes;
use Message\Cog\DB\QueryBuilderFactory;
use Message\Cog\DB\Result;

class Loader
{
	private $_qbFactory;
	private $_referralTypes;

	private $_columns = [
		'reward_config_id AS id',
		'name',
		'referral_type AS referralType',
		'updated_at AS updatedAt',
	];

	public function __construct(QueryBuilderFactory $qbFactory, ReferralTypes $referralTypes)
	{
		$this->_qbFactory     = $qbFactory;
		$this->_referralTypes = $referralTypes;
	}

	public function getCurrent()
	{
		$result = $this->_getSelect()
			->where('deleted_at IS NULL')
			->orderBy('updated_at DESC')
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByID($id)
	{
		$result = $this->_getSelect()
			->where('reward_config_id = :id?i', ['id' => $id])
			->getQuery()
			->run()
		;

		return $this->_bind($result, false);
	}

	public function getAll()
	{
		$result = $this->_getSelect()
			->orderBy('updated_at DESC')
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
			->from('refer_a_friend_reward_config')
		;
	}

	private function _bind(Result $result, $asArray = true)
	{
		$configs = [];

		foreach ($result as $row) {
			$config = new Config;
			$config->setID((int) $row->id);
			$config->setName($row->name);
			$config->setReferralType($this->_referralTypes->get($row->referralType));

			$updatedAt = new \DateTime;
			$updatedAt->setTimestamp($row->updatedAt);
			$config->setUpdatedAt($updatedAt);

			$configs[] = $config;
		}

		return $asArray ? $configs : array_shift($configs);
	}
}