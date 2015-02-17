<?php

namespace Message\Mothership\ReferAFriend\Reward\Type;

class NoRewardType implements TypeInterface
{
	public function getName()
	{
		return 'no-type';
	}

	public function getDisplayName()
	{
		return 'ms.refer.reward.types.no_type.name';
	}

	public function getDescription()
	{
		return 'ms.refer.reward.types.no_type.description';
	}

	public function allowConstraints()
	{
		return false;
	}

	public function allowTriggers()
	{
		return false;
	}
}