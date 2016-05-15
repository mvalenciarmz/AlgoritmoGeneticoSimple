<?php

ini_set('max_execution_time', 300); //300 seconds = 5 minutes

require_once './ga.php';

echo '
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
';

//CREAMOS UN NUEVO OBJETO GA
$ga = new GA(1);

//ESTABLECEMOS EL OBJETIVO
// El objetivo es llegar a una cadena de 30 números 1
$ga->setObjective(0, '111111111111111111111111111111');

//INICIALIZAMOS EL OBJETO
$ga->startUp(0);

//EMPEZAMOS LA EVOLUCIÓN
$bests = $ga->start(0);

?>
