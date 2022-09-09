# Личный проект «TaskForce»

* Студент: [Владимир Казаков](https://up.htmlacademy.ru/yii/2/user/77211).
* Наставник: [Сергей Парфенов](https://up.htmlacademy.ru/yii/2/user/926645).

---

## О проекте
«TaskForce» — это онлайн площадка для поиска исполнителей на разовые задачи. Сайт функционирует как биржа объявлений,
где заказчики — физические лица публикуют задания. Исполнители могут откликаться на эти задания, предлагая свои услуги и
стоимость работ.

## Установка

1. Создать БД. В консоли MySQL ввести:

```mysql
CREATE DATABASE IF NOT EXISTS taskforce_77211
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
```

2. Склонировать репозиторий в каталог taskforce:

```shell
git clone git@github.com:bysynth/77211-task-force-2.git taskforce
```
3. Зайти в каталог taskforce и выполнить команды:

```shell
cd taskforce
composer install
make init
```
Для работы с проектом вам необходимо самостоятельно настроить веб-сервер.

После выполнения команды make init будут загружены все миграции БД, добавлены данные-фикстуры, настроен RBAC.

Для тестирования всего функционала проекта рекомендую создать минимум одного пользователя-заказчика и пользователя-исполнителя.
При регистрации пользователей рекомендую выбирать город "Воронеж" (ранее добавленные фейк-аккаунты из фикстур
генерировались с указанием этого города).

---

<a href="https://htmlacademy.ru/intensive/php2"><img align="left" width="50" height="50" alt="HTML Academy" src="https://up.htmlacademy.ru/static/img/intensive/yii/logo-for-github-2.png"></a>

Репозиторий создан для обучения на профессиональном онлайн‑курсе «[PHP, уровень 2](https://htmlacademy.ru/intensive/php2)» от [HTML Academy](https://htmlacademy.ru).
