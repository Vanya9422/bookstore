<?php

namespace App\Core\Database;

use App\Core\Application;
use App\Core\Contracts\DatabaseInterface;
use App\Core\Contracts\ModelInterface;
use App\Core\Contracts\RelationInterface;
use PDO;

class Model implements ModelInterface, RelationInterface
{
    protected $connection;

    protected static string $table;

    protected array $relations = [];

    protected array $whereConditions = [];

    public function __construct() {
        $this->connection = Application::getContainer()?->get(DatabaseInterface::class)?->connect();
    }

    /**
     * Устанавливает объект соединения с базой данных.
     *
     * @param $connection
     * @return void
     */
    public function setConnection($connection): void {
        $this->connection = $connection;
    }

    /**
     * Реализация пагинации для модели.
     *
     * @param int $perPage Количество элементов на страницу.
     * @param int $currentPage Текущая страница.
     * @return array Возвращает массив с результатами пагинации.
     */
    public function paginate(int $perPage = 1, int $currentPage = 1): array {
        // Подсчет общего количества записей
        $table = self::getTable();
        $totalQuery = $this->connection->prepare("SELECT COUNT(*) FROM {$table}");
        $totalQuery->execute();
        $totalResults = $totalQuery->fetchColumn();

        // Вычисление необходимых значений для пагинации
        $totalPages = ceil($totalResults / $perPage);
        $offset = ($currentPage - 1) * $perPage;

        // Получение результатов для текущей страницы
        $stmt = $this->connection->prepare("SELECT * FROM {$table} LIMIT :perPage OFFSET :offset");
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Загрузка связей, если они были указаны
        if (!empty($this->relations)) {
            $results = $this->loadRelations($results);
        }

        return [
            'data' => $results,
            'total' => $totalResults,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total_pages' => $totalPages
        ];
    }

    /**
     * Добавляет связи для загрузки с основными результатами.
     *
     * @param array|string $relations
     * @return $this
     */
    public function with(array|string $relations): self {
        if (is_string($relations)) {
            $relations = [$relations];
        }

        $this->relations = array_merge($this->relations, $relations);

        return $this;
    }

    protected function loadRelations(array|object $results) {
        if (is_object($results)) {
            foreach ($this->relations as $relation) {
                if (method_exists($this, $relation)) {
                    $results->{$relation} = $this->$relation();
                }
            }

            return $results;
        }

        $ids = array_column($results, 'id');

        foreach ($this->relations as $relation) {
            if (method_exists($this, $relation)) {
                $relatedResults = $this->$relation($ids);
                foreach ($results as &$result) {
                    $result[$relation] = $relatedResults[$result['id']] ?? [];
                }
            }
        }

        return $results;
    }

    public function where(string $column, $operator = null, $value = null): static {
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->whereConditions[] = compact('column', 'operator', 'value');
        return $this;
    }

    public function first() {
        $table = self::getTable();
        $conditions = [];
        $bindings = [];

        foreach ($this->whereConditions as $condition) {
            $conditions[] = "{$condition['column']} {$condition['operator']} ?";
            $bindings[] = $condition['value'];
        }

        $conditionsStr = implode(' AND ', $conditions);
        $query = "SELECT * FROM {$table}" . ($conditionsStr ? " WHERE {$conditionsStr}" : '') . " LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->execute($bindings);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        $this->setAttributes($data);

        // Загрузка связей, если они были указаны
        if (!empty($this->relations)) {
            return $this->loadRelations($this);
        }

        return $this;
    }

    public function findAll(): array {
        $table = self::getTable();
        $stmt = $this->connection->query("SELECT * FROM $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $table = self::getTable();
        $stmt = $this->connection->prepare("SELECT * FROM {$table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $this->setAttributes($result) : null;
    }

    public function create(array $data)
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);
        $table = self::getTable();
        $stmt = $this->connection->prepare("INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})");
        $stmt->execute($data);

        return $this->find($this->connection->lastInsertId());
    }

    public function update($id, array $data) {
        $setPart = implode(', ', array_map(function ($field) {
            return "{$field} = :{$field}";
        }, array_keys($data)));
        $table = self::getTable();
        $stmt = $this->connection->prepare("UPDATE {$table} SET {$setPart} WHERE id = :id");
        $data['id'] = $id;
        $stmt->execute($data);

        return $this->find($id);
    }

    public function delete($id): bool {
        $table = self::getTable();
        $stmt = $this->connection->prepare("DELETE FROM {$table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function hasMany(string $relatedClass, string $foreignKey, array $ids): array {
        // Подготовка списка плейсхолдеров для безопасного включения ID в запрос
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "SELECT * FROM " . $relatedClass::getTable() . " WHERE {$foreignKey} IN ($placeholders)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute($ids);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Организация результатов по внешнему ключу
        $groupedResults = [];
        foreach ($results as $result) {
            $groupedResults[$result[$foreignKey]][] = $result;
        }
        return $groupedResults;
    }

    /**
     * Определяет обратное отношение "многие к одному" между моделями.
     * @param string $relatedClass
     * @param string $foreignKey
     * @param string $ownerKey
     * @return ?object
     */
    public function belongsTo(string $relatedClass, string $foreignKey, string $ownerKey = 'id'): ?object {
        $foreignKeyValue = $this->{$foreignKey};

        if (!$foreignKeyValue) {
            return null;
        }

        $stmt = $this->connection->prepare("SELECT * FROM {$relatedClass::getTable()} WHERE $ownerKey = :ownerKey LIMIT 1");
        $stmt->execute(['ownerKey' => $foreignKeyValue]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $relatedModel = new $relatedClass();

        return $relatedModel->setAttributes($data);
    }


    /**
     * Возвращает название таблицы модели.
     *
     * @return string|null Название таблицы в базе данных.
     */
    public static function getTable(): ?string
    {
        return static::$table;
    }

    public function setAttributes(array $attributes): static {
        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }
}