<?php
session_start();
session_destroy(); // apaga todos os dados da sessão no servidor
header('Location: index.php');
exit;
?>