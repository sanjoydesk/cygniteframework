<?php
/********************************************************
*	A PHP implementation of the classes described in the article 
*	'Test Infected: Programmers Love Writing Tests" 
*	by Kent Bech & Erich Gamma available at: 
*	<http://members.pingnet.ch/gamma/junit.htm>
*
*	author:	Greg McDowell <greg@webpureit.com>
*	date:		Tuesday, 6 August 2002
*
*	this class module is accompanied with a full set of tests using the
*	above described PHPUnit module <moneytest.php>
*
*********************************************************/

//************************************
// an 'quasi' abstract interface class
// with abstract methods that must be
// defined in sub-classes.
//************************************

Class IMoney {
	function add( $money ) {}
	function addMoney( $money ) {}
	function addMoneyBag( $moneyBag ) {}
};

//*************************************

//*************************************
// a class representing Money
//*************************************

Class Money extends IMoney{
	// class attribute(s)...
	var $amount;
	var $currency;

	function Money( $amt, $curr ) {
	// the class constructor...
		$this->amount = $amt;
		$this->currency = $curr;
	}

	function add( $money ) {
	// the inherited, 'overridden' method....
	// using Double Dispatch return the passed money object
	// after having called it's addMoney method passing 'this' == self as a parameter.

		return $money->addMoney( $this );
	}

	function addMoney( $money ) {
	// the inherited, 'overridden' method....
	// return a new object of appropriate type 
	// based on the parameter type passed in..

		if ( $this->currency() == $money->currency() ) {
			// add if money is same currency, then return a new money object.
			$result = new Money($this->amount() + $money->amount(), $this->currency() );
		} else { 
			// other wise return a new money bag passing 'this' ==self as a parameter...
			$result = new MoneyBag( $this, $money);	
		} 
		return $result;
	}

	function addMoneyBag( $moneyBag ) {
	// the inherited, 'overridden' method....
	// using double dispatch return money bag object calling it's add method...
		return $moneyBag->addMoney( $this );
	}

	function equals( $money ) {
	// compare self with passed-in money object...
	// Boolean result.
		$result = false;
		if ( ( $money != NULL ) ) {
			if ( ( $this->amount() == $money->amount() ) &&
				( $this->currency() == $money->currency()  ) ) {
				$result = true;
			}
		}
		return $result;
	}

	function amount() {
	// generic attribute accessor method...
		return $this->amount;
	}

	function currency() {
		// generic attribute accessor method...
		return $this->currency;
	}

};

//*************************************

//*************************************
// a class representing MoneyBag
//*************************************

Class MoneyBag extends IMoney {
	// class attribute(s)...
	var $allMyMonies = array();

	function MoneyBag( $m1, $m2=FALSE ) {
		// the class constructor...

		// Due to current version of PHP (4.3) not supporting 'Overriding' of methods....
		// this has been somewhat 'fudged' to work in a similar fashion...

		// the first parameter can be either an array of monies, a money object, or a money bag object...
		// the second parameter can be either a money object, or a money bag object, and is optional...

		// deal with the first parameter...
		if ( is_array( $m1 )  ){
			$this->loadMoneyArray( $m1 );
		} else if ( get_class( $m1 ) == "moneybag") {
			$this->allMyMonies = $m1->allMyMonies;
		} else {
			$this->appendMoney( $m1 );
		}

		// deal with the second parameter...
		if ( $m2 != FALSE ) {
			if ( get_class( $m2 ) == "moneybag" ) {
				$this->loadMoneyBag( $m2 );
			} else {
				$this->appendMoney( $m2 );
			}
		}
	}

	function loadMoneyArray( $moneyArray ) {
	// a method that will load the moneybag objects allMyMonies[ ] collection/array
	// of money objects

		$size = sizeof( $moneyArray );
		for ($count=0; $count<$size; $count++) {
			$this->appendMoney( $moneyArray[ $count ] );
		}
	}

	function loadMoneyBag( $moneyBag ) {
	//a method that will strip and copy a passed-in moneybag object's collection/array
	// of monies, and will load them into it's own collection/array.
	// Author: "I am sure someone will pick up on the 'slow' manner in which this is achieved!"

		$size = sizeof( $moneyBag->allMyMonies );
		for ( $count=0; $count<$size; $count++) {
			$this->appendMoney( $moneyBag->allMyMonies[$count] );
		}
	}

	function add( $money ) {
	// the inherited, 'overridden' method....
	// using Double Dispatch return the passed-in  object
	// after having called it's addMoneyBag method passing 'this' == self as a parameter.

		return $money->addMoneyBag( $this );
	}

	function addMoney( $money ) {
	// the inherited, 'overridden' method....
	// using double dispatch return a new money bag object containing the additional money object.
		return new MoneyBag($money, $this );
	}

	function addMoneyBag( $moneyBag ) {
	// using double dispatch return a new money bag object containing the additional money object.
		return new MoneyBag( $moneyBag, $this );
	}
	
	function appendMoney( $money ) {
		// add this money to the money bags monies...
		// if the currency already exists, then increase the total of this currency..

		// a flag...
		$added = FALSE; 
		$size = $this->size();

		for ( $count=0; $count<$size; $count++) {
			if ( $added == FALSE ) {
				if ( $this->allMyMonies[$count]->currency() == $money->currency() ) {
					$this->allMyMonies[$count] = $money->add( $this->allMyMonies[$count] );
					$added = TRUE;
				} 
			}
		}

		// after having check all existing monies, and still not added,
		// add the money to the bag...

		if ( $added == FALSE ) {
			$this->allMyMonies[ ] = $money;
		}

	}

	function equals( $moneyBag ) {
		// this method examines each money in 'this' money bag and compares it
		// with the passed-in money bag's monies. 
		// Note: It doesn't allow for same monies in different order - sorry!
		
		$result = FALSE;
		$size = $this->size();
		for ( $count=0; $count<$size; $count++ ) {
			if ( ( $this->allMyMonies[ $count ]->amount() == $moneyBag->allMyMonies[ $count ]->amount() ) && 
				( $this->allMyMonies[ $count ]->currency() == $moneyBag->allMyMonies[ $count ]->currency()  ) ) {
				$result = TRUE;
			}
		}
		return $result;
	}

	function size() {
	// a method that returns the size of an objects collection/array
	// of monies.
		return sizeof( $this->allMyMonies );
	}

};

