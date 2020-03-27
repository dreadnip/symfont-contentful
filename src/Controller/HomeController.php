<?php

namespace App\Controller;

use App\Service\ContentfulClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="test")
     */
    public function __invoke(ContentfulClient $client)
    {
        $pages = $client->getPages();

        return $this->render(
            'base.html.twig',
            [
                'pages' => $pages,
            ]
        );
    }
}