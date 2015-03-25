<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Constraint;

use Message\Mothership\ReferAFriend\Reward\RewardEntityInterface;
use Message\Mothership\ReferAFriend\Referral\ReferralInterface;

use Message\Cog\Event\Event;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Interface ConstraintInterface
 * @package Message\Mothership\ReferAFriend\Reward\Config\Constraint
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Interface representing a constraint against a reward configuration.
 *
 * A constraint is a specific rule which determines whether a referral is allowed to be triggered, usually depending on
 * what the referred user has done as a result of being referred.
 */
interface ConstraintInterface extends RewardEntityInterface
{
	/**
	 * Check to see if a referral fulfills the restrictions set by the constraint
	 *
	 * @param ReferralInterface $referral
	 * @param Event $event
	 */
	public function isValid(ReferralInterface $referral, Event $event);

	/**
	 * Set the value of the constraint
	 *
	 * @param $value
	 */
	public function setValue($value);

	/**
	 * Get the value of the constraint
	 *
	 * @return mixed
	 */
	public function getValue();

	/**
	 * Get the form type that will be displayed when setting up a new reward configuration
	 *
	 * @return string | FormBuilderInterface
	 */
	public function getFormType();

	/**
	 * Get the options to apply to the constraint's form field
	 *
	 * @return array
	 */
	public function getFormOptions();
}