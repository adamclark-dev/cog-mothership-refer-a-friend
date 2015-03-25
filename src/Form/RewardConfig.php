<?php

namespace Message\Mothership\ReferAFriend\Form;

use Message\Mothership\ReferAFriend\Reward\Type\TypeInterface;
use Message\Mothership\ReferAFriend\Reward\Config\Constraint;
use Message\Mothership\ReferAFriend\Reward\Config\Trigger;
use Message\Mothership\ReferAFriend\Reward\Config\RewardOption;
use Symfony\Component\Form;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as FormConstraints;

/**
 * Class RewardConfig
 * @package Message\Mothership\ReferAFriend\Form
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class to dynamically create configuration form based on the reward type set
 */
class RewardConfig extends Form\AbstractType
{
	const NAME        = 'refer_a_friend_reward_options';
	const REWARD_TYPE = 'reward_type';
	const NONE        = 'none';

	/**
	 * @var Constraint\CollectionBuilder
	 */
	private $_constraintCollectionBuilder;

	/**
	 * @var Trigger\CollectionBuilder
	 */
	private $_triggerCollectionBuilder;

	/**
	 * @var RewardOption\CollectionBuilder
	 */
	private $_rewardOptionCollectionBuilder;

	public function __construct(
		Constraint\CollectionBuilder $constraintCollectionBuilder,
		Trigger\CollectionBuilder $triggerCollectionBuilder,
		RewardOption\CollectionBuilder $rewardOptionBuilder
	)
	{
		$this->_constraintCollectionBuilder   = $constraintCollectionBuilder;
		$this->_triggerCollectionBuilder      = $triggerCollectionBuilder;
		$this->_rewardOptionCollectionBuilder = $rewardOptionBuilder;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return self::NAME;
	}

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(Form\FormBuilderInterface $builder, array $options)
	{
		if (null === $options[self::REWARD_TYPE]) {
			throw new \LogicException('You must set the `' . self::REWARD_TYPE . '` option as an instance of TypeInterface');
		}

		if (!$options[self::REWARD_TYPE] instanceof TypeInterface) {
			$type = gettype($options[self::REWARD_TYPE]) === 'object' ? get_class($options[self::REWARD_TYPE]) : gettype($options[self::REWARD_TYPE]);
			throw new \InvalidArgumentException('`referral_type` option must be an instance of TypeInterface, ' . $type . ' given');
		}

		$builder->add(self::REWARD_TYPE, 'hidden', [
			'data' => $options[self::REWARD_TYPE]->getName(),
		]);

		$builder->add('name', 'text', [
			'label' => 'ms.refer.form.config.name.name'
		]);

		$builder->add('message', 'textarea', [
			'label' => 'ms.refer.form.config.message.name',
			'constraints' => [
				new FormConstraints\NotBlank
			],
			'attr' => [
				'data-help-key' => 'ms.refer.config.message.help'
			]
		]);

		$this->_addConstraintFields($builder, $options[self::REWARD_TYPE]);
		$this->_addTriggerFields($builder, $options[self::REWARD_TYPE]);
		$this->_addRewardOptionFields($builder, $options[self::REWARD_TYPE]);
	}

	/**
	 * Validate that reward type field exists on form
	 *
	 * {@inheritDoc}
	 */
	public function finishView(Form\FormView $view, Form\FormInterface $form, array $options)
	{
		if (!$view->offsetExists(self::REWARD_TYPE)) {
			throw new \LogicException('No `' . self::REWARD_TYPE . '` field set on form! Be sure to call parent::buildForm() in form class!');
		}
	}

	/**
	 * Allow reward_type option, and set the ID of the form to match the name, so that the Save button can be tied to the
	 * form from an external view
	 *
	 * {@inheritDoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults([
			self::REWARD_TYPE => null,
			'attr' => [
				'id' => self::NAME,
			],
		]);
	}

	/**
	 * Loop through reward constraints and create fields for the form
	 *
	 * @param Form\FormBuilderInterface $builder
	 * @param TypeInterface $rewardType
	 */
	private function _addConstraintFields(Form\FormBuilderInterface $builder, TypeInterface $rewardType)
	{
		if (count($rewardType->validConstraints()) <= 0) {
			return;
		}

		$constraintsForm = $builder->create('constraints', null, [
			'label'    => 'ms.refer.form.constraints.label',
			'compound' => true,
		]);

		$constraints = $this->_constraintCollectionBuilder
			->getCollectionFromType($rewardType)
		;

		foreach ($constraints as $constraint) {
			$options = [
				'label' => $constraint->getDisplayName(),
				'attr'  => [
					'data-help-key' => $constraint->getDescription(),
				]
			];
			$options = $constraint->getFormOptions() + $options;
			$constraintsForm->add($constraint->getName(), $constraint->getFormType(), $options);
		};

		$builder->add($constraintsForm);
	}

	/**
	 * If one or zero triggers are applicable for reward config, create a hidden form field with value set to either
	 * 'none' or the name of the sole available trigger. If there are more than one possible triggers, create a radio
	 * choice field with an option for each trigger.
	 *
	 * @param Form\FormBuilderInterface $builder
	 * @param TypeInterface $rewardType
	 */
	private function _addTriggerFields(Form\FormBuilderInterface $builder, TypeInterface $rewardType)
	{
		$triggers = $this->_triggerCollectionBuilder
			->getCollectionFromType($rewardType);

		switch ($triggers->count()) {
			case 0:
				$builder->add('triggers', 'hidden', [
					'data' => self::NONE
				]);
				break;
			case 1:
				$builder->add('triggers', 'hidden', [
					'data' => key($triggers->all()),
				]);
				break;
			default:
				$choices = [];

				foreach ($triggers as $trigger) {
					$choices[$trigger->getName()] = $trigger->getDisplayName();
				}

				$builder->add('triggers', 'choice', [
					'label'    => 'ms.refer.form.triggers.label',
					'multiple' => false,
					'expanded' => true,
					'choices'  => $choices,
					'constraints' => [
						new FormConstraints\NotBlank,
					],
				]);
		}
	}

	/**
	 * Loop through reward options and create fields for the form
	 *
	 * @param Form\FormBuilderInterface $builder
	 * @param TypeInterface $rewardType
	 */
	private function _addRewardOptionFields(Form\FormBuilderInterface $builder, TypeInterface $rewardType)
	{
		if (count($rewardType->validRewardOptions()) <= 0) {
			return;
		}

		$rewardOptionsForm = $builder->create('reward_options', null, [
			'label'    => 'ms.refer.form.reward_options.label',
			'compound' => true,
		]);

		$rewardOptions = $this->_rewardOptionCollectionBuilder
			->getCollectionFromType($rewardType)
		;

		foreach ($rewardOptions as $rewardOption) {
			$options = [
				'label' => $rewardOption->getDisplayName(),
				'attr'  => [
					'data-help-key' => $rewardOption->getDescription(),
				]
			];
			$options = $rewardOption->getFormOptions() + $options;
			$rewardOptionsForm->add($rewardOption->getName(), $rewardOption->getFormType(), $options);
		};

		$builder->add($rewardOptionsForm);
	}
}