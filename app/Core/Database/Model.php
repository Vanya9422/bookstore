<?php

namespace App\Core\Database;

use App\Core\Application;
use App\Core\Contracts\DatabaseInterface;
use App\Core\Contracts\ModelInterface;
use App\Core\Contracts\PaginationInterface;
use App\Core\Contracts\RelationInterface;
use App\Core\Pagination\Paginator;
use DI\DependencyException;
use DI\NotFoundException;
use PDO;

/**
 * Базовый класс модели, предоставляющий общие методы для работы с базой данных.
 */
class Model implements ModelInterface, RelationInterface {

    /**
     * По умолчанию выбираются все поля
     * @var string
     */
    protected string $selectedFields = "*";

    /**
     * @var ?PDO Соединение с базой данных
     */
    protected ?PDO $connection;

    /**
     * @var string Название таблицы в базе данных
     */
    protected static string $table;

    /**
     * @var array Массив содержащий строки JOIN запросов.
     */
    protected array $joins = [];

    /**
     * @var array Массив названий связей, для которых необходимо подсчитать количество связанных записей.
     */
    protected array $relationsCounts = [];

    /**
     * @var array Массив связей для загрузки с результатами
     */
    protected array $relations = [];

    /**
     * @var array Условия для WHERE запроса
     */
    protected array $whereConditions = [];

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
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
     * Устанавливает поля для выборки.
     *
     * @param array|string $fields
     * @return $this
     */
    public function select(array|string $fields): static {
        if (is_array($fields)) {
            $this->selectedFields = implode(", ", $fields);
        } else {
            $this->selectedFields = $fields;
        }

        return $this;
    }

