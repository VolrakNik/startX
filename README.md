
Чтобы проверить CSRF-атаку с помощью локального index.html или другого ресурса, необходимо установить параметры для куки командой:
```
session_set_cookie_params([
    'secure' => true,           // Отправлять только по HTTPS
    'samesite' => 'None'         // Защита от CSRF (Strict, Lax или None)
]);
```
или по умолчанию в php.ini поставить параметр samesite в значение Lax.

Также, стоит отключить CSRF-защиту, чтобы не было проверки на налчие CSRF-токена
```
CSRFMiddleware::csrfProtect();
```

Чтобы восстановить защиту, закомментируйте параметры для куки, которые указали ранее(а если значение прописано в php.ini, то поменяйте его там)
и сбросьте все сессии командой:
```
session_destroy();
setcookie(session_name(), '', time() - 3600, '/');
```
# Запуск приложения
Скопируй файл `.env.example` в `.env`

Разверни контейнеры: `docker compose up -d`

Затем следует зайти в консоль php-контейнера:
`docker exec -it bank_php sh`

Выполни `composer install`

И выполнить миграцию: `php app/migrations/migrate.php`

Данные для работы с БД можно найти в .env