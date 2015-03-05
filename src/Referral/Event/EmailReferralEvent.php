<?php

namespace Message\Mothership\ReferAFriend\Referral\Event;

class EmailReferralEvent extends ReferralEvent
{
	private $_url;

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

	public function getUrl()
	{
		return $this->_url;
	}
}