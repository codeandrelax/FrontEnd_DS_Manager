<?php

namespace App\Services;

class PaginationService
{
    private int $defaultItemsPerPage;
    private int $maxItemsPerPage;

    public function __construct(int $defaultItemsPerPage = 10, int $maxItemsPerPage = 100)
    {
        $this->defaultItemsPerPage = $defaultItemsPerPage;
        $this->maxItemsPerPage = $maxItemsPerPage;
    }

    /**
     * Validates and calculates pagination values.
     */
    public function paginate(int $page, int $limit): array
    {
        $page = max($page, 1); // Minimum page is 1
        $limit = min(max($limit, 1), $this->maxItemsPerPage); // Clamp limit to allowed range

        $offset = ($page - 1) * $limit;

        return [
            'page' => $page,
            'limit' => $limit,
            'offset' => $offset,
        ];
    }

    /**
     * Generates pagination metadata.
     */
    public function generateMeta(int $totalItems, int $currentPage, int $itemsPerPage): array
    {
        $totalPages = (int) ceil($totalItems / $itemsPerPage);

        return [
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $currentPage,
            'items_per_page' => $itemsPerPage,
        ];
    }

    public function getDefaultItemsPerPage(): int
    {
        return $this->defaultItemsPerPage;
    }
}
