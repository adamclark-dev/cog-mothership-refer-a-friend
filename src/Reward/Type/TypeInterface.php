<?php

namespace Message\Mothership\ReferAFriend\Reward\Type;

/**
 * Interface TypeInterface
 * @package Message\Mothership\ReferAFriend\Referral\Type
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * An interface representing a type of reward. There are many different ways to reward someone for coming to your site,
 * and many different ways to determine that a referral has been successful. A reward type determines what rules and
 * rewards can be set against a configuration.
 */
interface TypeInterface
{
	/**
	 * @return string
	 */
	public function getName();

	/**
	 * Return a translation string for the display name
	 *
	 * @return string
	 */
	public function getDisplayName();

	/**
	 * Return a translation string for the description
	 *
	 * @return string
	 */
	public function getDescription();

	/**
	 * Toggle which constraints can be set against this type of reward
	 *
	 * @return array       Array of constraint names
	 */
	public function validConstraints();

	/**
	 * Toggle which triggers can be set against this type of reward
	 *
	 * @return array       Array of trigger names
	 */
	public function validTriggers();

	/**
	 * Toggle which reward options can be set against this type of reward
	 *
	 * @return array       Array of option names
	 */
	public function validRewardOptions();
}