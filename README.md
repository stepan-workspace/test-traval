# test-traval

## Тестовое задание:

> На фронте есть форма расчёта стоимости путешествия.
>
> Реализуйте API для неё на Symfony (без API Platform и БД).
>
> Сделайте только endpoint с расчётом стоимости со скидкой по данным формы на фронте.
>
> Саму форму на фронте делать не нужно.
>
> Используйте актуальные версии ПО. Напишите тесты.
>
> Разместите код на github/gitlab.

## Окружение:
- **Linux** (Linux fedora 6.10.11-100.fc39.x86_64)
- **docker** (Docker version 27.3.1, build ce12230)
- **docker-compose** (docker-compose version 1.29.2, build unknown)

## Команды:

- Развёртывание и запуск
    ```bash
    $ docker-compose up --build -d
    ```

    ```bash
    $ docker exec test-traval-app composer update
    ```

    ```bash
    $ docker exec test-traval-app chown -R www-data:www-data var/
    ```

- Документация API

    [http://localhost:8080/api/doc](http://localhost:8080/api/doc)

- Запуск тестов

    ```bash
    $ docker exec test-traval-app bash -c 'php bin/phpunit'
    ```
