<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\Transaction;
use Message\Cog\DB\TransactionalInterface;
use Message\User\UserInterface;

/**
 * Class Create
 * @package Message\Mothership\ReferAFriend\Referral
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
	 * @var \Message\User\UserInterface
	 */
	private $_currentUser;

	/**
	 * @var bool
	 */
	private $_transOverride = false;

	public function __construct(
		Transaction $trans,
		UserInterface $currentUser
	)
	{
		$this->_trans            = $trans;
		$this->_currentUser      = $currentUser;
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
	 * @param ReferralInterface $referral
	 */
	public function save(ReferralInterface $referral)
	{
		$this->_addToTransaction($referral);
		$this->_commitTransaction();
	}

	/**
	 * @param array $referrals
	 * @throws \InvalidArgumentException
	 */
	public function saveBatch(array $referrals)
	{
		foreach ($referrals as $referral) {
			$this->_addToTransaction($referral);
		}

		$this->_commitTransaction();
	}

	/**
	 * @param ReferralInterface $referral
	 */
	private function _addToTransaction(ReferralInterface $referral)
	{
		$this->_trans->add('
			INSERT INTO
				refer_a_friend_referral
				(
					reward_config_id,
					type,
					status,
					referrer_id,
					referred_email,
					created_at,
					created_by,
					updated_at,
					updated_by
				)
			VALUES
				(
					:rewardConfigID?i,
					:type?s,
					:status?s,
					:referrerID?i,
					:referredEmail?s,
					:createdAt?d
					:createdBy?in,
					:createdAt?d,
					:createdBy?in
				)
		', [
			'rewardConfigID' => $referral->getRewardConfig()->getID(),
			'type'           => $referral->getType()->getName(),
			'status'         => $referral->getStatus(),
			'referrerID'     => $referral->getReferrer()->id,
			'referredEmail'  => $referral->getReferredEmail(),
			'createdAt'      => new \DateTime(),
			'createdBy'      => $this->_currentUser->id,
		]);
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