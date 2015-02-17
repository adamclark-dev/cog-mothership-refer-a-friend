<?php

namespace Message\Mothership\ReferAFriend\Referral\Type;

class NoReferralType implements TypeInterface
{
	public function getName()
	{
		return 'no-type';
	}

	public function getDisplayName()
	{
		return 'ms.refer.referral.types.no_type.name';
	}

	public function getDescription()
	{
		return 'ms.refer.referral.types.no_type.description';
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