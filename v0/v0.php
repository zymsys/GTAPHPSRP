<?php
class TimesTableChallenge
{
	private $factors = array();
	private $answers = array();
	private $from;
	private $to;
	
	public function GetRange()
	{
		if (!array_key_exists('from', $_GET) || !array_key_exists('to', $_GET))
		{
			echo "<form method=\"GET\" action=\"".$_SERVER['REQUEST_URI']."\">";
			echo "Practice your times tables from <input type=\"text\" size=\"2\" name=\"from\"> to ".
				"<input type=\"text\" size=\"2\" name=\"to\">.  <input type=\"submit\" value=\"Go!\">";
			echo "</form>";
			return false;
		}
		else 
		{
			$this->from = intval($_GET['from']);
			$this->to = intval($_GET['to']);
			return true;
		}
	}

	public function GetQuestion()
	{
		if (!array_key_exists('factors', $_GET) || !array_key_exists('answers', $_GET))
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
		else 
		{
			$this->factors = explode(',', $_GET['factors']);
			$this->answers = explode(',', $_GET['answers']);
		}
	}
	
	private function DrawQuestion()
	{
		echo "<div><p>{$this->factors[0]} x {$this->factors[1]} =</p>";
		echo "<p>";
		foreach ($this->answers as $candidate)
		{
			echo "<a href=\"".$_SERVER['SCRIPT_NAME'].
				"?from=".$this->from.
				"&to=".$this->to.
				"&factors=".implode(',', $this->factors).
				"&answers=".implode(',', $this->answers).
				"&answer=".$candidate.
				"\">$candidate</a> ";
		}
		echo "</p></div>";
	}
	
	private function CheckAnswer()
	{
		if (array_key_exists('answer', $_GET))
		{
			if ($_GET['answer'] == ($this->factors[0] * $this->factors[1]))
			{
				header('Location: '.$_SERVER['SCRIPT_NAME'].
					'?from='.$this->from.
					'&to='.$this->to);
				return true;
			}
			echo "<p>Bzzt...  ".intval($_GET['answer'])." is not the correct answer.</p>";
		}
		return false;
	}
	
	public function TakeAction()
	{
		if ($this->GetRange())
		{
			$this->GetQuestion();
			if (!$this->CheckAnswer())
			{
				$this->DrawQuestion();
			}
		}
	}
}

$ttc = new TimesTableChallenge();
$ttc->TakeAction();
?>
