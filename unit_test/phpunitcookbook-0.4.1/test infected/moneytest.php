<?php

/********************************************************
*	A PHP implementation of the testing class <MoneyTest> described 
*	in the article 'Test Infected: Programmers Love Writing Tests" 
*	by Kent Bech & Erich Gamma available at: 
*	<http://members.pingnet.ch/gamma/junit.htm>
*
*	Note: There are several versions of the unit testing framework for PHP
*	This code uses phpunit-0.4 written by Fred Yankowski <fred@ontosys.com>
*   OntoSys, Inc  <http://www.OntoSys.com>
*
*
*	author:	Greg McDowell <greg@webpureit.com>
*	date:		Tuesday, 6 August 2002
*
*	this class module is accompanied with a full set of classes 
*	that satisfy the tests - <money.php>
*
*********************************************************/

require_once "phpunit.php";
require_once "money.php";

Class MoneyTest extends TestCase {
	var $m12CHF;
	var $m14CHF;
	var $m7USD;
	var $m21USD;
	var $mArray1;
	var $mArray2;
	var $moneybag1;
	var $moneybag2;
	
	function MoneyTest( $name = "MoneyTest" ) {
		 $this->TestCase( $name );
	}

	function setUp() {
		//create some money objects...
		$this->m12CHF = new Money(12, "CHF");
		$this->m14CHF = new Money(14, "CHF");
		$this->m7USD = new Money(7, "USD");
		$this->m21USD = new Money(21, "USD");

		// create some array of money objects...
		$this->mArray1 = array();
		$this->mArray1[ ] = $this->m12CHF;
		$this->mArray1[ ] = $this->m14CHF;

		$this->mArray2 = array();
		$this->mArray2[ ] = $this->m12CHF;
		$this->mArray2[ ] = $this->m14CHF;
		$this->mArray2[ ] = $this->m21USD;

		// create a couple of money bags, using the 'fudged' overridden constructor!
		$this->moneybag1 = new MoneyBag( $this->mArray1 );
		$this->moneybag2 = new MoneyBag( $this->m12CHF, $this->m21USD );
	}

	function tearDown() {
		$this->m12CHF =  NULL;
		$this->m14CHF =  NULL;
		$this->m7USD =  NULL;
		$this->m21USD =  NULL;
		$this->mArray1 = NULL;
		$this->mArray2 = NULL;
		$this->moneybag1 = NULL;
		$this->moneybag2 = NULL;
	}

	function test_simpleAdd() {
	// add two money objects
		$result = $this->m12CHF->add( $this->m14CHF );
		$this->assert( new Money(27, "CHF"), $result, "This should fail" );
		$this->assertEquals( new Money(27, "CHF"), $result, "This should fail" );
	}

	function test_equals() {
	// compare two money obejcts.
		$this->assert( $this->m12CHF->equals( new Money(12, "CHF") ), "This should pass" );
		$this->assert( $this->m12CHF->equals( $this->m12CHF ), "This should pass" );
		//$this->assert( $this->m12CHF->equals( NULL ), "This should pass" );
	}

	function test_bagEquals() {
	// compare two money bags, including their collection/array of monies.
		$this->assertEquals( $this->moneybag1->equals( $this->moneybag1 ), TRUE, "this should pass" );
		$this->assert( $this->moneybag1->equals( $this->moneybag1 ) , "This should pass" );
		$this->assertEquals( $this->moneybag1, $this->moneybag1, "This should pass" );
	}

	function test_appendMoney() {
	// add a money object to a moneybags collection/array or monies
		$this->moneybag1->appendMoney( $this->m21USD );
		$this->assert( $this->moneybag1->size()  == 2 , "This should pass" );
		$this->assert( $this->moneybag1 , "This should pass" );
	}

	function test_size() {
	// number of monies in moneybag collection/array
		$this->assert( $this->moneybag1->size() == 1);
		$this->assertEquals( $this->moneybag1->size(), 1);
	}

	function test_mixedSimpleAdd() {
	// add 2 different currency monies together
		$result = $this->m21USD->add( $this->m12CHF );
		$this->assertEquals($this->moneybag2, $result, "this should pass");

	}

	function test_bagSimpleAdd() {
	// add a MoneyBag to a simple Money 
		$expected = $this->moneybag2->add( $this->m12CHF );
		$result = $this->m12CHF->add( $this->moneybag2 );
		$this->assertEquals($expected, $result, "this should pass");
	}

	function test_simpleBagAdd() {
	// add a simple Money to a MoneyBag 
		$expected = $this->moneybag1->addMoney( $this->m12CHF );
		$result = $this->moneybag1->add( $this->m12CHF );
		$this->assertEquals($expected, $result, "this should pass");
	}

	function test_bagBagAdd() {
	// add two MoneyBags  
		$expected = $this->moneybag1->addMoneyBag( $this->moneybag1 );
		$result = $this->moneybag1->add( $this->moneybag1 );
		$this->assertEquals( $expected, $result, "this should pass" );
	}
};

// using the a suites ability to introspect and discover
// all the appropriate test methods and executing them
// automatically...
$suite = new TestSuite( "MoneyTest" );
$testRunner = new TestRunner();
$testRunner->run($suite);


/*
// a suite can run not only test cases, but other suites aswell...
$suite1 = new TestSuite( "MoneyTest" );
$suite2 = new TestSuite();
$suite2->addTest( $suite1 );
$testRunner = new TestRunner();
print("<h2>Running MoneyTest</h2>");
$testRunner->run($suite2);
*/

/*
// a manual way of adding more than one test case to a suite...
$test1 = new MoneyTest("test_simpleAdd");
$test2 = new MoneyTest("test_bagSimpleAdd");
$suite = new TestSuite();
$suite->addTest($test1);
$suite->addTest($test2);
$testRunner = new TestRunner();
$testRunner->run($suite);
*/

/*
// how to run a single test case...
$test = new MoneyTest("test_simpleAdd");
$result = $test->run();
print $result;
print $result->countFailures();
*/
?>
