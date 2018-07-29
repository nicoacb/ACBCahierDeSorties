<?php

namespace Aviron\SortieBundle\Controller;

use Aviron\SortieBundle\Entity\Saison;
use Aviron\SortieBundle\Form\SaisonType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SaisonController extends Controller
{
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function indexAction()
    {
    	$listeSaisons = $this->getDoctrine()
    	->getManager()
    	->getRepository('AvironSortieBundle:Saison')
    	->findAll();
    	
        return $this->render('AvironSortieBundle:Saison:index.html.twig', array('listeSaisons' => $listeSaisons));
    }
    
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function ajouterAction(Request $request)
    {
        $saison = new Saison();
        $form   = $this->get('form.factory')->create(SaisonType::class, $saison);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($saison);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Saison bien enregistrée.');

            return $this->redirectToRoute('aviron_sortie_saison_liste');
        }

        return $this->render('AvironSortieBundle:Saison:ajouter.html.twig', array(
        'form' => $form->createView(),
        ));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function modifierAction(Request $request, $id)
    {
        // On récupère l'entité de la saison correspondant à l'id $id
        $saison = $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironSortieBundle:Saison')
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
                // On enregistre l'objet $saison en base de données
                $em = $this->GetDoctrine()->getManager();
                $em->persist($saison);
                $em->flush();

                // On affiche un message de validation
                $request->getSession()->getFlashBag()->add('success', 'Saison bien enregistrée.');

                // On redirige vers la liste des saisons
                return $this->redirectToRoute('aviron_sortie_saison_liste');
            }
        }
        return $this->render('AvironSortieBundle:Saison:modifier.html.twig',
                                array('form' => $form->createView()));
    }
    
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function supprimerAction(Request $request, $id)
    {
        // On récupère l'entité de la saison correspondante à l'id $id
        $saison = $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironSortieBundle:Saison')
            ->find($id);

        // Si $saison est null, l'id n'existe pas
        if(null == $saison) {
            throw new NotFoundHttpException("La saison d'id ".$id." n'existe pas.");
        }

        // On supprime l'objet $saison en base de données
        $em = $this->GetDoctrine()->getManager();
        $em->remove($saison);
        $em->flush();

        // On affiche un message de validation
        $request->getSession()->getFlashBag()->add('success', 'Saison bien supprimée.');

        // On redirige vers la liste des saisons
        return $this->redirectToRoute('aviron_sortie_saison_liste');
    }

}
