<?php

namespace App\Core\Database;

use App\Core\Application;
use App\Core\Contracts\DatabaseInterface;
use App\Core\Contracts\ModelInterface;
use App\Core\Contracts\RelationInterface;
use PDO;

/**
 * Базовый класс модели, предоставляющий общие методы для работы с базой данных.
 */
class Model implements ModelInterface, RelationInterface {

    /**
     * @var ?PDO Соединение с базой данных
     */
    protected ?PDO $connection;

    /**
     * @var string Название таблицы в базе данных
     */
    protected static string $table;

    /**
     * @var array Массив связей для загрузки с результатами
     */
    protected array $relations = [];

    /**
     * @var array Условия для WHERE запроса
     */
    protected array $whereConditions = [];

    public function __construct() {
        $this->connection = Application::getContainer()?->get(DatabaseInterface::class)?->connect();
    }

    /**
     * Устанавливает соединение с базой данных.
     *
     * @param PDO $connection Новое соединение с базой данных
     */
    public function setConnection($connection): void {
        $this->connection = $connection;
    }

    /**
     * Реализует пагинацию для результатов запроса.
     *
     * @param int $perPage Количество записей на страницу
     * @param int $currentPage Номер текущей страницы
     * @return array Массив с данными пагинации
     */
    public function paginate(int $perPage = 1, int $currentPage = 1): array {
        $table = self::getTable();
        $totalQuery = $this->connection->prepare("SELECT COUNT(*) FROM {$table}");
        $totalQuery->execute();
        $totalResults = $totalQuery->fetchColumn();

        $totalPages = ceil($totalResults / $perPage);
        $offset = ($currentPage - 1) * $perPage;

        $stmt = $this->connection->prepare("SELECT * FROM {$table} LIMIT :perPage OFFSET :offset");
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($this->relations)) {
            $results = $this->loadRelations($results);
        }

        return [
            'data' => $results,
            'total' => $totalResults,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
        ];
    }

    /**
     * Указывает связи, которые должны быть загружены вместе с основными результатами.
     *
     * @param array|string $relations Связи для загрузки
     * @return $this
     */
    public function with(array|string $relations): static {
        if (is_string($relations)) {
            $relations = [$relations];
        }

        $this->relations = array_merge($this->relations, $relations);

        return $this;
    }

    /**
     * Добавляет условия для WHERE запроса.
     *
     * @param string $column Название колонки
     * @param mixed $operator Оператор или значение
     * @param mixed $value Значение
     * @return static
     */
    public function where(string $column, $operator = null, $value = null): static {
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->whereConditions[] = compact('column', 'operator', 'value');

        return $this;
    }

    /**
     * Находит запись по идентификатору.
     * @param $id
     * @return ?Model
     */
    public function find($id): ?static {
        $table = self::getTable();
        $stmt = $this->executeQuery("SELECT * FROM ". $table ." WHERE id = :id", ['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $this->setAttributes($result) : null;
    }

    /**
     * Создает новую запись в таблице модели.
     *
     * @param array $data
     * @return ?Model
     */
    public function create(array $data): ?static
    {
        $table = self::getTable();
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $this->executeQuery("INSERT INTO $table ($fields) VALUES ($placeholders)", $data);

        return $this->find($this->connection->lastInsertId());
    }

    /**
     * Обновляет запись в таблице модели по идентификатору.
     *
     * @param mixed $id
     * @param array $data
     * @return Model|null
     */
    public function update($id, array $data): ?static {
        $table = self::getTable();
        $setPart = implode(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($data)));
        $data['id'] = $id;

        $this->executeQuery("UPDATE $table SET $setPart WHERE id = :id", $data);

        return $this->find($id);
    }

    /**
     * Удаляет запись из таблицы модели по идентификатору.
     *
     * @param mixed $id
     * @return bool
     */
    public function delete($id): bool {
        $table = self::getTable();
        $stmt = $this->connection->prepare("DELETE FROM $table WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Получает первую запись, соответствующую условиям выборки.
     *
     * @return ?Model
     */
    public function first(): ?Model {
        $stmt = $this->prepareSelectQuery("LIMIT 1");
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        $this->setAttributes($data);

        if (!empty($this->relations)) {
            $this->loadRelations($this);
        }

        return $this;
    }

    /**
     * Определяет обратное отношение "один к многим" между моделями.
     * @param string $relatedClass
     * @param string $foreignKey
     * @param array $ids
     * @return array
     */
    public function hasMany(string $relatedClass, string $foreignKey, array $ids): array {
        // Подготовка списка плейсхолдеров для безопасного включения ID в запрос
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->executeQuery(
            "SELECT * FROM " . $relatedClass::getTable() . " WHERE $foreignKey IN ($placeholders)",
            $ids
        );

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

        $stmt = $this->executeQuery(
            "SELECT * FROM {$relatedClass::getTable()} WHERE $ownerKey = :ownerKey LIMIT 1",
            ['ownerKey' => $foreignKeyValue]
        );

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        $relatedModel = new $relatedClass();

        return $relatedModel->setAttributes($data);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return \PDOStatement|null
     */
    protected function executeQuery(string $sql, array $params = []): ?\PDOStatement {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function buildSelectQuery(): string {
        $table = self::getTable();
        $conditions = join(' AND ', array_map(function ($cond) { return "{$cond['column']} {$cond['operator']} ?"; }, $this->whereConditions));
        return sprintf("SELECT * FROM %s%s", $table, $conditions ? " WHERE $conditions" : '');
    }

    /**
     * @param string $additionalConditions
     * @return \PDOStatement
     */
    protected function prepareSelectQuery(string $additionalConditions = ""): \PDOStatement {
        $table = self::getTable();
        $conditions = implode(' AND ', array_map(fn($cond) => "{$cond['column']} {$cond['operator']} ?", $this->whereConditions));
        $query = "SELECT * FROM ". $table . ($conditions ? " WHERE $conditions" : '') . " $additionalConditions";
        return $this->executeQuery($query, $this->buildBindings());
    }

    protected function buildBindings(): array {
        return array_map(function ($cond) { return $cond['value']; }, $this->whereConditions);
    }

    /**
     * Возвращает название таблицы модели.
     * @return ?string
     */
    public static function getTable(): ?string
    {
        if (isset(static::$table)) {
            return static::$table;
        }

        // на основе имени класса, если свойство $table не задано
        $className = get_called_class();
        $array = explode('\\', $className);
        $classNameShort = end($array); // Получаем короткое имя класса
        return strtolower($classNameShort) . 's';
    }

    /**
     * Устанавливает атрибуты модели из массива.
     *
     * @param array $attributes Атрибуты для установки
     * @return static
     */
    public function setAttributes(array $attributes): static {
        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * Загружает указанные связи для результатов.
     *
     * @param array|object $results Результаты, для которых нужно загрузить связи
     * @return array|object Результаты с загруженными связями
     */
    public function loadRelations(array|object $results): array|object {
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
}
