<?php

namespace Message\Mothership\ReferAFriend\Form;

use Message\Mothership\ReferAFriend\Referral\Type\TypeInterface;
use Message\Mothership\ReferAFriend\Referral\Constraint;
use Message\Mothership\ReferAFriend\Referral\Trigger;
use Symfony\Component\Form;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OptionsForm extends Form\AbstractType
{
	const REFERRAL_TYPE = 'referral_type';

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
		return 'refer_a_friend_options';
	}

	public function buildForm(Form\FormBuilderInterface $builder, array $options)
	{
		if (null === $options['referrer_type']) {
			throw new \LogicException('You must set the `referrer_type` option as an instance of TypeInterface');
		}

		if (!$options['referrer_type'] instanceof TypeInterface) {
			$type = gettype($options['referrer_type']) === 'object' ? get_class($options['referrer_type']) : gettype($options['referrer_type']);
			throw new \InvalidArgumentException('`referrer_type` option must be an instance of TypeInterface, ' . $type . ' given');
		}

		$builder->add(self::REFERRAL_TYPE, 'hidden', [
			'data' => $options['referrer_type']->getName(),
		]);

		$builder->add('name', 'text', [
			'label' => 'ms.refer.form.config.name.name'
		]);

		$this->_addConstraintFields($builder, $options['referrer_type']);
		$this->_addTriggerFields($builder, $options['referrer_type']);
	}

	public function finishView(Form\FormView $view, Form\FormInterface $form, array $options)
	{
		if (!$view->offsetExists(self::REFERRAL_TYPE)) {
			throw new \LogicException('No `' . self::REFERRAL_TYPE . '` field set on form! Be sure to call parent::buildForm() in form class!');
		}
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults([
			'referrer_type' => null
		]);
	}

	private function _addConstraintFields(Form\FormBuilderInterface $builder, TypeInterface $referralType)
	{
		$constraintsForm = $builder->create('ms.refer.form.constraints');

		$constraints = $this->_constraintCollectionBuilder
			->getCollectionFromType($referralType)
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
		$triggers = $this->_constraintCollectionBuilder
			->getCollectionFromType($referralType);

		$choices = [];

		foreach ($triggers as $trigger) {
			$choices[$trigger->getName()] = $trigger->getDisplayName();
		}

		$builder->add('triggers', 'choice', [
			'label'    => 'ms.refer.form.triggers',
			'multiple' => false,
			'expanded' => true,
			'choices'  => $choices,
		]);
	}
}