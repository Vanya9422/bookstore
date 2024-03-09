<?php

namespace App\Core\Resources;

use App\Core\Contracts\JsonResourceInterface;
use App\Core\Contracts\PaginationInterface;
use App\Core\Pagination\Paginator;

abstract class JsonResource implements JsonResourceInterface {

    protected $resource;

    public function __construct($resource) {
        if (is_object($resource)) {
            $this->resource = (array)$resource;
        } else {
            $this->resource = $resource;
        }
    }

    abstract public function toArray(): array;
    public static function collection($resources): array {

        if ($resources instanceof PaginationInterface) {

            $items = array_map(fn($resource) => static::make($resource), $resources->getItems());

            return [
                'data' => $items,
                'current_page' => $resources->getCurrentPage(),
                'total_pages' => $resources->getTotalPages(),
                'per_page' => $resources->getPerPage(),
                'total' => $resources->getTotalItems(),
            ];
        } else {
            return array_map(fn($resource) => static::make($resource), $resources);
        }
    }

    /**
     * @param $resource
     * @return array
     */
    public static function make($resource): array {
        $instance = new static($resource);

        return $instance->toArray();
    }
}