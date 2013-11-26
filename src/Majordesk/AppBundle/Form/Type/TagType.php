<?php

namespace Majordesk\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Majordesk\AppBundle\Form\DataTransformer\TagToNomTransformer;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$entityManager = $options['em'];
        $transformer = new TagToNomTransformer($entityManager);
	
        $builder
            ->add('nom'                , 'text', array(
				'attr'=> array('class'=>'form-control', 'placeholder' => 'Nom du Tag', 'autocomplete' => 'off')
			))
			->add($builder->create('p_tags'         , 'collection', array(
					'type'         => 'text',
					'options'      => array(
							'attr' => array('class' =>'form-control tag-typeahead', 'placeholder' =>'Nom du Tag parent', 'data-provide' => 'typeahead', 'autocomplete' => 'off')
						),
					'required' => false,
					'allow_add'    => true,
                    'allow_delete' => true,
					'by_reference' => false
					))->addModelTransformer($transformer)
			)
			->add($builder->create('c_tags'         , 'collection', array(
					'type'         => 'text',
					'options'      => array(
							'attr' => array('class' =>'form-control', 'placeholder' =>'Nom du Tag enfant', 'autocomplete' => 'off')
						),
					'required' => false,
					'allow_add'    => true,
                    'allow_delete' => true,
					'by_reference' => false
					))->addModelTransformer($transformer)
			)
			// ->add($builder->create('c_tags'         , 'collection', array(
							// 'attr' => array('class' =>'span5 position-relative-down-5 tag-typeahead', 'placeholder' =>'Nom du Tag parent', 'data-provide' => 'typeahead', 'autocomplete' => 'off'),
							// 'mapped' => false,
							// 'required' => false
						// ))->addModelTransformer($transformer)
			// )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majordesk\AppBundle\Entity\Tag'
        ));
		
		$resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    public function getName()
    {
        return 'tagtype';
    }
}
