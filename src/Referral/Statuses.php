<?php

namespace Message\Mothership\ReferAFriend\Referral;

/**
 * Class Statuses
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class of constants for referral status names
 */
class Statuses
{
	const PENDING  = 'pending';
	const ERROR    = 'error';
	const EXPIRED  = 'expired';
	const COMPLETE = 'complete';

	/**
	 * Get all available status names as an array
	 *
	 * @return array
	 */
	static public function getStatuses()
	{
		return [
			self::PENDING,
			self::ERROR,
			self::EXPIRED,
			self::COMPLETE,
		];
	}
}