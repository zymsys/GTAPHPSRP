<?php

class MathDrillStats {
	private $plays = 0;
	private $firstTries = 0;
	private $tries = 0;
	
	public function getPlays() {
		return $this->plays;
	}

	public function getFirstTries() {
		return $this->firstTries;
	}

	public function getTries() {
		return $this->tries;
	}

	public function setPlays($plays) {
		$this->plays = $plays;
	}

	public function setFirstTries($firstTries) {
		$this->firstTries = $firstTries;
	}

	public function setTries($tries) {
		$this->tries = $tries;
	}

	public function incrementPlays() {
		$this->plays += 1;
	}
	
	public function conditionallyIncrementFirstTries() {
		if ($this->tries == 1)
		{
			$this->firstTries += 1;
		}
	}
}

?>