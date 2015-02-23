<?php

namespace Message\Mothership\ReferAFriend\Form\ReferralType;

use Message\Mothership\ReferAFriend\Referral\Type\Collection as ReferralTypes;
use Symfony\Component\Form;

abstract class AbstractForm extends Form\AbstractType
{
	const REFERRAL_TYPE = 'referral_type';

	private $_referralTypes;

	final public function setTypeCollection(ReferralTypes $referralTypes)
	{
		$this->_referralTypes = $referralTypes;
	}

	public function buildForm(Form\FormBuilderInterface $builder, array $options)
	{
		$type = $this->_getType();

		if (!is_string($type)) {
			throw new \LogicException('Referral type must be a string, ' . gettype($type) . ' given');
		}

		$builder->add(self::REFERRAL_TYPE, 'hidden', [
			'data' => $this->_getType(),
		]);

		$builder->add('name', 'text', [
			'label' => 'ms.refer.form.config.name.name'
		]);
	}

	public function finishView(Form\FormView $view, Form\FormInterface $form, array $options)
	{
		if (!$view->offsetExists(self::REFERRAL_TYPE)) {
			throw new \LogicException('No `' . self::REFERRAL_TYPE . '` field set on form! Be sure to call parent::buildForm() in form class!');
		}
	}

	abstract protected function _getType();
}