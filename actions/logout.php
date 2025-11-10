<?php
session_start();
session_destroy();
header("Location: paginainicial.php?logout=1");
exit;
