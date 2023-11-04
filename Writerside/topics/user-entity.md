# Сущность User

`User`

|    Название колонки     |     Тип      | nullable |             Назначение              |
|:-----------------------:|:------------:|:--------:|:-----------------------------------:|
|           id            |     UUID     |   нет    |     Идентификатор пользователя      |
|       first_name        | varchar(255) |   нет    |                 Имя                 |
|        last_name        | varchar(255) |    да    |               Фамилия               |
|          email          | varchar(255) |   нет    |                Почта                |
|   email_confirm_token   | varchar(255) |    да    |   Подтвержение корректности email   |
|         status          | varchar(255) |   нет    |         Статус пользователя         |
|          role           | varchar(255) |   нет    |          Роль пользователя          |
|      password_hash      | varchar(255) |   нет    |             Хэш пароля              |
|  reset_password_token   | varchar(255) |    да    |         Токен сброса пароля         |
| password_token_expires  |  timestamp   |    да    | Время действия токена сброса пароля |
|        new_email        | varchar(255) |    да    |             Новый email             |
|       created_at        |  timestamp   |   нет    |            Дата создания            |
