<?php

namespace Message\Mothership\ReferAFriend\Controller;

use Message\Cog\Controller\Controller;
use Message\Mothership\ReferAFriend\Form\TypeSelect;
use Message\Mothership\ReferAFriend\Form\RewardTypeForm;
use Message\Mothership\ReferAFriend\Reward\Config\Config;

class Create extends Controller
{
	public function create()
	{
		$form = $this->createForm($this->get('refer.form.type_select'));

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:create', [
			'form'        => $form,
			'rewardTypes' => $this->get('refer.reward.types'),
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
		$form = $this->_getTypeForm($type);

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:set_options', [
			'form' => $form,
		]);
	}

	public function setOptionsAction($type)
	{
		$form = $this->_getTypeForm($type);

		$type = $this->get('refer.reward.types')->get($type);

		$form->handleRequest();

		if ($form->isValid()) {
			$data = $form->getData();
			$config = $this->get('refer.reward.config.builder')->build($type, $data);

			// Current version only supports one configuration at at time, so all will be 'deleted'
			$this->get('refer.reward.config.delete')->deleteAll();
			$config = $this->get('refer.reward.config.create')->save($config);

			$this->_saveConfigEntities($config);

			$this->addFlash('success', $this->get('translator')->trans('ms.refer.config.success'));
		}

		return $this->redirectToRoute('ms.cp.refer_a_friend.dashboard');
	}

	private function _saveConfigEntities(Config $config)
	{
		$transaction = clone $this->get('db.transaction');

		$constraintCreate = clone $this->get('refer.reward.config.constraint_create');
		$constraintCreate->setTransaction($transaction);
		$constraintCreate->save($config);

		$triggerCreate = clone($this->get('refer.reward.config.trigger_create'));
		$triggerCreate->setTransaction($transaction);
		$triggerCreate->save($config);

		$transaction->commit();
	}

	private function _getTypeForm($type)
	{
		return $this->createForm($this->get('refer.form.reward_type_form'), null, [
			RewardTypeForm::REWARD_TYPE => $this->get('refer.reward.types')->get($type),
		]);
	}
}