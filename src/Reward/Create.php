<?php

namespace Message\Mothership\ReferAFriend\Reward;

use Message\Cog\DB\Transaction;
use Message\Cog\DB\TransactionalInterface;

/**
 * Class Create
 * @package Message\Mothership\ReferAFriend\Reward
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
class Create implements TransactionalInterface
{
	/**
	 * @var \Message\Cog\DB\Transaction
	 */
	private $_trans;

	/**
	 * @var Constraint\Create
	 */
	private $_constraintCreate;

	/**
	 * @var Trigger\Create
	 */
	private $_triggerCreate;

	/**
	 * @var bool
	 */
	private $_transOverride = false;

	public function __construct(Transaction $trans, Constraint\Create $constraintCreate, Trigger\Create $triggerCreate)
	{
		$this->_trans            = $trans;
		$this->_constraintCreate = $constraintCreate;
		$this->_triggerCreate    = $triggerCreate;
	}

	/**
	 * @param Transaction $trans
	 */
	public function setTransaction(Transaction $trans)
	{
		$this->_trans = $trans;
		$this->_transOverride = true;
	}

	/**
	 * @param RewardInterface $reward
	 */
	public function save(RewardInterface $reward)
	{
		$this->_addToTransaction($reward);
		$this->_commitTransaction();
	}

	/**
	 * @param array $rewards
	 * @throws \InvalidArgumentException
	 */
	public function saveBatch(array $rewards)
	{
		foreach ($rewards as $reward) {
			if (!$reward instanceof RewardInterface) {
				throw new \InvalidArgumentException('All rewards in array must implement RewardInterface');
			}

			$this->_addToTransaction($reward);
		}

		$this->_commitTransaction();
	}

	/**
	 * @param RewardInterface $reward
	 */
	private function _addToTransaction(RewardInterface $reward)
	{
		$this->_trans->add("
			INSERT INTO
				refer_a_friend_reward
				(
					type,
					status,
					referrer_id,
					referred_email,
					created_at,
					created_by,
					updated_at,
					updated_by,
					deleted_at,
					deleted_by
				)
			VALUES
				(
					:type?s,
					:status?s,
					:referrerID?i,
					:referredEmail?s,
					:createdAt?d
					:createdBy?in,
					:createdAt?d,
					:createdBy?in
				)
		");
	}

	/**
	 * Commit the transaction if $_transOverride is false
	 */
	private function _commitTransaction()
	{
		if (false === $this->_transOverride) {
			$this->_trans->commit();
		}
	}
}