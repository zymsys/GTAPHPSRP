<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'mock/HeaderMgr.php';
require_once '../TTC.php';
require_once '../TTCQuestion.php';

define('FROM', 2);
define('TO', 9);
define('FACTORS','3,4');
define('ANSWERS','10,11,12,13');
define('WRONG_ANSWER',10);
define('RIGHT_ANSWER',12);

/**
 * test case.
 */
class TCCTests extends PHPUnit_Framework_TestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$_SERVER['REQUEST_URI'] = 'http://localhost/TTC/';
		$_SERVER['SCRIPT_NAME'] = 'TTCTests.php';
		ob_start();
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		parent::tearDown ();
		ob_clean();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
	}
	
	public function test_setupui() {
		$calc = new TTCQuestion();
		$ttc = new TTC($calc);
		$ttc->takeAction();
		$form = ob_get_clean();
		$el = new SimpleXMLElement($form);
		$inputs = $el->xpath('/form/input');
		$this->assertTrue(count($inputs) == 3);
	}

	public function test_getrange() {
		$_GET['from'] = strval(FROM);
		$_GET['to'] = strval(TO);
		$calc = new TTCQuestion();
		$ttc = new TTC($calc);
		$ttc->TakeAction();
		$this->assertEquals(FROM, $calc->getFrom());
		$this->assertEquals(TO, $calc->getTo());
	}
	
	public function test_makequestion() 
	{
		$_GET['from'] = strval(FROM);
		$_GET['to'] = strval(TO);
		$calc = new TTCQuestion();
		$ttc = new TTC($calc);
		for ($i = 0; $i < 100; $i++)
		{
			$ttc->TakeAction();
			$this->assertGreaterThanOrEqual(FROM, $calc->getFactor(0));
			$this->assertGreaterThanOrEqual(FROM, $calc->getFactor(1));
			$this->assertLessThanOrEqual(TO, $calc->getFactor(0));
			$this->assertLessThanOrEqual(TO, $calc->getFactor(1));
			$this->assertTrue(array_search($calc->getFactor(0) * $calc->getFactor(1), $calc->getAnswers()) !== false);
		}
	}
	
	public function test_getquestionfromurl() 
	{
		$_GET['from'] = strval(FROM);
		$_GET['to'] = strval(TO);
		$_GET['factors'] = FACTORS;
		$_GET['answers'] = ANSWERS;
		$calc = new TTCQuestion();
		$ttc = new TTC($calc);
		$ttc->TakeAction();
		$factors = explode(',',FACTORS);
		$expectedAnswers = explode(',', ANSWERS);
		$answers = $calc->getAnswers();
		$this->assertEquals($factors[0], $calc->getFactor(0));
		$this->assertEquals($factors[1], $calc->getFactor(1));
		$this->assertEquals(count($expectedAnswers), count($answers));
		for ($a = 0; $a < count($answers); $a++)
		{
			$this->assertEquals($expectedAnswers[$a], $answers[$a]);
		}
	}
	
	public function test_wronganswer()
	{
		$_GET['from'] = strval(FROM);
		$_GET['to'] = strval(TO);
		$_GET['factors'] = FACTORS;
		$_GET['answers'] = ANSWERS;
		$_GET['answer'] = strval(WRONG_ANSWER);
		$calc = new TTCQuestion();
		$ttc = new TTC($calc);
		$ttc->TakeAction();
		$result = ob_get_clean();
		$el = new SimpleXMLElement($result);
		$paragraphs = $el->xpath('/div/p');
		$this->assertEquals(3, count($paragraphs));
	}
	
	public function test_correctanswer()
	{
		$_GET['from'] = strval(FROM);
		$_GET['to'] = strval(TO);
		$_GET['factors'] = FACTORS;
		$_GET['answers'] = ANSWERS;
		$_GET['answer'] = strval(RIGHT_ANSWER);
		$calc = new TTCQuestion();
		$ttc = new TTC($calc);
		$ttc->TakeAction();
		$result = ob_get_clean();
		$this->assertEquals('', $result);
	}
}