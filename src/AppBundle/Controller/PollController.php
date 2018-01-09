<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Poll;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Poll controller.
 *
 * @Route("poll")
 */
class PollController extends Controller
{
    /**
     * Lists all poll entities.
     *
     * @Route("/", name="poll_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $polls = $em->getRepository('AppBundle:Poll')->findAll();

        return $this->render('poll/index.html.twig', array(
            'polls' => $polls,
        ));
    }

    /**
     * Creates a new poll entity.
     *
     * @Route("/new", name="poll_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $poll = new Poll();
        $form = $this->createForm('AppBundle\Form\PollType', $poll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($poll);
            $em->flush();

            return $this->redirectToRoute('poll_show', array('id' => $poll->getId()));
        }

        return $this->render('poll/new.html.twig', array(
            'poll' => $poll,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a poll entity.
     *
     * @Route("/{id}", name="poll_show")
     * @Method("GET")
     */
    public function showAction(Poll $poll)
    {
        $deleteForm = $this->createDeleteForm($poll);

        return $this->render('poll/show.html.twig', array(
            'poll' => $poll,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing poll entity.
     *
     * @Route("/{id}/edit", name="poll_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Poll $poll)
    {
        $deleteForm = $this->createDeleteForm($poll);
        $editForm = $this->createForm('AppBundle\Form\PollType', $poll);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('poll_edit', array('id' => $poll->getId()));
        }

        return $this->render('poll/edit.html.twig', array(
            'poll' => $poll,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a poll entity.
     *
     * @Route("/{id}", name="poll_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Poll $poll)
    {
        $form = $this->createDeleteForm($poll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($poll);
            $em->flush();
        }

        return $this->redirectToRoute('poll_index');
    }

    /**
     * Creates a form to delete a poll entity.
     *
     * @param Poll $poll The poll entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Poll $poll)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('poll_delete', array('id' => $poll->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
