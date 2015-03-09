<?php

namespace Message\Mothership\ReferAFriend\Form;

use Symfony\Component\Form;
use Symfony\Component\Validator\Constraints;

/**
 * Class ReferAFriend
 * @package Message\Mothership\ReferAFriend\Form
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 */
class ReferAFriend extends Form\AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'refer_a_friend';
	}

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(Form\FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', [
			'label'       => 'ms.refer.form.refer_a_friend.name',
			'constraints' => [
				new Constraints\NotBlank,
			]
		]);

		$builder->add('email', 'text', [
			'label'       => 'ms.refer.form.refer_a_friend.email',
			'constraints' => [
				new Constraints\Email,
				new Constraints\NotBlank,
			]
		]);
	}
}