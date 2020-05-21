<?php

if(!isset($_SESSION['idUser'])) {
    header('Location: /nopermission.php');
}