<?php
require_once 'HeaderMgr.php';
require_once 'TTC.php';
require_once 'TTCQuestion.php';

$q = new TTCQuestion();
$ttc = new TTC($q);
$ttc->TakeAction();