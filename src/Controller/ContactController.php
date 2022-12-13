<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Group;
use App\Entity\AddFields;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContactRepository;
use App\Repository\GroupRepository;
use App\Repository\AddFieldsRepository;
use App\Form\ContactType;
use App\Form\GroupType;
use App\Form\AddFieldType;

class ContactController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'title' => 'Welcome !',
        ]);
    }

    

    #[Route('/contact/read/{id}', name: 'read')]
    public function read(Contact $contact = null, ManagerRegistry $doctrine): Response
    {
        
        return $this->render('Contact/read.html.twig', [
            'title' => 'Info du contact',
            'contact' => $contact,
        ]);
    }

        

    #[Route('/contact/list', name: 'contact_list')]
    public function list(ManagerRegistry $doctrine): Response
    {

        // get contact list from database
        $contactList = $doctrine->getRepository(Contact::class)->findAll();

        return $this->render('Contact/list.html.twig', [
            'title' => 'Liste',
            'contactList' => $contactList,
        ]);
    }

    #[Route('/contact/add', name: 'contact_add')]
    public function add(Request $request, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->save($form->getData(), true);
            return $this->redirectToRoute('contact_list');
        }

        return $this->render('Contact/add.html.twig', [
            'title' => 'Ajouter un contact',
            'form' => $form->createView()
        ]);
    }

    




    #[Route('/contact/edit/{id}', name: 'contact_edit')]
    public function edit(Contact $contact = null, Request $request, ManagerRegistry $doctrine, GroupRepository $groupRepository, AddFieldsRepository $addFieldsRepository): Response
    {
        $field = new AddFields();
        $field->setContact($contact);
        $fieldForm = $this->createForm(AddFieldType::class, $field);

        $fieldForm->handleRequest($request);
        if ($fieldForm->isSubmitted() && $fieldForm->isValid()) {
            $addFieldsRepository->save($fieldForm->getData(), true);
            return $this->redirectToRoute('contact_edit', ['id'=>$contact->getId()]);
        }

        $fieldsList = $addFieldsRepository->findBy(['contact'=>$contact]);

        return $this->render('Contact/edit.html.twig', [
            'title' => 'Editer un contact',
            'groups' => $groupRepository->findAll(),
            'contact' => $contact,
            'fieldForm' => $fieldForm->createView(),
            'fieldsList' => $fieldsList
        ]);
    }

    #[Route('/contact/update/{id}', name: 'contact_update')]
    public function update(Contact $contact = null, Request $request, ManagerRegistry $doctrine, GroupRepository $groupRepository): RedirectResponse
    {
        if ($contact) {
            $contact->setLname($request->request->get('lname'));
            $contact->setFname($request->request->get('fname'));
            $contact->setTel($request->request->get('tel'));
            $contact->setMail($request->request->get('email'));
            $group = $request->request->get('idGroup');
            if ($group != '') {
                $contact->addContactGroup($groupRepository->find((int)$group));
            }
            $manager = $doctrine->getManager();
            $manager->persist($contact);
            $manager->flush();

            $this->addFlash('success', "Contact modifié");
        }
        else {
            $this->addFlash('error', "Contact inexistant");
        }

        return $this->redirectToRoute("list");
    }
    
    #[Route('/contact/delete/{id}', name: 'contact_delete')]
    public function delete(Contact $contact = null, ManagerRegistry $doctrine): RedirectResponse
    {
        if ($contact) {
            $manager = $doctrine->getManager();
            $manager->remove($contact);
            $manager->flush();

            $this->addFlash('success', "Contact supprimé");
        }
        else {
            $this->addFlash('error', "Contact inexistant");
        }

        return $this->redirectToRoute("list");
    }

    #[Route('/contact/add/sender', name: 'contact_sender')]
    public function sender(Request $request, ManagerRegistry $doctrine): RedirectResponse
    {
        // dd($request->request->all());
        $contact = $request->request->all();
        $entityManager = $doctrine->getManager();
        $group = new Group();
        $contact = new Contact(null, $contact['lname'], $contact['fname'], $contact['tel'], $contact['email'], null, $group['groupName'] || null);
        $entityManager->persist($contact);
        $entityManager->flush();

        return $this->redirectToRoute("list");
    }

    #[Route('/test', name: 'test')]
    public function test(Request $request): Response
    {
        // dd($request->request->all());
        return $this->render('test/test.html.twig', [
            //Post data
            'posts' => $request->request->all(),
        ]);
    }
}
