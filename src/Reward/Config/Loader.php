<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Reward\Type\Collection as Types;
use Message\Cog\DB\QueryBuilderFactory;
use Message\Cog\DB\Result;
use Message\Cog\Localisation\Translator;

class Loader
{
	/**
	 * @var QueryBuilderFactory
	 */
	private $_qbFactory;

	/**
	 * @var Types
	 */
	private $_types;

	/**
	 * @var ConfigFactory
	 */
	private $_configFactory;

	private $_columns = [
		'reward_config_id AS id',
		'name',
		'type',
		'message',
		'created_at AS createdAt',
	];

	public function __construct(QueryBuilderFactory $qbFactory, Types $types, ConfigFactory $configFactory)
	{
		$this->_qbFactory     = $qbFactory;
		$this->_types         = $types;
		$this->_configFactory = $configFactory;
	}

	public function getCurrent()
	{
		$result = $this->_getSelect()
			->where('deleted_at IS NULL')
			->orderBy('createdAt DESC')
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
			->orderBy('createdAt DESC')
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
			$config = $this->_configFactory->getConfigProxy();
			$config->setID((int) $row->id);
			$config->setName($row->name);
			$config->setType($this->_types->get($row->type));
			$config->setMessage($row->message);

			$createdAt = new \DateTime;
			$createdAt->setTimestamp($row->createdAt);
			$config->setCreatedAt($createdAt);

			$configs[] = $config;
		}

		return $asArray ? $configs : array_shift($configs);
	}
}