    /**
     * Реализует пагинацию для результатов запроса,
     * включая подсчет связанных записей через колбэк.
     *
     * @param int $perPage
     * @param int $currentPage
     * @return PaginationInterface
     */
    public function paginate(int $perPage = 1, int $currentPage = 1): PaginationInterface {
        $table = self::getTable();

        // Основной запрос для подсчета общего количества записей (без учета фильтров и JOIN'ов)
        $totalQuery = $this->connection->prepare("SELECT COUNT(*) FROM {$table}");
        $totalQuery->execute();
        $totalResults = $totalQuery->fetchColumn();

        $totalPages = ceil($totalResults / $perPage);
        $offset = ($currentPage - 1) * $perPage;

        // Инициализируем строку SELECT с учетом выбранных полей
        $selectSql = $this->selectedFields !== "*" ? "SELECT {$this->selectedFields}" : "SELECT {$table}.*";

        // Обрабатываем подзапросы для подсчета связанных записей, если они есть
        $subQueries = [];
        foreach ($this->relationsCounts as $relation => $callback) {
            $subQuerySql = call_user_func($callback);
            if ($subQuerySql) {
                $subQueries[] = "($subQuerySql) AS {$relation}_count";
            }
        }

        if (!empty($subQueries)) {
            $selectSql .= ", " . implode(', ', $subQueries);
        }

        // Объединяем части SQL запроса
        $joinSql = implode(' ', $this->joins);
        $sql = "{$selectSql} FROM {$table} {$joinSql} LIMIT :perPage OFFSET :offset";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Загружаем связи, если они указаны
        if (!empty($this->relations) && !empty($results)) {
            $results = $this->loadRelations($results);
        }

        return new Paginator([
            'data' => $results,
            'total' => $totalResults,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
        ]);
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
     * Указывает связь, для которой нужно подсчитать количество записей, и опционально принимает
     * функцию обратного вызова для кастомного SQL запроса.
     * @param callable $callback Функция обратного вызова, возвращающая SQL подзапрос.
     * @param string $relation
     * @return $this
     */
    public function withCount(string $relation, callable $callback): static {
        $this->relationsCounts[$relation] = $callback ?: $relation;
        return $this;
    }

    /**
     * Добавляет JOIN к запросу.
     *
     * @param string $table Таблица для JOIN.
     * @param string $first Поле в основной таблице.
     * @param string $operator Оператор сравнения.
     * @param string $second Поле в присоединяемой таблице.
     * @return $this
     */
    public function join(string $table, string $first, string $operator, string $second): static {
        $this->joins[] = "JOIN $table ON $first $operator $second";
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
     * @throws \Exception
     */
    public function find($id): ?static {
        try {
            $stmt = $this->executeQuery("SELECT * FROM ". self::getTable() ." WHERE id = :id", ['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) return null;

            $this->setAttributes($result);

            if (!empty($this->relations)) {
                $result = $this->loadRelations($result);

                $this->setAttributes($result);
            }

            return $this;
        } catch (\PDOException $e) {
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
    }

    /**
     * Получает все записи из таблицы модели.
     *
     * @return array
     * @throws \Exception
     */
    public function all(): array {
        try {
            $table = self::getTable();
            $stmt = $this->executeQuery("SELECT * FROM {$table}");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($this->relations) && !empty($results)) {
                $results = $this->loadRelations($results);
            }

            return $results;
        } catch (\PDOException $e) {
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
    }

    /**
     * Создает новую запись в таблице модели.
     *
     * @param array $data
     * @return ?Model
     * @throws \Exception
     */
    public function create(array $data): ?static
    {
        try {
            $table = self::getTable();
            $keys = array_keys($data);
            $fields = implode(', ', $keys);
            $placeholders = ':' . implode(', :', $keys);

            $this->executeQuery("INSERT INTO $table ($fields) VALUES ($placeholders)", $data);

            $id = $this->connection->lastInsertId();

            return $this->find($id);
        } catch (\PDOException $e) {
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
    }

    /**
     * Обновляет запись в таблице модели по идентификатору.
     *
     * @param mixed $id
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function update(mixed $id, array $data): mixed {
        try {
            if (!$item = $this->find($id)) return false;

            $table = self::getTable();
            $setPart = implode(', ', array_map(function ($field) {
                return "$field = :$field";
            }, array_keys($data)));

            $data['id'] = $id;

            $this->executeQuery("UPDATE $table SET $setPart WHERE id = :id", $data);

            return $item;
        } catch (\PDOException $e) {
            // Логирование ошибки или выброс другого исключения с более понятным сообщением
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
    }

    /**
     * Удаляет запись из таблицы модели по идентификатору.
     *
     * @param mixed $id
     * @return bool
     * @throws \Exception
     */
    public function delete($id): bool {
        try {
            $table = self::getTable();

            if (!$this->find($id)) return false;

            return $this->connection
                ->prepare("DELETE FROM $table WHERE id = :id")
                ->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Логирование ошибки или выброс другого исключения с более понятным сообщением
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
    }

    /**
     * Получает первую запись, соответствующую условиям выборки.
     *
     * @return ?Model
     * @throws \Exception
     */
    public function first(): ?Model {
        try {
            $stmt = $this->prepareSelectQuery("LIMIT 1");
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) return null;

            $this->setAttributes($data);

            if (!empty($this->relations)) {
                $this->loadRelations($this);
            }

            $this->resetConditions();

            return $this;
        } catch (\PDOException $e) {
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
    }

    /**
     * Определяет обратное отношение "один к многим" между моделями.
     * @param string $relatedClass
     * @param string $foreignKey
     * @param array $ids
     * @return array
     * @throws \Exception
     */
    public function hasMany(string $relatedClass, string $foreignKey, array $ids): array {
        try {
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
        } catch (\PDOException $e) {
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
    }

    /**
     * Определяет обратное отношение "многие к одному" между моделями.
     * @param string $relatedClass
     * @param string $foreignKey
     * @param string $ownerKey
     * @return ?object
     * @throws \Exception
     */
    public function belongsTo(string $relatedClass, string $foreignKey, string $ownerKey = 'id'): ?object {
        try {
            $foreignKeyValue = $this->{$foreignKey};

            $stmt = $this->executeQuery(
                "SELECT * FROM {$relatedClass::getTable()} WHERE $ownerKey = :ownerKey LIMIT 1",
                ['ownerKey' => $foreignKeyValue]
            );

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) return null;

            $relatedModel = new $relatedClass();

            return $relatedModel->setAttributes($data);
        } catch (\PDOException $e) {
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
    }

    /**
     * @param string $sql
     * @param array $params
     * @return \PDOStatement|null
     * @throws \Exception
     */
    protected function executeQuery(string $sql, array $params = []): ?\PDOStatement {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            return $stmt;
        } catch (\PDOException $e) {
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
    }

    /**
     * @param string $additionalConditions
     * @return \PDOStatement
     * @throws \Exception
     */
    protected function prepareSelectQuery(string $additionalConditions = ""): \PDOStatement {
        try {
            $table = self::getTable();
            $conditions = implode(' AND ', array_map(fn($cond) => "{$cond['column']} {$cond['operator']} ?", $this->whereConditions));
            $query = "SELECT * FROM ". $table . ($conditions ? " WHERE $conditions" : '') . " $additionalConditions";
            return $this->executeQuery($query, $this->buildBindings());
        } catch (\PDOException $e) {
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
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
     * @throws \Exception
     */
    public function loadRelations(array|object $results): array|object {
        try {
            if (is_object($results)) {
                foreach ($this->relations as $relation) {
                    if (method_exists($this, $relation)) {
                        $results->{$relation} = $this->$relation();
                    }
                }

                return $results;
            }

            $ids = isset($results['id']) ? [$results['id']] : array_column($results, 'id');

            foreach ($this->relations as $relation) {
                if (method_exists($this, $relation)) {

                    if (!str_ends_with($relation, 's')) {
                        $results[$relation] = $this->$relation();
                    } else {
                        $relatedResults = $this->$relation($ids);

                        if (!isset($results[0])) {
                            $results[$relation] = $relatedResults[$results['id']] ?? [];
                        } else {
                            foreach ($results as &$result) {
                                $result[$relation] = $relatedResults[$result['id']] ?? [];
                            }
                        }
                    }
                }
            }

            return $results;
        } catch (\PDOException $e) {
            throw new \Exception("Произошла ошибка при выполнении запроса: " . $e->getMessage());
        }
    }

    public function resetConditions(): void {
        $this->whereConditions = [];
    }
}
