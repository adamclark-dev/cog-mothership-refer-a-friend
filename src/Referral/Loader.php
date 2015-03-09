<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\QueryBuilderFactory;
use Message\Cog\DB\Entity\EntityLoaderCollection;
use Message\Cog\DB\Result;

use Message\User\UserInterface;

/**
 * Class Loader
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for loading referrals from the database
 */
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
		'referral_id AS id',
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

	/**
	 * Load all existing referrals from the database
	 *
	 * @return array
	 */
	public function getAll()
	{
		$result = $this->_getSelect()
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	/**
	 * Load a specific referral from the database by its ID
	 *
	 * @param int $id
	 *
	 * @return array
	 */
	public function getByID($id)
	{
		$result = $this->_getSelect()
			->where('referral_id = :id?i', ['id' => $id])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	/**
	 * Load all referrals with a specific status from the database
	 *
	 * @param string $status     The status to load
	 *
	 * @return array
	 */
	public function getByStatus($status)
	{
		$result = $this->_getSelect($status)
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	/**
	 * Load all referrals made by a specific user
	 *
	 * @param UserInterface $user       The referrer user instance
	 * @param null | string $status     If set, results will be filtered by this status
	 *
	 * @return array
	 */
	public function getByUser(UserInterface $user, $status = null)
	{
		$result = $this->_getSelect($status)
			->where('referrer_id = :referrerID', ['referrerID' => $user->id])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	/**
	 * Load all referrals made to this email address
	 *
	 * @param string $email             The referred email address
	 * @param null | string $status     If set, results will be filtered by this status
	 *
	 * @return array
	 */
	public function getByEmail($email, $status = null)
	{
		$result = $this->_getSelect($status)
			->where('referred_email = :email?s', ['email' => $email])
			->getQuery()
			->run()
		;

		return $this->_bind($result);
	}

	/**
	 * Get basic select statement query builder with all appropriate columns
	 *
	 * @param null | string $status              If set, a WHERE statement will be added to the query to filter by status
	 *
	 * @return \Message\Cog\DB\QueryBuilder
	 */
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

	/**
	 * Bind query results to an instance of ReferralProxy
	 *
	 * @param Result $result
	 *
	 * @return array
	 */
	private function _bind(Result $result)
	{
		$referrals = [];

		foreach ($result as $row) {
			$referral = $this->_referralFactory->getReferralProxy();
			$referral->setID($row->id);
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