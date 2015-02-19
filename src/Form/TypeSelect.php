<?php

namespace Message\Mothership\ReferAFriend\Form;

use Message\Mothership\ReferAFriend\Referral\Type\Collection as ReferralTypes;
use Message\Cog\Localisation\Translator;

use Symfony\Component\Form;
use Symfony\Component\Validator\Constraints;

class TypeSelect extends Form\AbstractType
{
	const NAME = 'refer_a_friend_type_select';

	/**
	 * @var ReferralTypes
	 */
	private $_types;

	/**
	 * @var Translator
	 */
	private $_translator;

	public function __construct(ReferralTypes $types, Translator $translator)
	{
		$this->_types      = $types;
		$this->_translator = $translator;
	}

	public function getName()
	{
		return self::NAME;
	}

	public function buildForm(Form\FormBuilderInterface $builder, array $options)
	{
		$builder->add('type', 'choice', [
			'label'    => 'ms.refer.form.type_select.name',
			'choices'  => $this->_getChoices(),
			'expanded' => true,
			'multiple' => false,
			'constraints' => [
				new Constraints\NotBlank,
			],
			'attr' => ['data-help-key' => 'ms.refer.form.type_select.help']
		]);
	}

	private function _getChoices()
	{
		$choices = [];

		foreach ($this->_types as $type) {
			$choices[$type->getName()] = $this->_translator->trans($type->getDisplayName());
		}

		return $choices;
	}
}