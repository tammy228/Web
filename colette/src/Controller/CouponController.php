<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Form\CouponType;
use App\Repository\CouponRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CouponController extends AbstractController
{
    /**
     * @Route("admin/coupon", name="admin.coupon.list")
     * @param CouponRepository $couponRepository
     * @return Response
     */
    public function adminCouponList(CouponRepository $couponRepository)
    {
        $coupons = $couponRepository->findAll();

        return $this->render('admin/coupon/list.html.twig',array(
            'coupons' => $coupons,
        ));
    }

    /**
     * @Route("admin/coupon/create", name="admin.coupon.create")
     * @param Request $request
     * @return Response
     */
    public function adminCouponCreate(Request $request)
    {
        $coupon = new Coupon();
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if($form['type']->getData()  == 0  && $form['number']->getData() > 100){
                echo "<script>alert('折扣百分比須低於100 以下');window.history.back(-1);</script>";
                die;
            }


            $coupon ->setUuid();
            $em = $this->getDoctrine()->getManager();

            $em->persist($coupon);
            $em->flush();

            return $this->redirectToRoute('admin.coupon.list');
        }

        return $this->render('admin/coupon/create.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("admin/coupon/{uuid}/update", name="admin.coupon.update")
     * @param $uuid
     * @param CouponRepository $couponRepository
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function adminCouponUpdate($uuid, CouponRepository $couponRepository, Request $request)
    {
        $coupon = $couponRepository->findOneBy(array('uuid'=>$uuid));
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coupon = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($coupon);
            $em->flush();

            return $this->redirectToRoute('admin.coupon.list');
        }

        return $this->render('admin/coupon/update.html.twig',array(
            'form' => $form->createView(),
            'coupon' => $coupon
        ));
    }

    /**
     * @Route("admin/coupon/{uuid}/delete", name="admin.coupon.delete")
     */
    public function adminCouponDelete($uuid, CouponRepository $couponRepository)
    {
        $coupon = $couponRepository->findOneBy(array('uuid'=>$uuid));

        if(!$coupon) return $this->redirectToRoute('admin.coupon.list');

        $em = $this->getDoctrine()->getManager();
        $em->remove($coupon);
        $em->flush();

        return $this->redirectToRoute('admin.coupon.list');
    }
}
