<?php

namespace Message\Mothership\ReferAFriend\EventListener;

use Message\Cog\Event\EventListener;
use Message\Cog\Event\SubscriberInterface;
use Message\Mothership\ReferAFriend\Referral\Event;
use Message\Mothership\ReferAFriend\Referral\Exception\EmailException;

class ReferralListener extends EventListener implements SubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return [
			Event\Events::EMAIL_REFERRED => [
				'sendReferralEmail'
			],
		];
	}

	public function sendReferralEmail(Event\EmailReferralEvent $event)
	{
		$referral = $event->getReferral();

		$messageText = $event->getMessage() ?: $this->get('translator')->trans('ms.refer.referral.default_email');

		$message = $this->get('mail.message');
		$message->setTo($referral->getReferredEmail(), $referral->getReferredName());

		$message->setView('Message:Mothership:ReferAFriend::refer_a_friend:email:referral_message', [
			'toName'   => $referral->getReferredName(),
			'message'  => $messageText,
			'url'      => $event->getUrl(),
			'referrer' => $referral->getReferrer(),
		]);

		$dispatcher = $this->get('mail.dispatcher');
		$failedRecipients = [];
		$dispatcher->send($message, $failedRecipients);

		if (count($failedRecipients) > 0) {
			throw new EmailException($this->get('translator')->trans('ms.refer.referral.create.email_not_sent'));
		}
	}
}