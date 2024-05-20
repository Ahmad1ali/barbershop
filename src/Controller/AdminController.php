<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\AddProductType;
use App\Form\EditEmployeeType;
use App\Form\EditProductType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $em ): Response
    {
        $showProductAdmin = $em->getRepository(Product::class)->findAll();
        $showMedewerker = $em->getRepository(User::class)->findBy(['roles'=>array('["ROLE_EMPLOYEE"]')]);

        return $this->render('admin/index.html.twig', [
            'adminProduct' => $showProductAdmin,
            'showMedewerkers' => $showMedewerker
        ]);
    }
    #[Route('/delete-employee/{id}', name: 'delete_employee')]
    public function deleteEmployee( EntityManagerInterface $em,int $id ): Response
    {
        // from inside a controller
        $deleteMedewerker = $em->getRepository(User::class);
        $product = $deleteMedewerker->find($id);
        $em->remove($product);
        $em->flush();

    }

    #[Route('/edit-employee/{id}', name: 'edit_employee')]
    public function editEmployee(EntityManagerInterface $em ,Request $request,int $id ): Response
    {
        $showMedewerker = $em->getRepository(User::class)->find($id);

        $form = $this->createForm(EditEmployeeType::class, $showMedewerker);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $em->persist($task);
            $em->flush();


            return $this->redirectToRoute('app_admin');
        }



        return $this->render('admin/editemployee.html.twig', [
            'form'=>$form

        ]);
    }

    #[Route('/add-employee', name: 'add_employee')]
    public function addEmployee(EntityManagerInterface $em ,Request $request , UserPasswordHasherInterface $passwordHasher): Response
    {
        $newEmpolyee = new User();

        $form = $this->createForm(RegisterType::class, $newEmpolyee);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $newEmpolyee->setRoles(['ROLE_EMPLOYEE']);
            $newEmpolyee->setPassword($passwordHasher->hashPassword(
                $newEmpolyee,
                $newEmpolyee->getPassword()

            ));
            $em->persist($task);
            $em->flush();


            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/addempolyee.html.twig', [
            'form'=>$form

        ]);
    }
    #[Route('/edit-product/{id}', name: 'edit_product')]
    public function editProduct(EntityManagerInterface $em ,Request $request,int $id ): Response
    {
        $showMedewerker = $em->getRepository(Product::class)->find($id);

        $form = $this->createForm(EditProductType::class, $showMedewerker);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $em->persist($task);

            $em->flush();


            return $this->redirectToRoute('app_admin');
        }



        return $this->render('admin/editemployee.html.twig', [
            'form'=>$form

        ]);
    }
    #[Route('/add-product', name: 'add_product')]
    public function addProduct(Request $request,EntityManagerInterface $em ): Response
    {
        $newProduct = new Product();

        $form = $this->createForm(AddProductType::class, $newProduct);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $em->persist($task);
            $em->flush();




            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/addproduct.html.twig', [
            'form' => $form,
        ]);

    }


}
