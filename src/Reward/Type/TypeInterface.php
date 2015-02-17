<?php

namespace Message\Mothership\ReferAFriend\Reward\Type;

/**
 * Interface TypeInterface
 * @package Message\Mothership\ReferAFriend\Reward\Type
 *
 * @author Thomas Marchant <thomas@message.co.uk>
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
	 * Toggle whether constraints can be set against a reward
	 *
	 * @return bool
	 */
	public function allowConstraints();

	/**
	 * Toggle whether triggers can be set against a reward
	 *
	 * @return bool
	 */
	public function allowTriggers();
}