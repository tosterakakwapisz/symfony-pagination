<?php

namespace App\Service;

class PaginationService
{
    private const ADDITIONAL_PAGES_NUMBER = 5;

    public static function getPaginationData(
        int $currentPage,
        int $totalPages,
        string $requestUri
    ) {
        $isOnFirstPage = 1 === $currentPage;
        $isOnLastPage = $currentPage === $totalPages;

        $isLeftSpace = abs($currentPage - 1) > self::ADDITIONAL_PAGES_NUMBER;
        $isRightSpace = abs($currentPage - $totalPages) > self::ADDITIONAL_PAGES_NUMBER;

        $leftPageNumber = $isOnFirstPage ? 1 : $currentPage - 1;
        $leftLink = self::generatePaginationLink($requestUri, $leftPageNumber);
        $rightPageNumber = $isOnLastPage ? $totalPages : $currentPage + 1;
        $rightLink = self::generatePaginationLink($requestUri, $rightPageNumber);

        $leftPages = self::generateLeftPages($currentPage, $requestUri);
        $rightPages = self::generateRightPages($currentPage, $totalPages, $requestUri);

        return [
            'left' => [
                'disabled' => $isOnFirstPage ? 'disabled' : '',
                'content' => '<',
                'link' => $leftLink,
            ],
            'firstPage' => $isOnFirstPage ? null : [
                'disabled' => $isOnFirstPage ? 'disabled' : '',
                'content' => '1',
                'link' => self::generatePaginationLink($requestUri, 1),
            ],
            'leftSpacer' => !$isLeftSpace ? null : [
                'content' => '...',
            ],
            'leftPages' => $leftPages,
            'current' => [
                'content' => $currentPage,
            ],
            'rightPages' => $rightPages,
            'rightSpacer' => !$isRightSpace ? null : [
                'content' => '...',
            ],
            'lastPage' => $isOnLastPage ? null : [
                'disabled' => $isOnLastPage ? 'disabled' : '',
                'content' => $totalPages,
                'link' => self::generatePaginationLink($requestUri, $totalPages),
            ],
            'right' => [
                'disabled' => $isOnLastPage ? 'disabled' : '',
                'content' => '>',
                'link' => $rightLink,
            ],
        ];
    }

    private static function generatePaginationLink(string $requestUri, int $page): string
    {
        $doesUriContainPage = 1 === preg_match('/page=\d+/', $requestUri);
        if (!$doesUriContainPage) {
            return str_contains($requestUri, '?')
                ? $requestUri.'&page='.$page
                : $requestUri.'?page='.$page;
        }

        return preg_replace('/page=\d+/', 'page='.$page, $requestUri);
    }

    private static function generateLeftPages(int $currentPage, string $requestUri): array
    {
        $pages = [];
        for ($i = $currentPage - self::ADDITIONAL_PAGES_NUMBER; $i < $currentPage; ++$i) {
            $pageNumber = $i;
            if ($pageNumber > 1) {
                $pages[] = [
                    'content' => $pageNumber,
                    'link' => self::generatePaginationLink($requestUri, $pageNumber),
                ];
            }
        }

        return $pages;
    }

    private static function generateRightPages(int $currentPage, int $totalPages, string $requestUri): array
    {
        $pages = [];
        for ($i = $currentPage + 1; $i <= $currentPage + self::ADDITIONAL_PAGES_NUMBER; ++$i) {
            $pageNumber = $i;
            if ($pageNumber < $totalPages) {
                $pages[] = [
                    'content' => $pageNumber,
                    'link' => self::generatePaginationLink($requestUri, $pageNumber),
                ];
            }
        }

        return $pages;
    }
}
