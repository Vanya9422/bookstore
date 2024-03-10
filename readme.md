# Проект книжного магазина на чистом PHP

## Описание
Этот проект представляет собой книжный магазин, реализованный с использованием чистого PHP. Основной акцент в разработке был сделан на применении лучших практик программирования и архитектурных подходов. Мы стремились создать чистую, модульную и масштабируемую систему, следуя принципам SOLID, DRY, KISS и YAGNI. Архитектура проекта построена на паттерне MVC (Model-View-Controller), что позволяет четко разграничивать бизнес-логику, работу с данными и пользовательский интерфейс.

Проект включает в себя как административную часть, так и публичную часть сайта. В административной части реализованы CRUD операции для управления сущностями авторов и книг. Публичная часть сайта позволяет пользователям просматривать список авторов и их книг. Кроме того, проект поддерживает выдачу данных в формате JSON через RESTful API, обеспечивая тем самым гибкость в интеграции с другими системами и сервисами.

## Особенности Реализации:

- Чистый PHP без использования фреймворков: Проект реализован с использованием только нативного PHP, без применения сторонних фреймворков, что обеспечивает высокую скорость работы и понимание базовых принципов программирования на PHP.
- Соблюдение принципов SOLID: Код написан с учетом принципов SOLID, что делает его легко поддерживаемым и расширяемым.
- Паттерн MVC: Проект строго следует паттерну MVC, что способствует разделению ответственности и упрощению разработки.
- Модульная архитектура: Каждая часть системы разработана как отдельный модуль, что позволяет легко добавлять новые функции и компоненты.
- Репозиторий паттерн: Для работы с базой данных используется паттерн репозитория, обеспечивающий абстракцию доступа к данным и упрощение тестирования.
- CRUD операции: Полное управление сущностями авторов и книг через административный интерфейс.
- RESTful API: Поддержка RESTful API для интеграции с другими сервисами и приложениями.
- Тестирование API: Включена коллекция Postman для удобства тестирования API.

## Запуск и Развертывание

Проект настроен для работы с Apache сервером и использует MySQL в качестве системы управления базами данных. Для запуска необходимо выполнить миграции базы данных и, при необходимости, заполнить базу начальными данными с помощью сидов. В документации проекта приведены подробные инструкции по настройке и запуску.

## Установка

```bash
# Клонирование репозитория

  git clone https://github.com/Vanya9422/bookstore.git && cd bookstore

# Установка зависимостей

  composer install

# Настройка переменных окружения

  cp .env.example .env

# Настройте параметры подключения к базе данных в .env

# Запуск миграций

  vendor/bin/phinx migrate

# Заполните базу данных начальными данными с помощью сидеров:

  vendor/bin/phinx seed:run -s DatabaseSeeder

# Или для запуска конкретных сидеров:

  vendor/bin/phinx seed:run -s RoleSeeder && vendor/bin/phinx seed:run -s AdminAndUserSeeder
```
## Контактная Информация
Если у вас возникнут вопросы или понадобится помощь, обращайтесь к [Вани](https://t.me/grigoryan366).
