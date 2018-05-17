<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 10/05/2018
 * Time: 00:14
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/marsupilami")
 */
class MarsupilamiController extends Controller
{
    /**
     * @Route()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository(User::class);//recupére la table user
        $qb = $table->findAll();//tout la table (provoque une requete mysql

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb, /* query NOT result */
            max(1, $request->query->getInt('page', 1))/*page number*/,
            9/*limit per page*/
        );


        return $this->render('marsupilami/index.html.twig',[
            'pagination' => $pagination,
        ]);


    }
    /**
     * @Route("/voir")
     */
    public function viewAction()
    {
		
		$marsupilamis = $this->getUser();
		$friends = $marsupilamis->getFriends();

        return $this->render('marsupilami/view.html.twig',[
            'marsupilami' => $marsupilamis,
			'friends' => $friends,
        ]);
    }

    /**
     * @Route("/ajouter/{id}", requirements={"id":"\d+"})
     */
    public function addAction(Request $request, User $marsupilami,$id)
    {
      $token = $request->query->get('_token');

        if ($token === null) {
            throw $this->createAccessDeniedException('token not found');
        }

        if (!$this->isCsrfTokenValid('FRIEND_TOKEN', $token)) {
            throw $this->createAccessDeniedException('token invalid');
        }

        $this->getUser()->addFriends($marsupilami);
        $marsupilami->addFriends($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success', 'L\'ami' .' '.$marsupilami->getUsername().' '.'a bien été ajouté.');

        return $this->redirectToRoute('app_marsupilami_view',[ 'id' => $this->getUser()->getId()]);
    }

    /**
     * @Route("/modifier")
     */
    public function updateAction(Request $request)
    {
        $marsupilami = $this->getUser();
        $form = $this->createForm(UserType::class, $marsupilami);//instancier le formulaire
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //envoyer à la base
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success',"Il a bien modifié ces informations ");//envoyé le message au visiteur
            return $this->redirectToRoute('app_marsupilami_view',['id' => $marsupilami->getId()]);
        }
        return $this->render("marsupilami/edit.html.twig",[
            'form' => $form->createView(),
            'marsupilami' =>$marsupilami,
        ]);

    }

    /**
     * @Route("/supprimer/{id}",requirements={"id":"\d+"})
     */
    public function deleteAction(Request $request, User $marsupilami, $id)
    {
        $token = $request->query->get('_token');
        if(null === $token){
            throw $this->createAccessDeniedException();
        }
        if(!$this->isCsrfTokenValid('FRIEND_TOKEN', $token))
        {
            throw $this->createAccessDeniedException();
        }

        //envoyer à la base

         $this->getUser()->removeFriends($marsupilami);
        $marsupilami->removeFriends($this->getUser());
        $em = $this->getDoctrine()->getManager();
		/*$table = $em->getRepository(User::class);
		$friends = $table->find($id)*/
        $em->flush();
        $this->addFlash('success',"L'ami ".$marsupilami->getUsername(). ' a été bien supprimer ');//envoyé le message au visiteur
        return $this->redirectToRoute('app_marsupilami_view',[ 'id' => $marsupilami->getId()]);
    }


}