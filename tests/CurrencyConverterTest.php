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

namespace Thelia\CurrencyConverter\Tests;

use Thelia\CurrencyConverter\CurrencyConverter;
use Thelia\Math\Number;

class CurrencyConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::convert
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::__construct
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::validate
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::to
     * @expectedException \RuntimeException
     */
    public function testConvertWithoutFrom()
    {
        $currencyConverter = new CurrencyConverter($this->getProvider());
        $currencyConverter->to('EUR');
        $currencyConverter->convert(new Number(1));
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::convert
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::validate
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::from
     * @expectedException \RuntimeException
     */
    public function testConvertWithoutTo()
    {
        $currencyConverter = new CurrencyConverter($this->getProvider());
        $currencyConverter->from('EUR');
        $currencyConverter->convert(new Number(1));
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::convert
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::validate
     * @expectedException \RuntimeException
     */
    public function testConvertWithoutToAndFrom()
    {
        $currencyConverter = new CurrencyConverter($this->getProvider());
        $currencyConverter->convert(new Number(1));
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::convert
     * @expectedException \LogicException
     */
    public function testConvertWithBadProvider()
    {
        $currencyConverter = new CurrencyConverter($this->getMock('\Thelia\CurrencyConverter\Provider\ProviderInterface'));
        $currencyConverter->from('EUR');
        $currencyConverter->to('USD');
        $currencyConverter->convert(new Number(1));
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::convert
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::validate
     * @expectedException \Thelia\CurrencyConverter\Exception\MissingProviderException
     */
    public function testConvertWithoutProvider()
    {
        $currencyConverter = new CurrencyConverter();
        $currencyConverter
            ->from('USD')
            ->to('EUR')
            ->convert(new Number('1'));
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::convert
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::reset
     * @expectedException \RuntimeException
     */
    public function testConvertTwice()
    {
        $number = new Number(1);

        $currencyConverter = new CurrencyConverter($this->getProvider());
        $currencyConverter->from('EUR');
        $currencyConverter->to('USD');
        $currencyConverter->convert($number);

        $currencyConverter->convert($number);
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::convert
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::__construct
     */
    public function testConvertWithSetterProvider()
    {
        $currencyConverter = new CurrencyConverter($this->getProvider());

        $result = $currencyConverter
            ->from('EUR')
            ->to('USD')
            ->convert(new Number('1'));

        $this->assertInstanceOf('Thelia\Math\Number', $result, 'the converter must return an instance of \Thelia\Math\Number');
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::convert
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::validate
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::setProvider
     */
    public function testConvert()
    {
        $currencyConverter = new CurrencyConverter();

        $result = $currencyConverter
            ->setProvider($this->getProvider())
            ->from('EUR')
            ->to('USD')
            ->convert(new Number('1'));

        $this->assertInstanceOf('Thelia\Math\Number', $result, 'the converter must return an instance of \Thelia\Math\Number');
    }

    public function getProvider()
    {
        $provider = $this->getMock('\Thelia\CurrencyConverter\Provider\ProviderInterface');

        $provider->method('convert')
            ->willReturn(new Number(1));

        return $provider;
    }
}
