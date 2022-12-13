<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Group;
use App\Form\GroupeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GroupRepository;
use App\Form\GroupType;

class GroupController extends AbstractController
{
    #[Route('/group', name: 'group')]
    public function group(Request $request, GroupRepository $groupRepository): Response
    {
        
        $group = new Group();
        $formGroup = $this->createForm(GroupType::class, $group);

        $formGroup->handleRequest($request);
        if ($formGroup->isSubmitted() && $formGroup->isValid()) {
            $groupRepository->save($formGroup->getData(), true);
            return $this->redirectToRoute('index');
        }
        return $this->render('Contact/group.html.twig', [
            'title' => 'Ajouter un group',
            'formGroup' => $formGroup->createView()
        ]);
    }
}

