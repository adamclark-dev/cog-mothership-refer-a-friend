<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Cog\DB;
use Message\User\UserInterface;

class Delete implements DB\TransactionalInterface
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

	public function delete(Config $config)
	{
		$this->_addToTransaction($config);
		$this->_commitTransaction();
	}

	public function deleteBatch(array $configs)
	{
		foreach ($configs as $config) {
			$this->_addToTransaction($config);
		}

		$this->_commitTransaction();
	}

	public function deleteAll()
	{
		$this->_trans->add("
			UPDATE
				refer_a_friend_reward_config
			SET
				deleted_at    = :deletedAt?d,
				deleted_by    = :deletedBy?i
			WHERE
				deleted_at IS NULL
		", [
			'deletedAt' => new \DateTime,
			'deletedBy' => $this->_currentUser->id,
		]);

		$this->_commitTransaction();
	}

	private function _addToTransaction(Config $config)
	{
		$this->_trans->add("
			UPDATE
				refer_a_friend_reward_config
			SET
				deleted_at    = :deletedAt?d,
				deleted_by    = :deletedBy?i
			WHERE
				reward_config_id = :id?i
		", [
			'id'   => $config->getID(),
			'deletedAt' => new \DateTime,
			'deletedBy' => $this->_currentUser->id,
		]);
	}

	private function _commitTransaction()
	{
		if (false === $this->_transOverride) {
			$this->_trans->commit();
		}
	}
}