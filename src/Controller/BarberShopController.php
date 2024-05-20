<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BarberShopController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin');
        }
        if ($this->isGranted('ROLE_MEMBER')){
            return $this->redirectToRoute('app_klant');
        }
        if ($this->isGranted('ROLE_EMPLOYEE'))
            return $this->redirectToRoute('app_medewerker');

        return $this->render('barber_shop/index.html.twig', [

        ]);
    }
    #[Route('/category', name: 'app_category')]
    public function showCategory(EntityManagerInterface $em): Response
    {
        $showCategory =$em->getRepository(Category::class)->findAll();
        return $this->render('barber_shop/showcategory.html.twig', [
            'showCategorys' => $showCategory,
        ]);


    }
    #[Route('/show-producten/{id}', name: 'app_show_producten')]
    public function showProduct(EntityManagerInterface $em,int $id): Response
    {
        $showCategory =$em->getRepository(Category::class)->find($id);
        $showProducts=$showCategory->getProducts();
        return $this->render('barber_shop/productshow.html.twig', [
            'showProducts' => $showProducts,
        ]);

    }




}
