<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Constraint;

use Message\Mothership\ReferAFriend\Reward\Config\ConfigInterface;
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

	public function save(ConfigInterface $config)
	{
		foreach ($config->getConstraints() as $constraint) {
			$this->_addToTransaction($config, $constraint);
		}

		$this->_commitTransaction();
	}

	private function _addToTransaction(ConfigInterface $config, ConstraintInterface $constraint)
	{
		$this->_transaction->add("
			INSERT INTO
				refer_a_friend_reward_constraint
				(
					reward_config_id,
					`name`,
					`value`
				)
				VALUES
				(
					:rewardConfigID?i,
					:name?s,
					:value?s
				)
		", [
			'rewardConfigID' => $config->getID(),
			'name'           => $constraint->getName(),
			'value'          => $constraint->getValue(),
		]);
	}

	private function _commitTransaction()
	{
		if (false === $this->_transOverride) {
			$this->_transaction->commit();
		}
	}
}