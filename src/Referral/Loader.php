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
		'type',
		'status',
		'referrer_id AS referrerID',
		'referred_email AS referredEmail'
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

	public function getByType($type)
	{
		$result = $this->_getSelect()
			->where('type = :type?s', ['type' => $type])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByStatus($status)
	{
		$result = $this->_getSelect()
			->where('status = :status?s', ['status' => $status])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByUser(UserInterface $user)
	{
		$result = $this->_getSelect()
			->where('referrer_id = :referrerID', ['referrerID' => $user->id])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	public function getByEmail($email)
	{
		$result = $this->_getSelect()
			->where('referred_email = :email?s', ['email' => $email])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	private function _getSelect()
	{
		return $this->_qbFactory
			->getQueryBuilder()
			->select($this->_columns)
			->from('refer_a_friend_referral')
			->where('deleted_at IS NULL')
		;
	}

	private function _bind(Result $result)
	{
		$referrals = [];

		foreach ($result as $row) {
			$referral = $this->_referralFactory->getReferralProxy($row->type);
			$referral->setStatus($row->status);
			$referral->setReferrerID($row->referrerID);
			$referral->setReferredEmail($row->referredEmail);
			$referral->setLoaders($this->_entityLoaders);

			$referrals[] = $referral;
		}

		return $referrals;
	}
}