<?php

namespace Message\Mothership\ReferAFriend\Reward;

class Statuses
{
	const PENDING = 'pending';
	const ACTIVE  = 'active';
	const EXPIRED = 'expired';
	const USED    = 'used';

	static public function getStatuses()
	{
		return [
			self::PENDING,
			self::ACTIVE,
			self::EXPIRED,
			self::USED,
		];
	}
}