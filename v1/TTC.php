<?php
class TTC {
	private $question;
	
	public function __construct(TTCQuestion $question)
	{
		$this->question = $question;
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
	}
	
	private function CheckAnswer(&$errorMessage)
	{
		$errorMessage = '';
		if (array_key_exists('answer', $_GET))
		{
			if ($_GET['answer'] == ($this->question->getFactor(0) * $this->question->getFactor(1)))
			{
				HeaderMgr::setHeader('Location: '.$_SERVER['SCRIPT_NAME'].
					'?from='.$this->question->getFrom().
					'&to='.$this->question->getTo());
				return true;
			}
			$errorMessage = "<p>Bzzt...  ".intval($_GET['answer'])." is not the correct answer.</p>";
		}
		return false;
	}
	
	private function DrawQuestion($errorMessage = '')
	{
		echo "<div>$errorMessage";
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