<?php

namespace App\Service\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Twig\Environment;

class FormHandler
{
    private Request $request;
    private FlashBagInterface $flash;

    public function __construct(
        private EntityManagerInterface $em,
        private FormFactoryInterface $form,
        private Environment $twig,
        RequestStack $requestStack,
    ) {
        $this->flash = $requestStack->getSession()->getFlashBag();
        $this->request = $requestStack->getCurrentRequest();
    }

    public function handle(string $formType, string $redirectLink, string $templatePath, ?object $entity = null): Response|RedirectResponse
    {
        $form = $this->form->create($formType, $entity);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->em->persist($data);
            $this->em->flush();

            if (!$entity) {
                $this->flash->add('success', 'Vos données ont été créées');
            } else {
                $this->flash->add('success', 'Vos données ont été modifiées');
            }

            return new RedirectResponse($redirectLink);
        }

        return new Response($this->twig->render($templatePath, [
            'entity' => $entity,
            'form' => $form->createView(),
            'form_errors' => $form->getErrors(),
        ]));
    }
}