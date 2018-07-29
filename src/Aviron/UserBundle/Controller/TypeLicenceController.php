<?php
namespace Aviron\UserBundle\Controller;

use Aviron\UserBundle\Entity\TypeLicence;
/*use Aviron\SortieBundle\Form\SaisonType;*/
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TypeLicenceController extends Controller
{
	/**
    * @Security("has_role('ROLE_ADMIN')")
    */
	public function indexAction()
	{
		$listeTypesLicence = $this->getDoctrine()
		->getManager()
		->getRepository('AvironUserBundle:TypeLicence')
		->findAll();
		 
		return $this->render('AvironUserBundle:TypeLicence:index.html.twig', array('listeTypesLicence' => $listeTypesLicence));
	}

	/**
    * @Security("has_role('ROLE_ADMIN')")
    */
	public function ajouterAction(Request $request)
	{
		/*$saison = new Saison();
		$form   = $this->get('form.factory')->create(SaisonType::class, $saison);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($saison);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Saison bien enregistrée.');

			return $this->redirectToRoute('aviron_club_saison_liste');
		}

		return $this->render('AvironSortieBundle:Saison:ajouter.html.twig', array(
				'form' => $form->createView(),
		));*/
	}

}