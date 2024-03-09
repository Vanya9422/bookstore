<?php

namespace App\Repository;

use App\Core\Contracts\PaginationInterface;
use App\Core\Database\Model;
use App\Exceptions\Repository\RepositoryException;
use App\Repository\Contracts\RepositoryInterface;

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
     * Создает новую запись в базе данных.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model {
        return $this->getModel()->create($attributes);
    }

    /**
     * Обновляет запись в базе данных по заданному идентификатору.
     *
     * @param int $id
     * @param array $attributes
     * @return Model
     */
    public function update(int $id, array $attributes): Model {
        return $this->getModel()->update($id, $attributes);
    }

    /**
     * Удаляет запись из базы данных по идентификатору.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        return $this->getModel()->delete($id);
    }

    /**
     * Находит запись по идентификатору, опционально загружая связи.
     * @param int $id
     * @param array $relations
     * @return Model|null
     */
    public function find(int $id, array $relations = []): ?Model {
        return $this->getModel()->with($relations)->find($id);
    }

    /**
     * Получение списка авторов с пагинацией.
     *
     * @param int $perPage Количество записей на страницу.
     * @param ?int $currentPage Текущая страница.
     * @param array $relations
     * @return PaginationInterface Результаты пагинации.
     */
    public function paginate(int $perPage, ?int $currentPage = 1, array $relations = []): PaginationInterface
    {
        return $this->getModel()->with($relations)->paginate($perPage, $currentPage);
    }

    /**
     * Получает все записи из модели.
     *
     * @return array
     */
    public function all(): array {
        return $this->getModel()->all();
    }
}