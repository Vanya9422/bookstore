<?php

namespace App\Repository;

use App\Exceptions\Repository\RepositoryException;
use App\Repository\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Базовый класс репозитория, который определяет основные операции,
 * которые должны быть реализованы в наследуемых репозиториях.
 */
abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    /**
     * @throws RepositoryException
     */
    public function __construct() {
        $this->makeModel();
    }

    /**
     * Returns the current Model instance
     *
     * @return Model
     */
    public function getModel(): Model {
        return $this->model;
    }

    /**
     * Начинает новый запрос к базе данных, возвращая построитель запросов для модели.
     *
     * @return Builder
     */
    protected function startQuery(): Builder
    {
        return $this->getModel()->newQuery();
    }

    /**
     * Сбрасывает экземпляр модели, создавая новый экземпляр.
     * @return void
     * @throws RepositoryException
     */
    public function resetModel(): void
    {
        $this->makeModel();
    }

    /**
     * Возвращает имя класса модели.
     * Этот метод должен быть реализован в каждом конкретном репозитории.
     *
     * @return string Название класса модели.
     */
    abstract protected function getModelClass(): string;

    /**
     * Создает и возвращает экземпляр модели.
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel(): Model {
        $classModel = $this->getModelClass();

        $model = new $classModel();

        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->getModelClass()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model;
    }

    /**
     * Создает новый результат в базе данных.
     *
     * @param array $attributes Атрибуты для создания результата.
     */
    public function create(array $attributes): Model {
        return $this->startQuery()->create($attributes);
    }

    /**
     * Обновляет сущность в репозитории по её идентификатору.
     *
     * @param int|string $id Идентификатор сущности.
     * @param array $attributes Атрибуты для обновления.
     * @return Model
     * @throws RepositoryException
     */
    public function update(int|string $id, array $attributes): Model {
        $model = $this->find($id);
        if ($model) {
            $model->fill($attributes);
            $model->save();
            return $model;
        }

        throw new RepositoryException("Модель не найдена.");
    }

    /**
     * Находит сущность по идентификатору.
     *
     * @param int|string $id Идентификатор сущности.
     * @return Model|null
     */
    public function find(int|string $id): ?Model {
        return $this->startQuery()->find($id);
    }

    /**
     * Пагинация результатов запроса.
     *
     * @param int $perPage Количество результатов на страницу.
     * @param ?int $currentPage Номер текущей страницы, по умолчанию 1.
     * @param array $relations Массив связей, которые нужно подгрузить с результатами.
     *
     * @return array
     */
    public function paginate(int $perPage, ?int $currentPage = 1, array $relations = []): array {
        $query = $this->startQuery();

        if (!empty($relations)) {
            $query = $query->with($relations);
        }

        // Вычисляем общее количество записей в базе данных для данного запроса,
        // чтобы можно было определить общее количество страниц.
        $totalResults = $query->count();

        // Вычисляем смещение на основе номера текущей страницы и количества результатов на страницу.
        $offset = ($currentPage - 1) * $perPage;

        // Получаем подмножество результатов с учетом смещения и лимита.
        // Это обеспечивает пагинацию данных.
        $results = $query->skip($offset)->take($perPage)->get();

        // Определяем общее количество страниц пагинации.
        $totalPages = ceil($totalResults / $perPage);

        return [
            'data' => $results, // Массив с данными текущей страницы
            'total' => $totalResults, // Общее количество доступных результатов
            'per_page' => $perPage, // Количество результатов на страницу
            'current_page' => $currentPage, // Номер текущей страницы
            'total_pages' => $totalPages // Общее количество страниц
        ];
    }
}