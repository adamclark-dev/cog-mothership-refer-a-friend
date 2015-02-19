<?php

namespace Message\Mothership\ReferAFriend\Form\ReferralType;
use Symfony\Component\Form;

class NoReward extends AbstractForm
{
	const NAME = 'refer_a_friend_no_reward';

	public function getName()
	{
		return self::NAME;
	}

	protected function _getType()
	{
		return 'no_reward';
	}
}