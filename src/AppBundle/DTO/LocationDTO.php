<?php
declare(strict_types=1);

namespace AppBundle\DTO;

use AppBundle\Entity\Weather;

class LocationDTO
{
    /** @var Weather[] */
    private $items;

    /** @var int */
    private $count;

    /** @var int */
    private $page;

    /** @var int */
    private $pages;

    public function __construct(array $items, int $count, int $page, int $pages)
    {
        $this->items = $items;
        $this->count = $count;
        $this->page = $page;
        $this->pages = $pages;
    }

    /**
     * @return Weather[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Weather[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPages(): int
    {
        return $this->pages;
    }

    /**
     * @param int $pages
     */
    public function setPages(int $pages): void
    {
        $this->pages = $pages;
    }
}
