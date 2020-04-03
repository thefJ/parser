<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Services\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Shows log pages
 */
class LogController extends AbstractController
{
    /** @var int */
    private const LOG_LIMIT = 50;

    /**
     * @Route("/", name="index")
     *
     * @param Request $request
     * @param ClientRepository $clientRepository
     *
     * @return Response
     */
    public function index(Request $request, ClientRepository $clientRepository): Response
    {
        $paginator = new Paginator($request, self::LOG_LIMIT);
        $clients   = $clientRepository->getClientsVisitStatistic(
            $paginator->getOffset(),
            $paginator->getLimit()
        );
        $allClientsCount = $clientRepository->getClientsVisitStatisticCount();

        return $this->render(
            'log/index.html.twig',
            ['clients' => $clients, 'count' => $allClientsCount, 'paginator' => $paginator]
        );
    }
}
