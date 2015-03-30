<?php
// src/XRealm/AppBundle/Form/Type/UserType.php
namespace XRealm\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActivityPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('message', 'textarea', array(
			'label'	=> 'form.activitypost.label',
			'attr' => array(
				'placeholder' => ($options['isXRealm'] ? 'form.activitypost.xrealmplaceholder' : 'form.activitypost.placeholder'),
                'rows'  => 6,
			),
		));
        if(!$options['isXRealm'])
        {
            $builder->add('isPublic', 'checkbox', array(
                'label'	=> 'form.post_public.label',
                'required'  => false,
                'attr' => array(
                    'placeholder' => 'form.post_public.placeholder',
                    'class'   => 'toggle-switch',
                ),
            ));
        }
        if($options['isAssist'])
        {
            $builder->add('isSticky', 'checkbox', array(
                'label'	=> 'form.post_sticky.label',
                'required'  => false,
            ));
        }
        
		$builder->add('submit', 'submit', array(
			'label'	=> 'form.send.label',
			'attr' => array(
				'class' => 'btn btn-primary pull-right',
			),
		));
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'XRealm\AppBundle\Entity\ActivityPost',
            'isAssist'  => false,
            'isXRealm'  => false,
        ));
    }

    public function getName()
    {
        return 'activity_post';
    }
}