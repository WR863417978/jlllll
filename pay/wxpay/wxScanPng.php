<?php
require_once "phpqrcode.php";
QRcode::png($_GET['url']);
?>