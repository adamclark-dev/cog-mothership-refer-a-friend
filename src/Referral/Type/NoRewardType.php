<?php

namespace Message\Mothership\ReferAFriend\Referral\Type;

use Message\Mothership\ReferAFriend\Form\ReferralType\NoReward;

class NoRewardType implements TypeInterface
{
	private $_form;

	public function __construct(NoReward $form)
	{
		$this->_form = $form;
	}

	public function getName()
	{
		return 'no_reward';
	}

	public function getDisplayName()
	{
		return 'ms.refer.referral.types.no_reward.name';
	}

	public function getForm()
	{
		return $this->_form;
	}

	public function getDescription()
	{
		return 'ms.refer.referral.types.no_reward.description';
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