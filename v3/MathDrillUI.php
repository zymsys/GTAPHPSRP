<?php
class MathDrillUI {
	private $question;
	private $stats;
	
	public function getStats() {
		return $this->stats;
	}

	public function __construct(MultiplicationQuestion $question)
	{
		$this->question = $question;
		$this->stats = new MathDrillStats();
	}
	
	private function GetRange()
	{
		if (!array_key_exists('from', $_GET) || !array_key_exists('to', $_GET))
		{
			echo "<form method=\"GET\" action=\"".$_SERVER['REQUEST_URI']."\">";
			echo "Practice your times tables from <input type=\"text\" size=\"2\" name=\"from\" /> to ".
				"<input type=\"text\" size=\"2\" name=\"to\" />.  <input type=\"submit\" value=\"Go!\" />";
			echo "</form>";
			return false;
		}
		else 
		{
			$this->question->setRange(intval($_GET['from']), intval($_GET['to']));
			return true;
		}
	}
	
	private function GetQuestion()
	{
		if (!array_key_exists('factors', $_GET) || !array_key_exists('answers', $_GET))
		{
			$this->question->generate();
		}
		else 
		{
			$factors = explode(',', $_GET['factors']);
			$answers = explode(',', $_GET['answers']);
			$this->question->setQuestion($factors, $answers);
		}
		$this->stats->setTries(array_key_exists('try', $_GET) ? intval($_GET['try']) : 0);
		$this->stats->setPlays(array_key_exists('plays', $_GET) ? intval($_GET['plays']) : 0);
		$this->stats->setFirstTries(array_key_exists('firsttries', $_GET) ? intval($_GET['firsttries']) : 0);
	}
	
	private function CheckAnswer(&$errorMessage)
	{
		$errorMessage = '';
		if (array_key_exists('answer', $_GET))
		{
			$this->stats->incrementPlays();
			if ($this->question->CheckAnswer($_GET['answer'],$errorMessage))
			{
				$this->stats->conditionallyIncrementFirstTries();
				HeaderMgr::setHeader('Location: '.$_SERVER['SCRIPT_NAME'].
					'?from='.$this->question->getFrom().
					'&to='.$this->question->getTo().
					'&plays='.$this->stats->getPlays().
					'&firsttries='.$this->stats->getFirstTries());
				return true;
			}
		}
		return false;
	}
	
	private function DrawQuestion($errorMessage = '')
	{
		echo "<div>";
		echo "<p>You've played ".$this->stats->getPlays()." times so far, and got ".
			$this->stats->getFirstTries()." correct on the first try.</p>";
		echo "<p>$errorMessage</p>";
		echo "<p>".$this->question->getFactor(0)." x ".$this->question->getFactor(1)." =</p>";
		echo "<p>";
		$answers = $this->question->getAnswers();
		foreach ($answers as $candidate)
		{
			echo "<a href=\"".$_SERVER['SCRIPT_NAME'].
				"?from=".$this->question->getFrom().
				"&amp;to=".$this->question->getTo().
				"&amp;factors=".implode(',', array($this->question->getFactor(0), $this->question->getFactor(1))).
				"&amp;answers=".implode(',', $answers).
				"&amp;answer=".$candidate.
				"&amp;try=".($this->stats->getTries() + 1).
				"&amp;plays=".$this->stats->getPlays().
				"&amp;firsttries=".$this->stats->getFirstTries().
				"\">$candidate</a> ";
		}
		echo "</p></div>";
	}
	
	public function TakeAction()
	{
		if ($this->GetRange())
		{
			$errorMessage = '';
			$this->GetQuestion();
			if (!$this->CheckAnswer($errorMessage))
			{
				$this->DrawQuestion($errorMessage);
			}
		}
	}
}

?>