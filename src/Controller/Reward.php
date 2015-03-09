<?php

namespace Message\Mothership\ReferAFriend\Controller;

use Message\Cog\Controller\Controller;
use Message\Mothership\ReferAFriend\Form\TypeSelect;
use Message\Mothership\ReferAFriend\Form\RewardConfig;
use Message\Mothership\ReferAFriend\Reward\Config\Config;

class Reward extends Controller
{
	public function create()
	{
		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:create', [
			'rewardTypes' => $this->get('refer.reward.types'),
		]);
	}

	public function setOptions($type)
	{
		$form = $this->_getTypeForm($type);

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:set_options', [
			'form'   => $form,
			'action' => $this->generateUrl('ms.cp.refer_a_friend.set_options_action', ['type' => $type,])
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

	public function viewConfig($configID)
	{
		$rewardConfig = $this->get('refer.reward.config.loader')->getByID($configID);

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:view_config', [
			'rewardConfig' => $rewardConfig,
		]);
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

		$RewardOptionCreate = clone($this->get('refer.reward.config.reward_option_create'));
		$RewardOptionCreate->setTransaction($transaction);
		$RewardOptionCreate->save($config);

		$transaction->commit();
	}

	private function _getTypeForm($type)
	{
		$currentConfig = $this->get('refer.reward.config.current');

		$data = [
			'name'           => $currentConfig->getName(),
			'message'        => $currentConfig->getMessage(),
			'constraints'    => [],
			'reward_options' => [],
		];

		foreach ($currentConfig->getConstraints() as $constraint) {
			$data['constraints'][$constraint->getName()] = $constraint->getValue();
		}

		foreach ($currentConfig->getTriggers() as $trigger) {
			$data['triggers'] = $trigger->getName();
		}

		foreach ($currentConfig->getRewardOptions() as $rewardOption) {
			$data['reward_options'][$rewardOption->getName()] = $rewardOption->getValue();
		}

		return $this->createForm($this->get('refer.form.reward_config'), null, [
			RewardConfig::REWARD_TYPE => $this->get('refer.reward.types')->get($type),
			'data' => $data,
		]);
	}
}