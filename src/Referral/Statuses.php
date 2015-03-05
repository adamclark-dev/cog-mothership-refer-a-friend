<?php

namespace Message\Mothership\ReferAFriend\Referral;

class Statuses
{
	const PENDING  = 'pending';
	const ERROR    = 'error';
	const EXPIRED  = 'expired';
	const COMPLETE = 'complete';

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