<?php

require_once 'src/Task.php';

$strategy1 = new Task(1, 2);

var_dump(assert($strategy1->getNextStatus(Task::ACTION_START) === Task::STATUS_PROCESSING));
var_dump(assert($strategy1->getNextStatus(Task::ACTION_CANCEL) === Task::STATUS_CANCELED));
var_dump(assert($strategy1->getNextStatus(Task::ACTION_RESPOND) === Task::STATUS_NEW));
var_dump(assert($strategy1->getNextStatus(Task::ACTION_DONE) === Task::STATUS_DONE));
var_dump(assert($strategy1->getNextStatus(Task::ACTION_REFUSE) === Task::STATUS_FAILED));

$strategy2 = new Task(1, 2);
// currentUserId = Заказчик - статус задания "Новый", исполнитель назначен
var_dump(assert($strategy2->getAvailableAction(1) === [[Task::ACTION_START, Task::ACTION_CANCEL]]));
// currentUserId = Исполнитель - статус задания "Новый", исполнитель назначен
var_dump(assert($strategy2->getAvailableAction(2) === [Task::ACTION_RESPOND]));

$strategy3 = new Task(1, 2, Task::STATUS_PROCESSING);
// currentUserId = Заказчик, статус задания "В работе", исполнитель назначен
var_dump(assert($strategy3->getAvailableAction(1) === [Task::ACTION_DONE]));
// currentUserId = Исполнитель, статус задания "В работе", исполнитель назначен
var_dump(assert($strategy3->getAvailableAction(2) === [Task::ACTION_REFUSE]));
