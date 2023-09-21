# Содержание:

---
## Авторизация/Регистрация:

---
- [Структура сущности User](#user-structure)


### Структура сущности User <a name="user-structure"></a>

---
`User`

|    Название колонки     |     Тип      |             Назначение              |
|:-----------------------:|:------------:|:-----------------------------------:|
|           id            |     UUID     |     Идентификатор пользователя      |
|       first_name        | varchar(255) |                 Имя                 |
|        last_name        | varchar(255) |               Фамилия               |
|          email          | varchar(255) |                Почта                |
|   email_confirm_token   | varchar(255) |   Подтвержение корректности email   |
|         status          | varchar(255) |         Статус пользователя         |
|          role           | varchar(255) |          Роль пользователя          |
|      password_hash      | varchar(255) |             Хэш пароля              |
|  reset_password_token   | varchar(255) |         Токен сброса пароля         |
| password_token_expires  |  timestamp   | Время действия токена сброса пароля |
|        new_email        | varchar(255) |             Новый email             |
| new_email_confirm_token | varchar(255) |  Подтверждение корректности email   |
|       created_at        |  timestamp   |            Дата создания            |

