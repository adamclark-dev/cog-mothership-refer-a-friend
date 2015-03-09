<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Constraint;

use Message\Mothership\ReferAFriend\Reward\Config\ConfigInterface;
use Message\Cog\DB\Transaction;
use Message\Cog\DB\TransactionalInterface;

/**
 * Class Create
 * @package Message\Mothership\ReferAFriend\Reward\Config\Constraint
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for saving constraints to the database as part of a reward configuration. Constraints are saved as a basic
 * key/value pair.
 * Implements TransactionalInterface so that many constraints can be saved at once.
 */
class Create implements TransactionalInterface
{
	/**
	 * @var Transaction
	 */
	private $_transaction;

	/**
	 * @var bool
	 */
	private $_transOverride = false;

	public function __construct(Transaction $transaction)
	{
		$this->_transaction = $transaction;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setTransaction(Transaction $transaction)
	{
		$this->_transaction   = $transaction;
		$this->_transOverride = true;
	}

	/**
	 * Save all constraints from a reward configuration to the database
	 *
	 * @param ConfigInterface $config
	 */
	public function save(ConfigInterface $config)
	{
		foreach ($config->getConstraints() as $constraint) {
			$this->_addToTransaction($config, $constraint);
		}

		$this->_commitTransaction();
	}

	/**
	 * Add query saving the constraint name and value, as well as the reward config ID, to the database transaction
	 *
	 * @param ConfigInterface $config
	 * @param ConstraintInterface $constraint
	 */
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

	/**
	 * Commit the database transaction if it has not been overridden.
	 */
	private function _commitTransaction()
	{
		if (false === $this->_transOverride) {
			$this->_transaction->commit();
		}
	}
}