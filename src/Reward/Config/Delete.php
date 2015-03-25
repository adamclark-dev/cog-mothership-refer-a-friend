<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Cog\DB;
use Message\User\UserInterface;

/**
 * Class Delete
 * @package Message\Mothership\ReferAFriend\Reward\Config
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for deleting configurations. When a configuration is deleted, it is still accessible, but no longer active.
 * This means that users that have referred someone under one configuration can still have their rewards honoured.
 *
 * Implements TransactionalInterface so that many configurations can be deleted at once.
 */
class Delete implements DB\TransactionalInterface
{
	/**
	 * @var DB\Transaction
	 */
	private $_transaction;

	/**
	 * @var bool
	 */
	private $_transOverride = false;

	/**
	 * @var UserInterface
	 */
	private $_currentUser;

	public function __construct(DB\Transaction $transaction, UserInterface $currentUser)
	{
		$this->_transaction = $transaction;
		$this->_currentUser = $currentUser;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setTransaction(DB\Transaction $trans)
	{
		$this->_transaction   = $trans;
		$this->_transOverride = true;
	}

	/**
	 * Delete a single configuration
	 *
	 * @param Config $config
	 */
	public function delete(Config $config)
	{
		$this->_addToTransaction($config);
		$this->_commitTransaction();
	}

	/**
	 * Delete multiple configurations
	 *
	 * @param array $configs
	 */
	public function deleteBatch(array $configs)
	{
		foreach ($configs as $config) {
			$this->_addToTransaction($config);
		}

		$this->_commitTransaction();
	}

	/**
	 * Delete all un-deleted configurations
	 */
	public function deleteAll()
	{
		$this->_transaction->add("
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

	/**
	 * Add the query to delete a configuration to the database transaction
	 *
	 * @param Config $config
	 */
	private function _addToTransaction(Config $config)
	{
		$this->_transaction->add("
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