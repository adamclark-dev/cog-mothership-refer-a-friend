<?php

namespace Message\Mothership\ReferAFriend\Controller;

use Message\Cog\Controller\Controller;
use Message\User\AnonymousUser;
use Message\Mothership\ReferAFriend\Referral\Event;
use Message\Mothership\ReferAFriend\Referral\Exception\EmailException;

class Referral extends Controller
{
	public function referAFriend()
	{
		if (!$this->get('user.current') instanceof AnonymousUser) {
			$form = $this->createForm($this->get('refer.form.refer_a_friend'), null, [
					'action' => $this->generateUrl('ms.refer_a_friend.refer_action'),
			]);

			return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:front_end:refer_a_friend', [
				'form' => $form
			]);
		}

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:front_end:logged_out');
	}

	public function referAFriendAction()
	{
		if ($this->get('user.current') instanceof AnonymousUser) {
			throw new \LogicException('User must be logged in to refer a friend!');
		}

		$form = $this->createForm($this->get('refer.form.refer_a_friend'));
		$form->handleRequest();

		if ($form->isValid()) {
			$data = $form->getData();

			$referral = $this->get('refer.referral_builder')->build($data);

			$validator = $this->get('refer.validator_factory')->getValidator();

			if (false === $validator->isValid($referral)) {
				$this->addFlash('error', $this->trans($validator->getMessage()));

				return $this->redirectToReferer();
			}

			$referral = $this->get('refer.referral.create')->save($referral);

			$this->addFlash('success', $this->trans('ms.refer.referral.create.success'));

			$event = new Event\EmailReferralEvent;
			$event->setReferral($referral);

			$event->setUrl($this->get('request')->headers->get('referer'));

			try {
				$this->get('event.dispatcher')->dispatch(
					Event\Events::EMAIL_REFERRED,
					$event
				);
			} catch (EmailException $e) {
				$this->addFlash('error', $e->getMessage());
			}
		}

		return $this->redirectToReferer();
	}
}