<?php

namespace Ingewikkeld\CloudTransphperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ingewikkeld\CloudTransphperBundle\Form\TransferType;
use Ingewikkeld\CloudTransphperBundle\Entity\Transfer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        $transfer = new Transfer();
        $form = $this->createForm(new TransferType(), $transfer, array('show_legend' => false));

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest()->get('ingewikkeld_cloudtransphperbundle_transfertype'));

            if ($form->isValid()) {

                $files = $this->getRequest()->files->get('ingewikkeld_cloudtransphperbundle_transfertype');
                /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $upload */
                $upload = $files['upload'];

                /** @var \Ingewikkeld\HPCloudBundle\Service\HPCloudService $service */
                $service = $this->get('ingewikkeld_hp_cloud.service');
                $localObject = $service->getLocalObjectFromFile($upload->getRealPath(), $upload->getClientOriginalName());
                $object = $service->upload($this->container->getParameter('cloudvps_container'), $localObject);

                $transfer->setLink($object->url());
                $this->getDoctrine()->getManager()->persist($transfer);
                $this->getDoctrine()->getManager()->flush();

                $seed = md5(time().$transfer->getSenderEmail().$transfer->getRecipientEmail());

                $message = \Swift_Message::newInstance()
                    ->setSubject("You've got a new file!")
                    ->setFrom($this->container->getParameter('transphper_email'))
                    ->setTo($transfer->getRecipientEmail())
                    ->setBody(
                        $this->renderView(
                            'IngewikkeldCloudTransphperBundle:Transfer:mail.txt.twig',
                            array(
                                'seed' => $seed,
                                'hash' => $transfer->getHash($seed),
                                'transfer' => $transfer,
                            )
                        )
                    )
                ;
                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add('notice', 'Your file has been uploaded, ' . $transfer->getRecipientName() . ' will receive an e-mail soon');

                return $this->redirect($this->generateUrl('homepage'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * Download a file
     *
     * @param $hash
     * @param $seed
     *
     * @Route("/download/{id}/{seed}/{hash}", name="download")
     */
    public function downloadAction($id, $hash, $seed)
    {
        /** @var \Ingewikkeld\CloudTransphperBundle\Entity\Transfer $transfer */
        $transfer = $this->getDoctrine()->getRepository('IngewikkeldCloudTransphperBundle:Transfer')->find($id);

        if (!$transfer || !$transfer->validateHash($hash, $seed)) {
            throw new NotFoundHttpException();
        }

        return $this->redirect($transfer->getLink());
    }
}
