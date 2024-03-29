<?php

namespace App\Controller;

use App\Entity\TypeBateau;
use App\Form\TypeBateauType;
use App\Repository\TypeBateauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TypeBateauController extends AbstractController
{
    private $entityManager;
    private $typeBateauRepository;

    public function __construct(EntityManagerInterface $entityManager,
        TypeBateauRepository $typeBateauRepository)
    {
        $this->entityManager = $entityManager;
        $this->typeBateauRepository = $typeBateauRepository;
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function index($page)
    {
        // On ne sait pas combien de pages il y a mais on sait qu'une page doit être supérieure ou égale à 1
        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher une page d'erreur 404
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $typesbateau = $this->typeBateauRepository
    	    ->getListeTypesBateau();

        return $this->render('typebateau/index.html.twig', array('listTypesBateau' => $typesbateau));
    }
    
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function ajouter(Request $request)
    {
        // On créé un objet TypeBateau
        $typebateau = new TypeBateau();

        // On génère le formulaire
        $form = $this->createForm(TypeBateauType::class, $typebateau);

        // Si la requête est en POST, c'est qu'on veut enregistrer le type de bateau
        if($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid()) {
                // On enregistre l'objet $typebateau en base de données
                $this->EnregistreTypeBateau($typebateau);

                $this->addFlash('success', 'Type de bateau bien ajouté.');

                // On redirige vers les types de bateaux
                return $this->redirectToRoute('aviron_sortie_typebateau_home');
            }
        }

        // Sinon on affiche le formulaire pour ajouter un type de bateau
        return $this->render('typebateau/ajouter.html.twig', 
                        array('form' => $form->createView()));
    }
    
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function modifier(Request $request, $id)
    {
        $typebateau = $this->typeBateauRepository
            ->find($id);

        // Si $typebateau est null, l'id n'existe pas
        if(null == $typebateau) {
            throw new NotFoundHttpException("Le type de bateau d'id ".$id." n'existe pas.");
        }

        // On génère le formulaire
        $form = $this->createForm(TypeBateauType::class, $typebateau);

        // Si la requête est en POST, c'est qu'on veut enregistrer le type de bateau
        if($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid()) {
                // On enregistre l'objet $typebateau en base de données
                $this->EnregistreTypeBateau($typebateau);

                $this->addFlash('success', 'Type de bateau bien enregistré.');

                // On redirige vers les types de bateaux
                return $this->redirectToRoute('aviron_sortie_typebateau_home');
            }
        }
 
        return $this->render('typebateau/modifier.html.twig', 
                        array('form' => $form->createView()));
    }

    private function EnregistreTypeBateau($typebateau)
    {
        $this->entityManager->persist($typebateau);
        $this->entityManager->flush();
    }
}
