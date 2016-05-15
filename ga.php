<?php

require_once 'population.php';

class GA{

	private $banks = array();
	const MAX_GENERATIONS = 50000;
	const FITNESS_THRESHOLD = 0;

	public function __construct($banks = NULL)
	{
		try{
			if(is_null($banks)){
				throw new Exception('Error, no se ha especificado # de bancos');
			}
			$this->init($banks);
		}catch(Exception $e){
			die($e->getMessage());
		}		
	}
	
	private function init($banks)
	{
		for($x=0; $x<$banks; $x++){
			$this->banks[$x] = new Population();
		}
	}
	
	public function getBank($bank = NULL){
		try{
			if(is_null($bank)){
				throw new Exception('Error, falta especificar banco');
			}
			return $this->banks[$bank];
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	public function getBestCandidates($bank = NULL){
		try{
			if(is_null($bank)){
				throw new Exception('Error, falta especificar banco');
			}
			return $this->banks[$bank]->getBestCitizens();
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	public function setObjective($bank = NULL, $objective = NULL){
		try{
			if(is_null($bank) || is_null($objective)){
				throw new Exception('Error, falta especificar banco u objetivo');
			}
			$this->banks[$bank]->setObjective($objective);
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	public function startUp($bank = NULL)
	{
		try{
			if(is_null($bank)){
				throw new Exception('Error, falta especificar banco');
			}
			$this->banks[$bank]->startUp();
			$this->banks[$bank]->calculateFitness();
			$this->banks[$bank]->assignBestCitizens();
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	public function isFinished($bank = NULL, $fitness_threshold = 0){
		try{
			if(is_null($bank)){
				throw new Exception('Error, falta especificar banco');
			}
			$bests = $this->getBestCandidates($bank);
			$can0 = $bests[0]->getFitness();
			return ($can0 == $fitness_threshold);
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	function microtime_float()
  {
    list($useg, $seg) = explode(" ", microtime());
    return ((float)$useg + (float)$seg);
  }
	
	public function start($bank = NULL){
		try{
			if(is_null($bank)){
				throw new Exception('Error, falta especificar banco');
			}
			//BUCLE HASTA ENCONTRAR EL MEJOR CANDIDATO
			$end = FALSE;
			$generation = 0;
			$stime = $this->microtime_float();
			
			echo "<table class='table table-striped table-bordered table-hover'>";
			echo "<thead><tr>";
			echo "<th class = 'success'>GENERACIÓN</th>";
			echo "<th class = 'success'>MEJOR CANDIDATO</th>";
			echo "<th class = 'success'>FITNESS</th>";
			echo "</tr></thead><tbody>";
			
			while($end == FALSE && $generation < self::MAX_GENERATIONS){
				//CREAMOS CANDIDATOS A PARTIR DE LOS DOS MEJORES CANDIDATOS DE LA GENERACIÓN ANTERIOR
				$this->banks[$bank]->reproduce();

				//MUTAMOS LOS CANDIDATOS DE LA GENERACIÓN ACTUAL
				$this->banks[$bank]->mutate();

				//EVALUAMOS LOS CANDIDATOS Y ESTABLECEMOS LOS DOS MEJORES DE ESTA GENERACIÓN
				$this->banks[$bank]->calculateFitness();
				$this->banks[$bank]->assignBestCitizens();

				//PRINTAMOS POR PANTALLA LOS DOS MEJORES CANDIDATOS DE ESTA GENERACIÓN
				$bests = $this->banks[$bank]->getBestCitizens();
				
				$i = 0;
				foreach($bests as $b){
				  ++$i;
				  echo "<tr><td>$generation</td><td>" . $b->getData() . "</td><td>" . $b->getFitness() . "</td>";
				}

				$generation++;
				$end = $this->isFinished(0, self::FITNESS_THRESHOLD);
			}
			echo "</tbody></table><br /><br />";
			$ftime = $this->microtime_float();
			echo "Tiempo de evolución : " . ($ftime - $stime) . ' segundos' . PHP_EOL . PHP_EOL;
			return $this->banks[$bank]->getBestCitizens();
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}

?>