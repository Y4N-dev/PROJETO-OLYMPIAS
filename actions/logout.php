<?php
session_start();
session_destroy();
header('Location: /PROJETO-OLYMPIAS/index.php');
exit;
