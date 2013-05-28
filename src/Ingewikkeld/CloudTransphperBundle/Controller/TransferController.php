<?php

namespace Ingewikkeld\CloudTransphperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ingewikkeld\CloudTransphperBundle\Form\TransferType;
use Ingewikkeld\CloudTransphperBundle\Entity\Transfer;

class TransferController extends Controller
{
    /**
     * Upload files to our file transfer service
     *
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createForm(new TransferType(), new Transfer(), array('show_legend' => false));

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest()->get('ingewikkeld_cloudtransphperbundle_transfertype'));

            if ($form->isValid()) {

            }
        }

        return array('form' => $form->createView());
    }
}
