<?php
namespace App\Core;

class Paginator {
    private int $total;
    private int $perPage;
    private int $currentPage;
    private int $lastPage;
    private int $offset;

    public function __construct(int $total, int $perPage = 12, ?int $currentPage = null) {
        $this->total = $total;
        $this->perPage = max(1, $perPage);
        $this->currentPage = max(1, $currentPage ?? $this->getPageFromRequest());
        $this->lastPage = max(1, (int) ceil($total / $this->perPage));
        $this->currentPage = min($this->currentPage, $this->lastPage);
        $this->offset = ($this->currentPage - 1) * $this->perPage;
    }

    public function offset(): int {
        return $this->offset;
    }

    public function limit(): int {
        return $this->perPage;
    }

    public function total(): int {
        return $this->total;
    }

    public function perPage(): int {
        return $this->perPage;
    }

    public function currentPage(): int {
        return $this->currentPage;
    }

    public function lastPage(): int {
        return $this->lastPage;
    }

    public function hasPages(): bool {
        return $this->lastPage > 1;
    }

    public function hasPreviousPage(): bool {
        return $this->currentPage > 1;
    }

    public function hasNextPage(): bool {
        return $this->currentPage < $this->lastPage;
    }

    public function previousPage(): int {
        return max(1, $this->currentPage - 1);
    }

    public function nextPage(): int {
        return min($this->lastPage, $this->currentPage + 1);
    }

    public function pages(int $range = 2): array {
        $start = max(1, $this->currentPage - $range);
        $end = min($this->lastPage, $this->currentPage + $range);
        return range($start, $end);
    }

    public function url(int $page): string {
        $params = array_merge($_GET, ['page' => $page]);
        return '?' . http_build_query($params);
    }

    public function previousUrl(): string {
        return $this->url($this->previousPage());
    }

    public function nextUrl(): string {
        return $this->url($this->nextPage());
    }

    public function from(): int {
        return $this->total === 0 ? 0 : $this->offset + 1;
    }

    public function to(): int {
        return min($this->offset + $this->perPage, $this->total);
    }

    private function getPageFromRequest(): int {
        $page = $_GET['page'] ?? 1;
        return is_numeric($page) && $page > 0 ? (int) $page : 1;
    }
}