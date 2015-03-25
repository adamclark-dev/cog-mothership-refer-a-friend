<?php

namespace Message\Mothership\ReferAFriend\Referral\Event;

/**
 * Class EmailReferralEvent
 * @package Message\Mothership\ReferAFriend\Referral\Event
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Event that is fired when an email address is referred to the site. Used for triggering an email to be sent to that
 * email address.
 */
class EmailReferralEvent extends ReferralEvent
{
	/**
	 * @var string
	 */
	private $_url;

	/**
	 * Set the URL to link the referred user to the site
	 *
	 * @param $url
	 */
	public function setUrl($url)
	{
		if (!is_string($url)) {
			throw new \InvalidArgumentException('URL must be a string!');
		}
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			throw new \LogicException('`' . $url . '` is not a valid URL');
		}

		$this->_url = $url;
	}

	/**
	 * Get the URL to link the referred user to the site
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->_url;
	}
}