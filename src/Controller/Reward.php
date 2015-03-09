<?php

namespace Message\Mothership\ReferAFriend\Controller;

use Message\Cog\Controller\Controller;
use Message\Mothership\ReferAFriend\Form\RewardConfig;
use Message\Mothership\ReferAFriend\Reward\Config\Config;

/**
 * Class Reward
 * @package Message\Mothership\ReferAFriend\Controller
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Controller for handling the configuration of rewards
 */
class Reward extends Controller
{
	/**
	 * Render first stage of form to create a new configuration for rewards. This creates two links that will make an
	 * AJAX request to bring up the appropriate form.
	 *
	 * @return \Message\Cog\HTTP\Response
	 */
	public function create()
	{
		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:create', [
			'rewardTypes' => $this->get('refer.reward.types'),
		]);
	}

	/**
	 * Render form for creating a new configuration for rewards.
	 *
	 * @param string $type        The name of the reward type (set by URLs in the `create()` method)
	 *
	 * @return \Message\Cog\HTTP\Response
	 */
	public function setOptions($type)
	{
		$form = $this->_getTypeForm($type);

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:set_options', [
			'form'   => $form,
			'action' => $this->generateUrl('ms.cp.refer_a_friend.set_options_action', ['type' => $type,])
		]);
	}

	/**
	 * Save the configuration for the rewards.
	 *
	 * @param string $type        The name of the reward type (set by URLs in the `create()` method)
	 *
	 * @return \Message\Cog\HTTP\RedirectResponse
	 */
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

	/**
	 * View the current configuration for rewards.
	 *
	 * @param int $configID
	 *
	 * @return \Message\Cog\HTTP\Response
	 */
	public function viewConfig($configID)
	{
		$rewardConfig = $this->get('refer.reward.config.loader')->getByID($configID);

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:view_config', [
			'rewardConfig' => $rewardConfig,
		]);
	}

	/**
	 * Save constraints, triggers and reward options against a reward configuration.
	 *
	 * @param Config $config
	 */
	private function _saveConfigEntities(Config $config)
	{
		$transaction = clone $this->get('db.transaction');

		$constraintCreate = clone $this->get('refer.reward.config.constraint_create');
		$constraintCreate->setTransaction($transaction);
		$constraintCreate->save($config);

		$triggerCreate = clone($this->get('refer.reward.config.trigger_create'));
		$triggerCreate->setTransaction($transaction);
		$triggerCreate->save($config);

		$rewardOptionCreate = clone($this->get('refer.reward.config.reward_option_create'));
		$rewardOptionCreate->setTransaction($transaction);
		$rewardOptionCreate->save($config);

		$transaction->commit();
	}

	/**
	 * Get form for creating reward configuration
	 *
	 * @param string $type
	 *
	 * @return \Symfony\Component\Form\Form
	 */
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