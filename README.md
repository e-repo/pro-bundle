Интернет магазин материалов для защиты растений

## BACKEND Структура проекта:
UI\
-- Http\
---- Auth\
---- Blog\
---- Shop\
-- Cli\
Auth\
-- Shared\
---- Domain\
-- User\
---- Command\
---- Query\
---- Domain\
------ Entity\
------ Repository\
------ Service\
Shop\
-- Shared\
---- Domain\
-- Product\
---- Command\
---- Query\
---- Domain\
------ Entity\
------ Repository\
------ Service\
-- Order\
---- Command\
---- Query\
---- Domain\
------ Entity\
------ Repository\
------ Service\
Infrastructure\
-- Blog\
-- Shop\
-- Shared

### Разбивка по слоям

`Presenter (UI):`
- Http
- Cli

`Application (помодульно):`
- Shop (название модуля)
- Command
- Query
- Shared

`Domain:`
- Domain
- Entity
- Repository
- Service
- Shared\Domain

`Infrastructure:`
- Shop
- Blog
- Shared

### Основные технологии
`Backend:`
- PHP 8.2
- Symfony 6.3
- minio (файловое хранилище)
- Архитектурный подход CQRS + DDD

### Список модулей сервиса:
- Auth
- Blog
- Shop

## FRONTEND
### Основные технологии
- Nuxt.js 
- Vue 3
- TS
- Админка на базе Admin LTE (доступна по адресу `admin.bunches`)