<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\RewardOption;

use Message\Mothership\ReferAFriend\Reward\Config\ConfigInterface;

use Message\Cog\DB\Transaction;
use Message\Cog\DB\TransactionalInterface;

/**
 * Class Create
 * @package Message\Mothership\ReferAFriend\Reward\Config\RewardOption
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for saving reward options to the database as part of the reward configuration. Reward options are saved as a
 * basic key/value pair.
 * Implements TransactionalInterface so that many reward options can be saved at once.
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
	 * Loop through the reward options on a reward configuration and add them to the database transaction
	 *
	 * @param ConfigInterface $config
	 */
	public function save(ConfigInterface $config)
	{
		foreach ($config->getRewardOptions() as $rewardOption) {
			$this->_addToTransaction($config, $rewardOption);
		}

		$this->_commitTransaction();
	}

	/**
	 * Add a query for saving the reward option to the database transaction
	 *
	 * @param ConfigInterface $config
	 * @param RewardOptionInterface $rewardOption
	 */
	private function _addToTransaction(ConfigInterface $config, RewardOptionInterface $rewardOption)
	{
		$this->_transaction->add("
			INSERT INTO
				refer_a_friend_reward_option
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
			'name'           => $rewardOption->getName(),
			'value'          => $rewardOption->getValue(),
		]);
	}

	/**
	 * Commit the database transaction if it has not been overridden
	 */
	private function _commitTransaction()
	{
		if (false === $this->_transOverride) {
			$this->_transaction->commit();
		}
	}
}