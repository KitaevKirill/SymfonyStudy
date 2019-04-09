<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserFullType;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        $form = $this->createForm(UserType::class);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }



        return [ 'users' => $users, 'user_form' => $form->createView() ];
    }


    /**
     * @Route("/edit/{id}", name="edit")
     * @Template()
     */
    public function editAction($id, Request $request){

        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
//            $user->setEmail($request);
            $user = $form->getData();


            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return [ 'user' => $user, 'user_form' => $form->createView() ];
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->find($id);

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/feedback", name="feedback")
     */
    public function feedbackAction(){
        die('feedback here');
    }

    /**
     * @Route("/list", name="list")
     */
    public function listAction(){
        die('list here');
    }

    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
}
