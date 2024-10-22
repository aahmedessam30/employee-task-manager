<?php

namespace Core\Pagination;

class Paginator
{
    protected int $perPage;
    protected int $currentPage;
    protected int $total;
    protected int $lastPage;
    protected int $from;
    protected int $to;
    protected array $links = [];
    protected array $items;

    public function __construct(int $perPage, int $currentPage, int $total, array $items)
    {
        $this->perPage     = $perPage;
        $this->currentPage = $currentPage;
        $this->total       = $total;
        $this->lastPage    = $this->lastPage();
        $this->from        = $this->from();
        $this->to          = $this->to();
        $this->links       = $this->links();
        $this->items       = $items;
    }

    public function items(): array
    {
        return $this->items;
    }

    protected function lastPage(): int
    {
        return (int)ceil($this->total / $this->perPage);
    }

    protected function from(): int
    {
        return ($this->perPage * $this->currentPage) - $this->perPage + 1;
    }

    protected function to(): int
    {
        return min($this->total, $this->perPage * $this->currentPage);
    }

    protected function links(): array
    {
        $links = [];

        for ($i = 1; $i <= $this->lastPage; $i++) {
            $links[] = [
                'page'   => $i,
                'url'    => url() . '?page=' . $i,
                'active' => $i === $this->currentPage,
            ];
        }

        return $links;
    }

    public function toArray(): array
    {
        return [
            'per_page'     => $this->perPage,
            'current_page' => $this->currentPage,
            'total'        => $this->total,
            'last_page'    => $this->lastPage,
            'from'         => $this->from,
            'to'           => $this->to,
            'links'        => $this->links,
        ];
    }
}
