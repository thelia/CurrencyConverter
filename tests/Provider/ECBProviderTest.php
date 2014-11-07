<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Thelia\CurrencyConverter\Tests\Provider;


use Thelia\CurrencyConverter\Provider\ECBProvider;
use Thelia\Math\Number;

class ECBProviderTest extends \PHPUnit_Framework_TestCase
{
    public $data = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
    <gesmes:Envelope
        xmlns:gesmes="http://www.gesmes.org/xml/2002-08-01"
        xmlns="http://www.ecb.int/vocabulary/2002-08-01/eurofxref">
	<gesmes:subject>Reference rates</gesmes:subject>
	<gesmes:Sender>
		<gesmes:name>European Central Bank</gesmes:name>
	</gesmes:Sender>
	<Cube>
		<Cube time='2014-11-07'>
			<Cube currency='USD' rate='1.2393'/>
			<Cube currency='GBP' rate='0.78340'/>
		</Cube>
	</Cube>
    </gesmes:Envelope>
XML;

    /**
     * @var \Thelia\CurrencyConverter\Provider\ECBProvider
     */
    public $provider;

    public function setUp()
    {
        $this->provider = new ECBProvider();

        $this->provider->loadFromXml($this->data);
    }

    /**
     * @covers \Thelia\CurrencyConverter\Provider\ECBProvider::resolve
     */
    public function testFromEuro()
    {
        $provider = $this->provider;
        $number = new Number(1);

        $usdRate = $provider->from('EUR')->to('USD')->resolve($number);

        $this->assertInstanceOf('Thelia\Math\Number', $usdRate, "the provider must return an instance of Number");
        $this->assertEquals('1.2393', $usdRate->getNumber(-1), "the expected result from EUR to USD is 1.2393");

        $gbpRate = $provider->from('EUR')->to('GBP')->resolve($number);

        $this->assertInstanceOf('Thelia\Math\Number', $gbpRate, "the provider must return an instance of Number");
        $this->assertEquals('0.78340', $gbpRate->getNumber(-1), "the expected result from EUR to GBP is 0.78340");
    }

    /**
     * @covers \Thelia\CurrencyConverter\Provider\ECBProvider::resolve
     */
    public function testFromUsd()
    {
        $provider = $this->provider;
        $number = new Number(1);

        $euroRate = $provider->from('USD')->to('EUR')->resolve($number);

        $this->assertInstanceOf('Thelia\Math\Number', $euroRate, "the provider must return an instance of Number");
        $this->assertEquals('0.80690712498991', $euroRate->getNumber(-1), "the expected result from USD to EUR is 0.80690712498991");

        $gbpRate = $provider->from('USD')->to('GBP')->resolve($number);

        $this->assertInstanceOf('Thelia\Math\Number', $gbpRate, "the provider must return an instance of Number");
        $this->assertEquals('0.6321310417171', $gbpRate->getNumber(-1), "the expected result from USD to GBP is 0.6321310417171");
    }

    /**
     * @covers \Thelia\CurrencyConverter\Provider\ECBProvider::resolve
     */
    public function testFromGbp()
    {
        $provider = $this->provider;
        $number = new Number(1);

        $euroRate = $provider->from('GBP')->to('EUR')->resolve($number);

        $this->assertInstanceOf('Thelia\Math\Number', $euroRate, "the provider must return an instance of Number");
        $this->assertEquals('1.2764871074802', $euroRate->getNumber(-1), "the expected result from GBP to EUR is 1.2764871074802");

        $usdRate = $provider->from('GBP')->to('USD')->resolve($number);

        $this->assertInstanceOf('Thelia\Math\Number', $usdRate, "the provider must return an instance of Number");
        $this->assertEquals('1.5819504723002', $usdRate->getNumber(-1), "the expected result from GBP to USD is 1.5819504723002");
    }

    /**
     * @expectedException \Thelia\CurrencyConverter\Exception\CurrencyNotFoundException
     */
    public function testResolveWithUnknownCurrencies()
    {
        $provider = $this->provider;
        $number = new Number(1);

        $rate = $provider->from('FOO')->to('BAR')->resolve($number);
    }
}
