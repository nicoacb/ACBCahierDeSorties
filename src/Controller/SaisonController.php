<?php

namespace App\Controller;

use App\Entity\Saison;
use App\Form\SaisonType;
use App\Repository\SaisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SaisonController extends AbstractController
{
    private $entityManager;
    private $saisonRepository;

    public function __construct(EntityManagerInterface $entityManager,
        SaisonRepository $saisonRepository)
    {
        $this->entityManager = $entityManager;
        $this->saisonRepository = $saisonRepository;
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function index()
    {
    	$listeSaisons = $this->saisonRepository
    	    ->findAll();
    	
        return $this->render('saison/index.html.twig', array('listeSaisons' => $listeSaisons));
    }
    
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function ajouter(Request $request)
    {
        $saison = new Saison();
        $form   = $this->get('form.factory')->create(SaisonType::class, $saison);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $this->EnregistreSaison($saison);

            $this->addFlash('success', 'Saison bien enregistrée.');

            return $this->redirectToRoute('aviron_sortie_saison_liste');
        }

        return $this->render('saison/ajouter.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function modifier(Request $request, $id)
    {
        $saison = $this->saisonRepository
            ->find($id);

        // Si $saison est null, l'id n'existe pas
        if(null == $saison) {
            throw new NotFoundHttpException("La saison d'id ".$id." n'existe pas.");
        }

        // On génère le formulaire
        $form = $this->createForm(SaisonType::class, $saison);

        // Si la requête est en POST, c'est qu'on veut enregistrer la saison
        if($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid()) {
                $this->EnregistreSaison($saison);

                $this->addFlash('success', 'Saison bien enregistrée.');

                // On redirige vers la liste des saisons
                return $this->redirectToRoute('aviron_sortie_saison_liste');
            }
        }
        return $this->render('saison/modifier.html.twig',
                                array('form' => $form->createView()));
    }
    
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function supprimer(Request $request, $id)
    {
        $saison = $this->saisonRepository
            ->find($id);

        // Si $saison est null, l'id n'existe pas
        if(null == $saison) {
            throw new NotFoundHttpException("La saison d'id ".$id." n'existe pas.");
        }

        $this->SupprimeSaison($saison);

        $this->addFlash('success', 'Saison bien supprimée.');

        // On redirige vers la liste des saisons
        return $this->redirectToRoute('aviron_sortie_saison_liste');
    }

    private function EnregistreSaison($saison)
    {
        $this->entityManager->persist($saison);
        $this->entityManager->flush();
    }

    private function SupprimeSaison($saison)
    {
        $this->entityManager->remove($saison);
        $this->entityManager->flush();
    }
}