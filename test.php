<?php

require_once 'src/Task.php';

$strategy = new Task(1, 2);

var_dump(assert($strategy->getNextStatus(Task::ACTION_CANCEL) === Task::STATUS_CANCELED));
var_dump(assert($strategy->getNextStatus(Task::ACTION_RESPOND) === Task::STATUS_PROCESSING));
var_dump(assert($strategy->getNextStatus(Task::ACTION_DONE) === Task::STATUS_DONE));
var_dump(assert($strategy->getNextStatus(Task::ACTION_REFUSE) === Task::STATUS_FAILED));

var_dump(assert($strategy->getAvailableAction(Task::STATUS_NEW) === [Task::ACTION_CANCEL, Task::ACTION_RESPOND]));
var_dump(assert($strategy->getAvailableAction(Task::STATUS_PROCESSING) === [Task::ACTION_DONE, Task::ACTION_REFUSE]));
var_dump(assert($strategy->getAvailableAction(Task::STATUS_FAILED) === []));
var_dump(assert($strategy->getAvailableAction(Task::STATUS_DONE) === []));
var_dump(assert($strategy->getAvailableAction(Task::STATUS_CANCELED) === []));