/*

$m1 = new Money(12, "CHF");
$m2 = new Money(14, "USD");
$m3 = new Money(16, "CHF");
$m4 = new Money(13, "CHF");
$mArray = array();
$mArray[ ] = $m1;
$mArray[ ] = $m2;
$mArray[ ] = $m3;
print_r($mArray);
print "<br>";
print "<br>";
//$mbag= new MoneyBag( $m1, $m2 );
$mbag= new MoneyBag( $mArray );
print_r ( $mbag->allMyMonies );
print "<br>";
//$mbag = NULL;

// a new test...
print "New tests<br><p>";
$mbag1 = new MoneyBag( $m1, $m2);
$mbag1->appendMoney( $m4 );
print_r ( $mbag1->allMyMonies );
print "<br>";
print $mbag1->getTotal();
print "<br>";
print $mbag1->getSize();
$result = $mbag1->equals( $mbag );
if ($result == TRUE) {
	print "true";
} else {
	print "false";
}	

*/

/*
$m12CHF = new Money(12, "CHF");
$m14CHF = new Money(14, "CHF");
$m7USD = new Money(7, "USD");
$m21USD = new Money(21, "USD");
$moneyArray = array( );
$moneyArray[ ] = $m12CHF;
$moneyArray[ ] = $m7USD;
$expected = new MoneyBag( $moneyArray );
$result = $m7USD->add($m12CHF);
print_r($expected);
print "<br>";
print_r($result);
//print get_class( $result );
*/

/*
$m12CHF = new Money(12, "CHF");
$m14CHF = new Money(14, "CHF");
$m7USD = new Money(7, "USD");
$m21USD = new Money(21, "USD");

print_r( $m12CHF->add($m12CHF) );
print"<br>";
print_r( $m12CHF->addMoney($m12CHF)  );
*/
/*
$result = $m21USD->add( $m12CHF );
print_r( $result );
print"<br>";

$moneyArray = array( );
$moneyArray[ ] = $m12CHF;
$moneyArray[ ] = $m21USD;
$mb1 = new MoneyBag( $moneyArray );
print_r( $mb1 );

*/
/*
$moneyArray = array( );
$moneyArray[ ] = $m21USD;
$moneyArray[ ] = $m7USD;
$mb1 = new MoneyBag( $moneyArray );
print_r($mb1);
print"<br>";
$result = $mb1->add( $m12CHF );
print_r($result);
print"<br>";
*/
/*
$moneyArray2 = array();
$moneyArray2[ ] = $m12CHF;
$moneyArray2[ ] = $m7USD;
$moneyArray2[ ] = $m21USD;
$mb2 = new MoneyBag( $moneyArray2 );
print_r($mb2);

*/
?>