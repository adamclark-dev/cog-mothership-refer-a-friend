<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Constraint\Constraints;

use Message\Mothership\ReferAFriend\Referral\ReferralInterface;
use Message\Mothership\ReferAFriend\Reward\Config\Constraint\ConstraintInterface;

use Message\Cog\Event\Event;

use Symfony\Component\Validator\Constraints;

class Timeout implements ConstraintInterface
{
	private $_value;

	public function getName()
	{
		return 'refer_a_friend_timeout';
	}

	public function getDisplayName()
	{
		return 'ms.refer.reward.constraints.timeout.name';
	}

	public function getDescription()
	{
		return 'ms.refer.reward.constraints.timeout.description';
	}

	public function setValue($value)
	{
		if (!is_numeric($value) || (int) $value != $value) {
			throw new \LogicException('Value must be a whole number');
		}

		$this->_value = (int) $value;
	}

	public function getValue()
	{
		return $this->_value;
	}

	public function getFormType()
	{
		return 'text';
	}

	public function getFormOptions()
	{
		return [
			'constraints' => [
				new Constraints\GreaterThan(['value' => 0]),
			]
		];
	}

	public function isValid(ReferralInterface $referral, Event $event)
	{

	}
}