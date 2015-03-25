<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Reward\Type\Collection as Types;
use Message\Cog\DB\QueryBuilderFactory;
use Message\Cog\DB\Result;
use Message\Cog\Localisation\Translator;

/**
 * Class Loader
 * @package Message\Mothership\ReferAFriend\Reward\Config
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for loading configurations from the database
 */
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

	/**
	 * Get all active (non-deleted) configurations from the database
	 *
	 * @return array
	 */
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

	/**
	 * Get a specific configuration by its ID
	 *
	 * @param int $id
	 *
	 * @return ConfigProxy
	 */
	public function getByID($id)
	{
		$result = $this->_getSelect()
			->where('reward_config_id = :id?i', ['id' => $id])
			->getQuery()
			->run()
		;

		return $this->_bind($result, false);
	}

	/**
	 * Get all configurations, including deleted ones
	 *
	 * @return array
	 */
	public function getAll()
	{
		$result = $this->_getSelect()
			->orderBy('createdAt DESC')
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	/**
	 * Get an instance of the QueryBuilder with the correct columns and table already set
	 *
	 * @return \Message\Cog\DB\QueryBuilder
	 */
	private function _getSelect()
	{
		return $this->_qbFactory
			->getQueryBuilder()
			->select($this->_columns)
			->from('refer_a_friend_reward_config')
		;
	}

	/**
	 * Loop through database result and bind to a ConfigProxy object. If $asArray is set to false, a single configuration
	 * will be returned.
	 *
	 * @param Result $result
	 * @param bool $asArray
	 *
	 * @return array | ConfigProxy
	 */
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