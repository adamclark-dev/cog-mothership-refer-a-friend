<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Trigger;

use Message\Mothership\ReferAFriend\Reward\Config\ConfigInterface;
use Message\Cog\DB\Transaction;
use Message\Cog\DB\TransactionalInterface;

/**
 * Class Create
 * @package Message\Mothership\ReferAFriend\Reward\Config\Trigger
 *
 * Class for saving triggers to the database against a reward configuration
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
	 * Save all the triggers against a specific reward configuration to the database
	 *
	 * @param ConfigInterface $config
	 */
	public function save(ConfigInterface $config)
	{
		foreach ($config->getTriggers() as $trigger) {
			$this->_addToTransaction($config, $trigger);
		}

		$this->_commitTransaction();
	}

	/**
	 * Add a query saving a trigger to the database transaction
	 *
	 * @param ConfigInterface $config
	 * @param TriggerInterface $trigger
	 */
	private function _addToTransaction(ConfigInterface $config, TriggerInterface $trigger)
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

	/**
	 * Commit the database transaction if the transaction has not been overridden
	 */
	private function _commitTransaction()
	{
		if (false === $this->_transOverride) {
			$this->_transaction->commit();
		}
	}
}