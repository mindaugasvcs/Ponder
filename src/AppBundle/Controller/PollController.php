<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Poll;
use AppBundle\Entity\PollOption;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Poll controller.
 */
class PollController extends Controller
{
    /**
     * Lists all poll entities.
     *
     * @Route("/", name="poll_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT p, o, a, v
                  FROM AppBundle:Poll p
                  INNER JOIN p.pollOptions o
                  INNER JOIN p.author a
                  LEFT JOIN o.votes v
                  WITH v.voterIp = :ip
                  ORDER BY p.createdAt DESC";
        $query = $em->createQuery($dql);
        $query->setParameter('ip', $request->getClientIp());

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // parameters to template
        return $this->render('poll/index.html.twig', [
            'pagination' => $pagination
            ]
        );
    }

    /**
     * Creates a new poll entity.
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/new", name="poll_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $poll = new Poll();
        $poll->setAuthor($this->getUser());
        $poll->getPollOptions()->add(new PollOption());
        $poll->getPollOptions()->add(new PollOption());

        $form = $this->createForm('AppBundle\Form\PollType', $poll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $poll = $form->getData();

            foreach ($poll->getPollOptions() as $index => $pollOption) {
                $pollOption->setSequence($index);
                $pollOption->setPoll($poll);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($poll);
            $em->flush();

            $this->addFlash('success', 'Poll created!');

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
     * @Route("/{id}", name="poll_show", requirements={"id" = "\d+"})
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
     * @Route("/{id}/edit", name="poll_edit", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Poll $poll)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if ($poll->getAuthor() !== $this->getUser()) {
                throw $this->createAccessDeniedException();
            }
        }

        $deleteForm = $this->createDeleteForm($poll);
        $editForm = $this->createForm('AppBundle\Form\PollType', $poll);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Poll updated!');

            return $this->redirectToRoute('poll_show', array('id' => $poll->getId()));
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
     * @Route("/{id}", name="poll_delete", requirements={"id" = "\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Poll $poll)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if ($poll->getAuthor() !== $this->getUser()) {
                throw $this->createAccessDeniedException();
            }
        }

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
        return $this->createFormBuilder(null, [
            'action' => $this->generateUrl('poll_delete', ['id' => $poll->getId()]),
            'method' => 'DELETE',
            'attr' => ['id' => 'item-deletion-form']
        ])
            ->getForm();
    }
}
