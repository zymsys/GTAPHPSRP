<?php
class MultiplicationQuestion
{
	private $from;
	private $to;
	private $factors;
	private $answers;
	
	function setRange($from, $to) {
		$this->from = $from;
		$this->to = $to;
	}
	
	public function getFrom()
	{
		return $this->from;
	}
	
	public function getTo()
	{
		return $this->to;
	}
	
	function setQuestion(array $factors, array $answers)
	{
		if (count($factors) != 2)
		{
			throw new InvalidArgumentException("There must be only two factors.");
		}
		$this->factors = $factors;
		$this->answers = $answers;
	}
	
	function getFactor($factorIndex)
	{
		if (($factorIndex < 0) || ($factorIndex > 1))
		{
			throw new InvalidArgumentException("factorIndex must be either 0 or 1");
		}
		return $this->factors[$factorIndex];
	}
	
	function getAnswers()
	{
		return $this->answers;
	}
	
	function generate()
	{
		$this->factors = array(
			rand($this->from, $this->to),
			rand($this->from, $this->to)
		);
		$this->answers = array($this->factors[0] * $this->factors[1]);
		while (count($this->answers) < 4)
		{
			switch (rand(1,3)) 
			{
				case 1:
					$candidate = strval($this->answers[0]);
					$baddigit = rand(0,strlen($candidate) - 1);
					$candidate[$baddigit] = strval(rand(0,9));
					break;
				case 2:
					$otherfactors = $this->factors;
					$badfactor = rand(0,1);
					$otherfactors[$badfactor] = rand($this->from, $this->to);
					$candidate = $otherfactors[0] * $otherfactors[1];
					break;
				default:
					$candidate = rand($this->from * $this->from, $this->to * $this->to);
					break;
			}
			$candidate = intval($candidate);
			if (array_search($candidate, $this->answers) === false)
			{
				$this->answers[] = $candidate;
			}
		}
		shuffle($this->answers);
	}
	
	public function CheckAnswer($answer, &$errorMessage)
	{
		if (($this->factors[0] * $this->factors[1]) == $answer)
		{
			return true;
		}
		$errorMessage = "<p>Bzzt...  $answer is not the correct answer.</p>";
		return false;
	}
}