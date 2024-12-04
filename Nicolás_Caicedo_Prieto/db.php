<?php

require_once __DIR__ . "/data-access/CalendarDataAccess.php";
require_once __DIR__ . "/entities/User.php";
require_once __DIR__ . "/entities/Event.php";

$dbFile = __DIR__ . "/data-access/calendario.db";
$calendarDataAccess = new CalendarDataAccess($dbFile);
