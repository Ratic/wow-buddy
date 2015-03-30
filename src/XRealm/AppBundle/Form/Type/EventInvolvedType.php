<?php
// src/XRealm/AppBundle/Form/Type/UserType.php
namespace XRealm\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use XRealm\AppBundle\Entity\EventInvolvedCharacter;

class EventInvolvedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('status', 'choice', array(
            'attr'  => array(
                'class' => 'form-control',
            ),
            'choices'   => array(
                EventInvolvedCharacter::STATUS_ACCEPTED => 'website.accept',
                EventInvolvedCharacter::STATUS_DECLINED => 'website.decline',
            ),
        ));
        $builder->add('submit', 'submit', array(
            'label' => 'website.event_insert',
            'attr'  => array(
                'class' => 'btn btn-primary pull-left',
            )
        ));
        $builder->add('redirect', 'hidden', array(
            'mapped'    => false,
            'data'  => $options['redirect'],
        ));
        $builder->add('comment', 'textarea', array(
            'attr' => array(
                'placeholder'   => 'form.comment.placeholder',
                'style' => 'width: 100%; margin: 10px 0;padding: 10px; border:1px solid #ccc;',
            ),
            'required'  => false,
        ));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'XRealm\AppBundle\Entity\EventInvolvedCharacter',
            'redirect'  => 'calendar',
        ));
    }

    public function getName()
    {
        return 'event_involved';
    }
}