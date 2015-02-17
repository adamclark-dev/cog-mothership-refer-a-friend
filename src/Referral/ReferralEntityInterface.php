<?php

namespace Message\Mothership\ReferAFriend\Referral;

/**
 * Interface ReferralEntityInterface
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
interface ReferralEntityInterface
{
	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string
	 */
	public function getDisplayName();

	/**
	 * @return string
	 */
	public function getDescription();

	/**
	 * @return array
	 */
	public function getTypes();
}