<?php

namespace App\Controller;

use App\Entity\RbkProduct;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class RbkController
 *
 * @Route("/rbk")
 *
 * @package App\Controller
 */
class RbkController extends AbstractController
{

    /**
     * @Route("/news")
     * @Template
     *
     * @return array
     */
    public function newsAction(): array
    {
        $news = $this->getDoctrine()->getManager()
            ->getRepository(RbkProduct::class)->findAll();

        return ['news' => $news];
    }

    /**
     * @Route("/post/{id}", requirements={"id"="\d+"}, defaults={ "id": "0" })
     * @Template
     *
     * @param string $id Post id.
     *
     * @return array
     */
    public function postAction(string $id): array
    {
        $post = $this->getDoctrine()->getManager()
            ->getRepository(RbkProduct::class)->find($id);

        if ($post === null) {
            throw $this->createNotFoundException(
                sprintf('Post with id %s not found', $id)
            );
        }

        return ['post' => $post];
    }
}