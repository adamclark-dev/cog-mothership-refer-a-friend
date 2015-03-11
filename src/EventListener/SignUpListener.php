<?php

namespace Message\Mothership\ReferAFriend\EventListener;

use Message\Mothership\ReferAFriend\Referral;
use Message\Cog\Event\EventListener;
use Message\Cog\Event\SubscriberInterface;
use Message\User\Event\Event as UserEvent;

/**
 * Class SignUpListener
 * @package Message\Mothership\ReferAFriend\EventListener
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Event listener for checking if a user logged in had been referred
 */
class SignUpListener extends EventListener implements SubscriberInterface
{
	/**
	 * {@inheritDoc}
	 */
	static public function getSubscribedEvents()
	{
		return [
			UserEvent::LOGIN => [
				'setStatusToComplete'
			]
		];
	}

	/**
	 * Set status to complete if a referred user has signed up and logged in. Unfortunately, this needs to listen out
	 * for logins, as the create event is not dispatched, despite existing. However, even if it does, the user is saved
	 * to the database via a database transaction, which means that the referral `updated_by` column cannot be saved
	 *
	 * @param UserEvent $event
	 */
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