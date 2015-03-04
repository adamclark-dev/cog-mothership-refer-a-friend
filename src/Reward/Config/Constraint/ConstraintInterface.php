<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Constraint;

use Message\Mothership\ReferAFriend\Referral\ReferralEntityInterface;
use Message\Mothership\ReferAFriend\Referral\ReferralInterface;

use Message\Cog\Event\Event;

use Symfony\Component\Form\FormBuilderInterface;

interface ConstraintInterface extends ReferralEntityInterface
{
	/**
	 * @param ReferralInterface $referral
	 * @param Event $event
	 */
	public function isValid(ReferralInterface $referral, Event $event);

	/**
	 * @param $value
	 */
	public function setValue($value);

	/**
	 * @return mixed
	 */
	public function getValue();

	/**
	 * @return string | FormBuilderInterface
	 */
	public function getFormType();

	/**
	 * @return array
	 */
	public function getFormOptions();
}