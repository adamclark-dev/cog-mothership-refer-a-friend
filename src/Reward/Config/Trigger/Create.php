<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger;

use Message\Mothership\ReferAFriend\Reward\Config\Config;
use Message\Cog\DB\Transaction;
use Message\Cog\DB\TransactionalInterface;

class Create implements TransactionalInterface
{
	private $_transaction;
	private $_transOverride = false;

	public function __construct(Transaction $transaction)
	{
		$this->_transaction = $transaction;
	}

	public function setTransaction(Transaction $transaction)
	{
		$this->_transaction   = $transaction;
		$this->_transOverride = true;
	}

	public function save(Config $config)
	{
		foreach ($config->getTriggers() as $trigger) {
			$this->_addToTransaction($config, $trigger);
		}

		$this->_commitTransaction();
	}

	private function _addToTransaction(Config $config, TriggerInterface $trigger)
	{
		$this->_transaction->add("
			INSERT INTO
				refer_a_friend_reward_trigger
				(
					reward_config_id,
					`name`
				)
				VALUES
				(
					:rewardConfigID?i,
					:name?s
				)
		", [
			'rewardConfigID' => $config->getID(),
			'name'           => $trigger->getName(),
		]);
	}

	private function _commitTransaction()
	{
		if (false === $this->_transOverride) {
			$this->_transaction->commit();
		}
	}
}