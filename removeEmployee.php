<?php
session_start();
include './auth.php';
require_once './server/Database.php';

    if (!empty($_REQUEST['id'])) {
        $database->removeEmployee($_REQUEST['id']);
        header('Location: /employees.php');
    }
