Интернет магазин материалов для защиты растений

## Структура проекта:
Http\
Cli\
Auth\
-- Shared\
---- Domain\
-- Command\
-- Query\
-- Domain\
---- Entity\
---- Repository\
---- Service\
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

## Основные технологии
`Backend:`
- PHP 8.2
- Symfony 6.3
- minio (файловое хранилище)
- Архитектурный подход CQRS + DDD

`Frontend:`
- Админка на базе Admin LTE через шаблоны твиг. Для каждого модуля своя админка, которая будет доступна по адресу `/<module-name>/adimn` н-р `/shop/admin`
- Фронтенд-часть которая смотрит наружу - Nuxt.js 