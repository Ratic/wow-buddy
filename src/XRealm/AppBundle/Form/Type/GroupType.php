<?php
// src/XRealm/AppBundle/Form/Type/UserType.php
namespace XRealm\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['type'] == 'new' || $options['type'] == 'edit')
        {
            $builder->add('name', 'text', array(
                'label'	=> 'form.group_name.label',
                'attr' => array(
                        'placeholder' => 'form.group_name.placeholder',
                ),
                'disabled'  => ($options['type'] == 'new') ? false : true,
            ));
        }
        
        if($options['type'] == 'new' || $options['type'] == 'edit' )
        {
            $builder->add('isPublic', 'choice', array(
                'label'	=> 'form.group_public.label',
                'choices' => array(true => 'form.group_public.yes', false => 'form.group_public.no'),
                'attr' => array(
                        'placeholder' => 'form.group_public.placeholder',
                ),
            ));
        }
        if($options['type'] == 'edit')
        {
            $builder->add('description', 'ckeditor', array(
                'label'	=> 'form.group_description.label',
                    'config' => array(
                        'uiColor' => '#165e80',
                        'toolbar' => array(
                            array(
                                'name'  => 'formats',
                                'items' => array('Format', 'Font', 'FontSize'),
                            ),
                            array(
                                'name'  => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                            ),
                            /*array(
                                'name'  => 'source',
                                'items' => array('Source'),
                            ),*/

                        ),
                    ),
            ));
        }
        
        if($options['type'] == 'new' || $options['type'] == 'edit')
        {
            $builder->add('submit', 'submit', array(
                'label'	=> 'form.' . (($options['type'] == 'new') ? 'new' : 'save') . '.label',
                'attr' => array(
                        'class' => 'btn btn-primary pull-right',
                ),
            ));
        }
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'XRealm\AppBundle\Entity\Group',
            'type'      => null,
        ));
    }

    public function getName()
    {
        return 'group';
    }
}