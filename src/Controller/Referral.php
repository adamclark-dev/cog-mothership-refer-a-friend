<?php

namespace Message\Mothership\ReferAFriend\Controller;

use Message\Cog\Controller\Controller;

class Referral extends Controller
{
	public function referAFriend()
	{
		$form = $this->createForm($this->get('refer.form.refer_a_friend'));

		return $this->render('::refer_a_friend:front_end', [
			'form' => $form
		]);
	}

	public function referAFriendAction()
	{
		$form = $this->createForm($this->get('refer.form.refer_a_friend'));
		$form->handleRequest();

		if ($form->isValid()) {
			$data = $form->getData();


		}

		return $this->redirectToReferer();
	}
}