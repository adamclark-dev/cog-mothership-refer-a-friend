<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\QueryBuilderFactory;
use Message\Cog\DB\Entity\EntityLoaderCollection;
use Message\Cog\DB\Result;

use Message\User\UserInterface;

class Loader
{
	/**
	 * @var \Message\Cog\DB\QueryBuilderFactory
	 */
	private $_qbFactory;

	/**
	 * @var ReferralFactory
	 */
	private $_referralFactory;

	/**
	 * @var EntityLoaderCollection
	 */
	private $_entityLoaders;

	/**
	 * @var array
	 */
	private $_columns = [
		'status',
		'referrer_id AS referrerID',
		'reward_config_id AS rewardConfigID',
		'referred_email AS referredEmail',
		'referred_name AS referredName',
		'created_at AS createdAt'
	];

	final public function __construct(
		QueryBuilderFactory $qbFactory,
		ReferralFactory $referralFactory,
		EntityLoaderCollection $loaders
	)
	{
		$this->_qbFactory       = $qbFactory;
		$this->_referralFactory = $referralFactory;
		$this->_entityLoaders   = $loaders;
	}

	public function getAll()
	{
		$result = $this->_getSelect()
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByID($id)
	{
		$result = $this->_getSelect()
			->where('referral_id = :id?i', ['id' => $id])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByType($type, $status = null)
	{
		$result = $this->_getSelect($status)
			->where('type = :type?s', ['type' => $type])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByStatus($status)
	{
		$result = $this->_getSelect($status)
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByUser(UserInterface $user, $status = null)
	{
		$result = $this->_getSelect($status)
			->where('referrer_id = :referrerID', ['referrerID' => $user->id])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByEmail($email, $status = null)
	{
		$result = $this->_getSelect($status)
			->where('referred_email = :email?s', ['email' => $email])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	private function _getSelect($status = null)
	{
		$select = $this->_qbFactory
			->getQueryBuilder()
			->select($this->_columns)
			->from('refer_a_friend_referral')
			->where('deleted_at IS NULL')
		;

		if (null !== $status) {
			$select->where('status = :status?s', ['status' => $status]);
		}

		return $select;
	}

	private function _bind(Result $result)
	{
		$referrals = [];

		foreach ($result as $row) {
			$referral = $this->_referralFactory->getReferralProxy();
			$referral->setStatus($row->status);
			$referral->setRewardConfigID($row->rewardConfigID);
			$referral->setReferrerID($row->referrerID);
			$referral->setReferredEmail($row->referredEmail);
			$referral->setReferredName($row->referredName);
			$referral->setLoaders($this->_entityLoaders);

			$createdAt = new \DateTime;
			$createdAt->setTimestamp($row->createdAt);
			$referral->setCreatedAt($createdAt);

			$referrals[] = $referral;
		}

		return $referrals;
	}
}