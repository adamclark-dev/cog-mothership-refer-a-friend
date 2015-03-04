<?php

namespace Message\Mothership\ReferAFriend\Reward\Type;

class NoRewardType implements TypeInterface
{
	public function getName()
	{
		return 'no_reward';
	}

	public function getDisplayName()
	{
		return 'ms.refer.reward.types.no_reward.name';
	}

	public function getDescription()
	{
		return 'ms.refer.reward.types.no_reward.description';
	}

	public function validConstraints()
	{
		return [];
	}

	public function validTriggers()
	{
		return [];
	}
}