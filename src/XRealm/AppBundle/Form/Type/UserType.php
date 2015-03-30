<?php
// src/XRealm/AppBundle/Form/Type/UserType.php
namespace XRealm\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('username', 'text', array(
			'label'	=> 'form.username.label',
			'attr' => array(
				'placeholder' => 'form.username.placeholder',
			),
		));
        $builder->add('email', 'email', array(
			'label'	=> 'form.email.label',
			'required'	=> false,
			'attr' => array(
				'placeholder' => 'form.email.placeholder',
			),
		));
        $builder->add('plainPassword', 'repeated', array(
           'first_name'  => 'password',
           'second_name' => 'confirm',
           'type'        => 'password',
           'mapped'      => false,
		   'first_options'	=> array(
			    'label'  => 'form.password.label',
				'attr' => array(
					'placeholder' => 'form.password.placeholder',
				),
		    ),
		   'second_options'	=> array(
			    'label'  => 'form.confirm.label',
				'attr' => array(
					'placeholder' => 'form.confirm.placeholder',
				),
		    ),
        ));
		$builder->add('submit', 'submit', array(
			'label'	=> 'form.button.register',
			'attr' => array(
				'class'	=> 'btn btn-primary pull-right',	
			),			
		));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'XRealm\AppBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}