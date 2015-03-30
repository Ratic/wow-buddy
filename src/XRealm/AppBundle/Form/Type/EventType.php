<?php
// src/XRealm/AppBundle/Form/Type/UserType.php
namespace XRealm\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('title', 'text', array(
            'label'	=> 'form.event_name.label',
            'attr' => array(
                    'placeholder' => 'form.event_name.placeholder',
            ),
        ));
        $builder->add('startAt', 'datetime', array(
            'label'	=> 'form.event_startat.label',
            'attr' => array(
                'class' => 'form-control',
            ),
            'widget'    => 'single_text',
            'format'    => $options['datetime_format'],
        ));
        $builder->add('endAt', 'datetime', array(
            'label'	=> 'form.event_endat.label',
            'attr' => array(
                'class' => 'form-control',
            ),
            'required'  => false,
            'widget'    => 'single_text',
            'format'    => $options['datetime_format'],
        ));
        $builder->add('submit', 'submit', array(
            'label'	=> 'form.create.label',
            'attr' => array(
                    'class' => 'btn btn-primary pull-right',

            ),
        ));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'XRealm\AppBundle\Entity\Event',
            'datetime_format' => 'yyyy-MM-dd HH:mm',
        ));
    }

    public function getName()
    {
        return 'event';
    }
}