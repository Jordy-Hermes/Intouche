<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AddFields;
use App\Repository\AddFieldsRepository;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddFieldType;

#[Route('/addfields')]
class AddFieldsController extends AbstractController
{

    public function __construct(private AddFieldsRepository $addFieldsRepository){}

    #[Route('/edit/{id}', name: 'app_field_edit')]
    public function edit(AddFields $addfield,  Request $request): Response
    {
        $fieldForm = $this->createForm(AddFieldType::class, $addfield);

        $fieldForm->handleRequest($request);
        if ($fieldForm->isSubmitted() && $fieldForm->isValid()) {
            $this->addFieldsRepository->save($fieldForm->getData(), true);
            return $this->redirectToRoute('contact_edit', ['id'=>$addfield->getContact()->getId()]);
        }

        return $this->render('add_fields/edit.html.twig', [
            'fieldForm' => $fieldForm->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_field_delete')]
    public function delete(AddFields $addfield): Response
    {
        $contact = $addfield->getContact();
        $this->addFieldsRepository->remove($addfield, true);
        return $this->redirectToRoute('contact_edit', ['id'=>$contact->getId()]);
    }
}
