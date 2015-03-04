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
		$builder->add('text', 'email', [
			'label'       => 'ms.refer.form.refer_a_friend.email',
			'constraints' => [
				new Constraints\Email,
				new Constraints\NotBlank,
			]
		]);

		$builder->add('textarea', 'message', [
			'label'       => 'ms.refer.form.refer_a_friend.message',
		]);
	}
}