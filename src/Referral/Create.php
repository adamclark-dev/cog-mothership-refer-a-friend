<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\Query;
use Message\User\UserInterface;

/**
 * Class Create
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
class Create
{
	/**
	 * @var \Message\Cog\DB\Query
	 */
	private $_query;

	/**
	 * @var \Message\User\UserInterface
	 */
	private $_currentUser;


	public function __construct(
		Query $query,
		UserInterface $currentUser
	)
	{
		$this->_query       = $query;
		$this->_currentUser = $currentUser;
	}

	/**
	 * @param ReferralInterface $referral
	 */
	public function save(ReferralInterface $referral)
	{
		$result = $this->_query->run('
			INSERT INTO
				refer_a_friend_referral
				(
					reward_config_id,
					status,
					referrer_id,
					referred_email,
					referred_name,
					created_at,
					created_by,
					updated_at,
					updated_by
				)
			VALUES
				(
					:rewardConfigID?i,
					:status?s,
					:referrerID?i,
					:referredEmail?s,
					:referredName?s,
					:createdAt?d,
					:createdBy?in,
					:createdAt?d,
					:createdBy?in
				)
		', [
			'rewardConfigID' => $referral->getRewardConfig()->getID(),
			'status'         => $referral->getStatus(),
			'referrerID'     => $referral->getReferrer()->id,
			'referredEmail'  => $referral->getReferredEmail(),
			'referredName'   => $referral->getReferredName(),
			'createdAt'      => $referral->getCreatedAt() ?: new \DateTime(),
			'createdBy'      => $this->_currentUser->id,
		]);

		$referral->setID($result->id());

		return $referral;
	}
}