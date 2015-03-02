<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Constraint;

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

	public function save(ConstraintInterface $constraint)
	{
		$this->_addToTransaction($constraint);
		$this->_commitTransaction();
	}

	public function saveBatch($constraints)
	{
		if (!$constraints instanceof Collection || !is_array($constraints)) {
			$type = gettype($constraints) === 'object' ? get_class($constraints) : gettype($constraints);
			throw new \InvalidArgumentException('$constraints must be an instance of Constraint\\Collection or an array, ' . $type . ' given');
		}

		foreach ($constraints as $constraint) {
			$this->_addToTransaction($constraint);
		}

		$this->_commitTransaction();
	}

	private function _addToTransaction(ConstraintInterface $constraint)
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
			'rewardConfigID' => $constraint->getRewardConfig()->getID(),
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