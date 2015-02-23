<?php

namespace Message\Mothership\ReferAFriend\Controller;

use Message\Cog\Controller\Controller;
use Message\Mothership\ReferAFriend\Form\TypeSelect;

use Symfony\Component\HttpKernel\Exception\HttpException;

class Create extends Controller
{
	public function create()
	{
		$form = $this->createForm($this->get('refer.form.type_select'));

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:create', [
			'form'          => $form,
			'referralTypes' => $this->get('refer.referral.types'),
		]);
	}

	public function createAction()
	{
		$form = $this->createForm($this->get('refer.form.type_select'));

		$form->handleRequest();

		if ($form->isValid()) {
			$data = $form->getData();
			$type = $data[TypeSelect::FIELD_NAME];

			return $this->redirectToRoute('ms.cp.refer_a_friend.set_options', [
				'type' => $type
			]);
		}

		return $this->redirectToReferer();
	}

	public function setOptions($type)
	{
		if (!$this->get('refer.referral.types')->exists($type)) {
			throw new HttpException(404, 'No referral with type `' . $type . '` found!');
		}

		$form = $this->createForm($this->get('refer.referral.types')->get($type)->getForm());

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:set_options', [
			'form' => $form,
		]);
	}

	public function setOptionsAction($type)
	{
		$referralType = $this->get('refer.referral.types')->get($type);

		$form = $this->createForm($referralType->getForm());

		$form->handleRequest();

		if ($form->isValid()) {
			$data = $form->getData();
			$rewardConfig = $this->get('refer.reward.config.builders')->get($type)->build($data);
			$rewardConfig->setReferralType($referralType);

			// Current version only supports one configuration at at time, so all will be 'deleted'
			$this->get('refer.reward.config.delete')->deleteAll();
			$this->get('refer.reward.config.create')->save($rewardConfig);
			$this->addFlash('success', $this->get('translator')->trans('ms.refer.config.success'));
		}

		return $this->redirectToRoute('ms.cp.refer_a_friend.dashboard');
	}
}