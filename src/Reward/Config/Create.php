<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Cog\DB;
use Message\User\UserInterface;

class Create implements DB\TransactionalInterface
{
	private $_trans;
	private $_transOverride = false;
	private $_currentUser;

	public function __construct(DB\Transaction $trans, UserInterface $currentUser)
	{
		$this->_trans       = $trans;
		$this->_currentUser = $currentUser;
	}

	public function setTransaction(DB\Transaction $trans)
	{
		$this->_trans         = $trans;
		$this->_transOverride = true;
	}

	public function save(Config $config)
	{
		$this->_addToTransaction($config);
		$this->_commitTransaction();
	}

	public function saveBatch(array $configs)
	{
		foreach ($configs as $config) {
			$this->_addToTransaction($config);
		}

		$this->_commitTransaction();
	}

	private function _addToTransaction(Config $config)
	{
		$this->_trans->add("
			INSERT INTO
				refer_a_friend_reward_config
				(
					`name`,
					referral_type,
					created_at,
					created_by
				)
			VALUES
				(
					:name?s,
					:type?s,
					:createdAt?d,
					:createdBy?i
				)
			;
		", [
			'name' => $config->getName(),
			'type' => $config->getReferralType()->getName(),
			'createdAt' => new \DateTime,
			'createdBy' => $this->_currentUser->id,
		]);
	}

	private function _commitTransaction()
	{
		if (false === $this->_transOverride) {
			$this->_trans->commit();
		}
	}
}