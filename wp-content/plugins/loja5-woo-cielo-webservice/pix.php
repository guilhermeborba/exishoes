<?php
include_once(dirname(__FILE__)."/qrcode.php");
$pix = trim($_GET['codigo']);
QRCode::png($pix, null,'M',5);
header("Content-Type: image/png");
