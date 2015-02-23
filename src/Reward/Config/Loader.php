<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Referral\Type\Collection as ReferralTypes;
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
	 * @var ReferralTypes
	 */
	private $_referralTypes;

	/**
	 * @var Translator
	 */
	private $_translator;

	private $_columns = [
		'reward_config_id AS id',
		'name',
		'referral_type AS referralType',
		'created_at AS createdAt',
	];

	public function __construct(QueryBuilderFactory $qbFactory, ReferralTypes $referralTypes, Translator $translator)
	{
		$this->_qbFactory     = $qbFactory;
		$this->_referralTypes = $referralTypes;
		$this->_translator    = $translator;
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
			$config = new Config($this->_translator);
			$config->setID((int) $row->id);
			$config->setName($row->name);
			$config->setReferralType($this->_referralTypes->get($row->referralType));

			$createdAt = new \DateTime;
			$createdAt->setTimestamp($row->createdAt);
			$config->setCreatedAt($createdAt);

			$configs[] = $config;
		}

		return $asArray ? $configs : array_shift($configs);
	}
}