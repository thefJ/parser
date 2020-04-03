<?php
declare(strict_types = 1);

namespace App\Twig;

use App\Services\Paginator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Match specific twig logic
 */
class PaginationExtension extends AbstractExtension
{
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pagination', [$this, 'pagination'], ['is_safe' => ['html'], 'needs_environment' => true])
        ];
    }

    /**
     * @param Environment $twig
     * @param Paginator   $paginator
     * @param int         $count
     *
     * @return string
     */
    public function pagination(Environment $twig, Paginator $paginator, int $count): string
    {
        $pages = [];
        $routeParameters = $paginator->getRouteParameters();
        for ($i = 1; $i <= $paginator->getPagesCount($count); $i++) {
            if ($i > 1) {
                $routeParameters = \array_merge($routeParameters, [Paginator::PAGE_PARAM_NAME => $i]);
            } else {
                unset($routeParameters[Paginator::PAGE_PARAM_NAME]);
            }
            $url = $this->urlGenerator->generate(
                $paginator->getRoute(),
                $routeParameters
            );

            $pages[] = [
                'url'       => $url,
                'is_active' => $i === $paginator->getPage(),
                'number'    => $i
            ];
        }

        return $twig->render('/twig/pagination.html.twig', ['pages' => $pages]);
    }
}
