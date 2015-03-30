<?php
// src/XRealm/UserBundle/Form/Type/UserType.php
namespace XRealm\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddCharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('realm', 'entity', array(
			'label'	=> 'form.realm.label',
			'attr' => array(
				'placeholder' => 'form.realm.placeholder',
			),
			'class' => 'XRealm\\UserBundle\\Entity\\BlizzRealm',
			'property' => 'name',
		));
		$builder->add('name', 'text', array(
			'label'	=> 'form.character_name.label',
			'attr' => array(
				'placeholder' => 'form.character_name.placeholder',
			),
			
		));
		$builder->add('submit', 'submit', array(
			'label'	=> 'form.add.label',
			'attr' => array(
				'class' => 'btn btn-primary pull-right',
			),
		));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'XRealm\UserBundle\Entity\BlizzCharacter',
        ));
    }

    public function getName()
    {
        return 'blizz_character';
    }
}