<?php
require_once 'HeaderMgr.php';
require_once 'MathDrillStats.php';
require_once 'MathDrillUI.php';
require_once 'MultiplicationQuestion.php';

$q = new MultiplicationQuestion();
$ttc = new MathDrillUI($q);
$ttc->TakeAction();