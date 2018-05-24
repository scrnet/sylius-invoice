<?php

namespace Behappy\InvoicePlugin\Controller;

use Behappy\InvoicePlugin\Entity\OrderInvoice;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvoiceController extends Controller
{
    /**
     * @param Request $request
     *
     * @return PdfResponse
     */
    public function renderPdfAction(Request $request): PdfResponse
    {
        if(!empty($request->get('id'))){
            $orderId = (int)$request->get('id');
            $order = $this->container->get('sylius.repository.order')->find($orderId);
        }elseif(!empty($request->get('number'))){
            $orderNumber = $request->get('number');
            $order = $this->container->get('sylius.repository.order')->findOneBy(['number' => $orderNumber]);
        }else{
            throw new BadRequestHttpException('Missing mandatory parameter');
        }
        if(!$order instanceof Order)
            throw new NotFoundHttpException('The "order" has not been found');
    
        if($order->getState() !== OrderInterface::STATE_FULFILLED)
            throw new BadRequestHttpException('Order not fulfilled');
    
        $html = $this->renderView('@BehappyInvoice/Invoice/pdf.html.twig', [
            'order' => $order
        ]);
        
        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'invoice.pdf'
        );
    }
    
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function renderAction(Request $request): Response
    {
        if(!empty($request->get('id'))){
            $orderId = (int)$request->get('id');
            $order = $this->container->get('sylius.repository.order')->find($orderId);
        }elseif(!empty($request->get('number'))){
            $orderNumber = $request->get('number');
            $order = $this->container->get('sylius.repository.order')->findOneBy(['number' => $orderNumber]);
        }else{
            throw new BadRequestHttpException('Missing mandatory parameter');
        }
        if(!$order instanceof Order)
            throw new NotFoundHttpException('The "order" has not been found');
    
        if($order->getState() !== OrderInterface::STATE_FULFILLED)
            throw new BadRequestHttpException('Order not fulfilled');

        $em = $this->getDoctrine()->getManager();
        $oOrderInvoice = $em->getRepository('BehappyInvoicePlugin:OrderInvoice')->findOneByOrder( $orderId );
        if( !$oOrderInvoice ){
            $oOrderInvoice = new OrderInvoice();
            $oOrderInvoice->setOrder( $order );
            $nNumber =  $em->getRepository('BehappyInvoicePlugin:OrderInvoice')->findLastNumber();

            if(!$nNumber){
                $nNumber = 1;
            }else{
                $nNumber = $nNumber['max_number'] + 1;
            }

            $oOrderInvoice->setNumber( $nNumber );
            $em->persist( $oOrderInvoice );
            $em->flush();
        }

        return $this->render('@BehappyInvoice/Invoice/pdf.html.twig', [
            'order' => $order,
            'invoice_number' => sprintf('%08d', $oOrderInvoice->getNumber() )
        ]);
    }
}