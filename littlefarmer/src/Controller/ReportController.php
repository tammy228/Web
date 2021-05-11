<?php

namespace App\Controller;

use App\Entity\Report;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/farmer/report", name="farmer.report.list")
     */
    public function reportList()
    {
        $products =$this->getUser()->getProducts();
        return $this->render('farmer/report/list.html.twig',array(
            'products'=>$products,
        ));
    }

    /**
     * @Route("/farmer/report/{id}/", name= "farmer.report.list.by")
     * @param $id
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function reportListBy($id,ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(array('uuid'=>$id));
        $reports = $product->getReport();
        return $this->render('farmer/report/listBy.html.twig',array(

            'reports'=>$reports,
            'product' =>$product,
        ));
    }


}
