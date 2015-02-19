<?php

namespace Message\Mothership\ReferAFriend\Referral\Type;

use Symfony\Component\Form\AbstractType as Form;

/**
 * Interface TypeInterface
 * @package Message\Mothership\ReferAFriend\Referral\Type
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
	 * @return Form
	 */
	public function getForm();

	/**
	 * Toggle whether constraints can be set against a referral
	 *
	 * @return bool
	 */
	public function allowConstraints();

	/**
	 * Toggle whether triggers can be set against a referral
	 *
	 * @return bool
	 */
	public function allowTriggers();
}