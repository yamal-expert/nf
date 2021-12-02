## Завод Новостей

![](https://github.com/yamal-expert/nf/blob/main/fn/template/images/logo200.png?raw=true)

### Что это?
Web-версия системы коллективной работы.

#### Возможности
- Только просмотр. Возможность редактирования не реализована;
- Вывод последних 200 выпусков;
- Поиск по ключевым словам за выбранный диапазон дат;
- Работа через Веб-браузер на любом устройстве;
- При необходимости наличие сети Интернет не требуется - нужно только удалить импорт шрифта с сервиса Google Fonts;

По всем вопросам, в т.ч. по добавлению функционала можно писать на электронную почту [expert@yamal.expert](mailto:expert@yamal.expert)

#### Нужно сделать (TODO)
1. Редактирование своих материалов.
2. Редактирование и одобрение чужих материалов (от имени редактора).
3. Изменить принцип хранения кукисов авторизации, добавить чекбок «запомнить меня».
4. Сохранение и печать блоков.
5. Добавить выдачу в разных структурах.
6. Удаление временных данных сессии поиска.
7. Добавить диалоговые окна сообщений вместо системных.
8. Добавить блок с навигацией по страницам и выбор нужной даты новостного выпуска.
9. Добавить меню выбора программ.

#### Зависимости
1. Windows или Linux.
2. [Apache HTTP Server](https://httpd.apache.org/).
2. [PHP 7](https://www.php.net/) и выше.
3. [Драйвер Майкрософт для PHP для SQL Server](https://docs.microsoft.com/ru-ru/sql/connect/php/download-drivers-php-sql-server?view=sql-server-ver15).
3. MSSQL с базой данных новостей (тестировалось на MS SQL 2008 R2).

#### Установка
1. Установить связку Apache+PHP+модуль sqlsrv.
2. Разместить папку nf в корне http-сервера.
3. В файле inc/config.php указать данные авторизации на SQL-сервере.

#### Используемые компоненты
- https://tabler.io/
- https://jquery.com/
- https://fonts.google.com/

#### Примечание
*Выпускать в Интернет не желательно. Аудит безопасности не проводился.*