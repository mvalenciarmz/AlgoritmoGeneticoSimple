<?php

require_once 'citizen.php';

class Population{
	
	const MAX_POPULATION = 50;
	private $population = array();
	private $bestCitizens = array();
	private $objective = '';
	
	public function __construct(){
		$this->init();
	}
	
	private function init(){
		for($x=0; $x<self::MAX_POPULATION; $x++){
			$this->population[$x] = new Citizen();
		}
		
		$this->bestCitizens[0] = new Citizen();
    $this->bestCitizens[0]->setFitness(50);
	}
	
	public function calculateFitness(){
		for($x=0; $x<self::MAX_POPULATION; $x++){
			$this->population[$x]->calculateFitness($this->objective);
		}
	} 
	
	public function setObjective($objective = NULL){
		try{
			if(is_null($objective)){
				throw new Exception('Error, no se ha especificado objetivo');
			}
			$this->objective = $objective;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	// Aquí creamos todos los arrays de la población
	public function startUp(){
		
		echo "<table class='table table-striped table-bordered table-hover'>";
		echo "<thead><tr>";
        echo "<th class = 'danger'>CROMOSOMA</th>";
        echo "<th class = 'danger'>GENES</th>";
        echo "</tr></thead><tbody>";
		
		for($x=0; $x<self::MAX_POPULATION; $x++){
			echo "<tr><td>Cromosoma " . ( $x +1 ) . "</td><td>";
			$this->population[$x]->setData($this->randomData(strlen($this->objective)));
		}
		echo "</tbody></table><br /><br />";
	}
	
	// Básicamente, regresamos una cadena de caracteres aleatorios, de la longitud deseada
	// Para éste ejercicio, hemos limitado el contenido de la cadena a Unos y Ceros
	private function randomData($length){
		$temp = '';
		// Generamos una cadena aleatoria de ceros y unos... de la longitud solicitada
		for($x=0;$x<$length;$x++){
			//$temp .= chr(mt_rand(32, 126));
			$temp .= (string)mt_rand(0, 1);
		}
		echo $temp . "</td></tr>";
		return $temp;
	}

	// Simplemente para ordenar dos caracteres
	private function cmp($a, $b){
		$a = $a->getFitness();
		$b = $b->getFitness();
		
	    if ($a == $b) {
	        return 0;
	    }
	    return ($a < $b) ? -1 : 1;
	}
	
	public function assignBestCitizens(){
		
		// Ordenamos
		usort($this->population, array($this,"cmp"));
		
		$this->bestCitizens[0]->setData($this->population[0]->getData());
		$this->bestCitizens[0]->setFitness($this->population[0]->getFitness());
		
	}
	
	public function getBestCitizens(){
		return $this->bestCitizens;
	}
	
	public function reproduce(){
		$best = $this->population[0]->getData();
		for($x=1; $x<self::MAX_POPULATION; $x++){
			$temp = $this->population[$x]->getData();
			for($y=0;$y<strlen($this->objective);$y++){
				if(mt_rand(0,1)){
				  $temp[$y] = $best[$y];
				}
			}
			$this->population[$x]->setData($temp);
		}
	}
	
	public function mutate(){
		for($x=0; $x<self::MAX_POPULATION; $x++){
			$data = $this->population[$x]->getData();
			for($y=0;$y<strlen($this->objective);$y++){
			  //if(mt_rand()%100<4){
			  // Para éste ejercicio, usaremos una probabilidad de mutación de 0.0555
			  if(mt_rand()%100<0.0555){
			    //$data[$y] = chr(mt_rand(32, 126));
				$data[$y] = (string)mt_rand(0, 1);
				
			  }
			}
			
			$this->population[$x]->setData($data);
		}
	}
}

?>