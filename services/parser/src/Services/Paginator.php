<?php
declare(strict_types = 1);

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

/**
 * Pagination for statement
 */
class Paginator
{
    /** @var string */
    public const PAGE_PARAM_NAME = 'page';

    private string $route;

    private array  $routeParameters;

    private int    $page;

    private int    $limit;

    /**
     * @param Request $request
     * @param int     $limit
     */
    public function __construct(Request $request, int $limit)
    {
        $this->route           = $request->attributes->get('_route');
        $this->routeParameters = $request->attributes->get('_route_params');
        $this->page            = (int) $request->query->get(self::PAGE_PARAM_NAME, 1);
        $this->limit           = $limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->page > 1 ? ($this->page - 1) * $this->limit : 0;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $itemsCount
     *
     * @return int
     */
    public function getPagesCount(int $itemsCount): int
    {
        return (int) \ceil($itemsCount / $this->limit);
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }
}
