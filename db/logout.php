<?php
session_start();
session_destroy();
header("Location: sign/sign-in.php");
exit;