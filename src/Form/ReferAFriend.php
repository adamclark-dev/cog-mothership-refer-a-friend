<?php

namespace Message\Mothership\ReferAFriend\Form;

use Symfony\Component\Form;
use Symfony\Component\Validator\Constraints;

class ReferAFriend extends Form\AbstractType
{
	public function getName()
	{
		return 'refer_a_friend';
	}

	public function buildForm(Form\FormBuilderInterface $builder, array $options)
	{
		$builder->add('email', 'text', [
			'label'       => 'ms.refer.form.refer_a_friend.email',
			'constraints' => [
				new Constraints\Email,
				new Constraints\NotBlank,
			]
		]);

		$builder->add('name', 'text', [
			'label'       => 'ms.refer.form.refer_a_friend.name',
			'constraints' => [
				new Constraints\NotBlank,
			]
		]);

		$builder->add('message', 'textarea', [
			'label' => 'ms.refer.form.refer_a_friend.message',
		]);
	}
}