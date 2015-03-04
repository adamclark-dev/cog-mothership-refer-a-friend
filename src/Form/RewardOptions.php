<?php

namespace Message\Mothership\ReferAFriend\Form;

use Message\Mothership\ReferAFriend\Reward\Type\TypeInterface;
use Message\Mothership\ReferAFriend\Reward\Config\Constraint;
use Message\Mothership\ReferAFriend\Reward\Config\Trigger;
use Symfony\Component\Form;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

class RewardOptions extends Form\AbstractType
{
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

	public function __construct(
		Constraint\CollectionBuilder $constraintCollectionBuilder,
		Trigger\CollectionBuilder $triggerCollectionBuilder
	)
	{
		$this->_constraintCollectionBuilder = $constraintCollectionBuilder;
		$this->_triggerCollectionBuilder    = $triggerCollectionBuilder;
	}

	public function getName()
	{
		return 'refer_a_friend_reward_options';
	}

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

		$this->_addConstraintFields($builder, $options[self::REWARD_TYPE]);
		$this->_addTriggerFields($builder, $options[self::REWARD_TYPE]);
	}

	public function finishView(Form\FormView $view, Form\FormInterface $form, array $options)
	{
		if (!$view->offsetExists(self::REWARD_TYPE)) {
			throw new \LogicException('No `' . self::REWARD_TYPE . '` field set on form! Be sure to call parent::buildForm() in form class!');
		}
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults([
			self::REWARD_TYPE => null
		]);
	}

	private function _addConstraintFields(Form\FormBuilderInterface $builder, TypeInterface $rewardType)
	{
		$constraintsForm = $builder->create('constraints', null, [
			'label'    => 'ms.refer.form.constraints.label',
			'compound' => true,
		]);

		$constraints = $this->_constraintCollectionBuilder
			->getCollectionFromType($rewardType)
		;

		foreach ($constraints as $constraint) {
			$options = ['label' => $constraint->getDisplayName()];
			$options = $constraint->getFormOptions() + $options;
			$constraintsForm->add($constraint->getName(), $constraint->getFormType(), $options);
		};

		$builder->add($constraintsForm);
	}

	private function _addTriggerFields(Form\FormBuilderInterface $builder, TypeInterface $referralType)
	{
		$triggers = $this->_triggerCollectionBuilder
			->getCollectionFromType($referralType);

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
						new Constraints\NotBlank,
					]
				]);
		}
	}
}