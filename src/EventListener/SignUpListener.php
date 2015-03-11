<?php

namespace Message\Mothership\ReferAFriend\EventListener;

use Message\Mothership\ReferAFriend\Referral;
use Message\Cog\Event\EventListener;
use Message\Cog\Event\SubscriberInterface;
use Message\User\Event\Event as UserEvent;

class SignUpListener extends EventListener implements SubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return [
			UserEvent::LOGIN => [
				'setStatusToComplete'
			]
		];
	}

	public function setStatusToComplete(UserEvent $event)
	{
		$user = $event->getUser();

		if (!$user) {
			throw new \LogicException('No user on user create event!');
		}

		$referrals = $this->get('refer.referral.loader')->getByEmail($user->email, Referral\Statuses::PENDING);

		if (empty($referrals)) {
			return;
		}

		$transaction  = clone $this->get('db.transaction');
		$referralEdit = clone $this->get('refer.referral.edit');
		$referralEdit->setTransaction($transaction);

		foreach ($referrals as $referral) {
			if ($referral->hasTriggered(UserEvent::LOGIN) && $this->_isValid($referral)) {
				$referral->setStatus(Referral\Statuses::COMPLETE);

				$event = new Referral\Event\ReferralEvent();
				$event->setReferral($referral);
				$this->get('event.dispatcher')->dispatch(
					Referral\Event\Events::SIGN_UP,
					$event
				);

				$referralEdit->save($referral);
			}
		}

		$transaction->commit();
	}

	private function _isValid(Referral\ReferralInterface $referral)
	{
		foreach ($referral->getRewardConfig()->getConstraints() as $constraint) {
			if (false === $constraint->isValid($referral)) {
				return false;
			}
		}

		return true;
	}
}