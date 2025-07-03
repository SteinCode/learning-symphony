<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\ProductForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'product_index')]
    public function index(ProductRepository $repository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $repository->findAll(),
        ]);
    }

    #[Route('/product/{id}', name: 'product_show', requirements: ['id' => '\d+'])]
    public function show(Product $product): Response
    {

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/new', 'product_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductForm::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($product);

            $manager->flush();

            $this->addFlash('notice', 'Produktas buvo sekmingai pridetas');

            return $this->redirectToRoute('product_show', [
                'id' => $product->getId()
            ]);
        }
        return $this->render(
            '/product/new.html.twig',
            ['form' => $form],
        );
    }

    #[Route('/product/{id<\d+>}/edit', 'product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductForm::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash('notice', 'Produktas buvo sekmingai atnaujintas');

            return $this->redirectToRoute('product_show', [
                'id' => $product->getId()
            ]);
        }
        return $this->render(
            '/product/edit.html.twig',
            ['form' => $form],
        );
    }
    #[Route('/product/{id<\d+>}/delete', 'product_delete')]
    public function delete(Product $product, Request $request, EntityManagerInterface $manager): Response
    {

        if ($request->isMethod('POST')) {

            $manager->remove($product);

            $manager->flush();

            $this->addFlash('notice', "Produktas istrintas sekmingai!");

            return $this->redirectToRoute('product_index');
        }


        return $this->render(
            '/product/delete.html.twig',
            ['id' => $product->getId()],
        );
    }
}
