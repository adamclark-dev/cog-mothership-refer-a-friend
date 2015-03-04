<?php

namespace Message\Mothership\ReferAFriend\Referral\Event;

class EmailReferralEvent extends ReferralEvent
{
	private $_message;
	private $_url;

	public function setMessage($message)
	{
		if (!is_string($message)) {
			throw new \InvalidArgumentException('Message must be a string!');
		}

		$this->_message = $message;
	}

	public function getMessage()
	{
		return $this->_message;
	}

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