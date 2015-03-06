<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\Transaction;
use Message\Cog\DB\TransactionalInterface;

use Message\User\UserInterface;

class Edit implements TransactionalInterface
{
	/**
	 * @var \Message\Cog\DB\Transaction
	 */
	private $_trans;

	/**
	 * @var bool
	 */
	private $_transOverride = false;

	/**
	 * @var \Message\User\UserInterface
	 */
	private $_currentUser;

	public function __construct(Transaction $trans, UserInterface $currentUser)
	{
		$this->_trans       = $trans;
		$this->_currentUser = $currentUser;
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
			UPDATE
				refer_a_friend_referral
			SET
				status         = :status?s,
				referrer_id    = :referrerID?i,
				referred_email = :referredEmail?s,
				updated_at     = :updatedAt?d,
				updated_by     = :updatedBy?in
			WHERE
				referral_id = :id?i
		', [
			'status'         => $referral->getStatus(),
			'referrerID'     => $referral->getReferrer()->id,
			'referredEmail'  => $referral->getReferredEmail(),
			'updatedAt'      => new \DateTime,
			'updatedBy'      => $this->_currentUser->id,
			'id'             => $referral->getID(),
		]);
	}

	/**
	 * Commit the transaction if not overridden
	 */
	private function _commitTransaction()
	{
		if (false === $this->_transOverride) {
			$this->_trans->commit();
		}
	}
}