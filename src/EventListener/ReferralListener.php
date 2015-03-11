<?php

namespace Message\Mothership\ReferAFriend\EventListener;

use Message\Cog\Event\EventListener;
use Message\Cog\Event\SubscriberInterface;
use Message\Mothership\ReferAFriend\Referral\Event;
use Message\Mothership\ReferAFriend\Referral\Exception\EmailException;

/**
 * Class ReferralListener
 * @package Message\Mothership\ReferAFriend\EventListener
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Event listener for handling the creation of referrals
 */
class ReferralListener extends EventListener implements SubscriberInterface
{
	/**
	 * {@inheritDoc}
	 */
	static public function getSubscribedEvents()
	{
		return [
			Event\Events::EMAIL_REFERRED => [
				'sendReferralEmail'
			],
		];
	}

	/**
	 * Send email to referred email address upon submission of Refer a Friend form
	 *
	 * @param Event\EmailReferralEvent $event
	 */
	public function sendReferralEmail(Event\EmailReferralEvent $event)
	{
		$referral = $event->getReferral();

		$message = $this->get('mail.message');
		$message->setTo($referral->getReferredEmail(), $referral->getReferredName());

		$message->setSubject($this->get('translator')->trans('ms.refer.email.subject', [
			'{%site%}' => $this->get('cfg')->app->name,
		]));

		$message->setView('Message:Mothership:ReferAFriend::refer_a_friend:email:referral_message', [
			'toName'   => $referral->getReferredName(),
			'message'  => $referral->getRewardConfig()->getMessage(),
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