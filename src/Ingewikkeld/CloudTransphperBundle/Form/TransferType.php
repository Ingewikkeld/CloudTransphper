<?php

namespace Ingewikkeld\CloudTransphperBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TransferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array('label' => 'File to transfer', 'mapped' => false))
            ->add('senderName', null, array('label' => 'Your name'))
            ->add('senderEmail', null, array('label' => 'Your e-mail'))
            ->add('recipientName', null, array('label' => 'Recipient name'))
            ->add('recipientEmail', null, array('label' => 'Recipient E-mail'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ingewikkeld\CloudTransphperBundle\Entity\Transfer'
        ));
    }

    public function getName()
    {
        return 'ingewikkeld_cloudtransphperbundle_transfertype';
    }
}
