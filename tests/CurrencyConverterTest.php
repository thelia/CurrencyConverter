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
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::resolve
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::validate
     * @expectedException \RuntimeException
     */
    public function testResolveWithoutFrom()
    {
        $currencyConverter = new CurrencyConverter($this->getProvider());
        $currencyConverter->to('EUR');
        $currencyConverter->resolve(new Number(1));
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::resolve
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::validate
     * @expectedException \RuntimeException
     */
    public function testResolveWithoutTo()
    {
        $currencyConverter = new CurrencyConverter($this->getProvider());
        $currencyConverter->from('EUR');
        $currencyConverter->resolve(new Number(1));
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::resolve
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::validate
     * @expectedException \RuntimeException
     */
    public function testResolveWithoutToAndFrom()
    {
        $currencyConverter = new CurrencyConverter($this->getProvider());
        $currencyConverter->resolve(new Number(1));
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::resolve
     * @expectedException \LogicException
     */
    public function testResolveWithBadProvider()
    {
        $currencyConverter = new CurrencyConverter($this->getMock('\Thelia\CurrencyConverter\Provider\ProviderInterface'));
        $currencyConverter->from('EUR');
        $currencyConverter->to('USD');
        $currencyConverter->resolve(new Number(1));
    }

    /**
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::resolve
     * @covers \Thelia\CurrencyConverter\CurrencyConverter::reset
     * @expectedException \RuntimeException
     */
    public function testResolveTwice()
    {
        $number = new Number(1);

        $currencyConverter = new CurrencyConverter($this->getProvider());
        $currencyConverter->from('EUR');
        $currencyConverter->to('USD');
        $currencyConverter->resolve($number);

        $currencyConverter->resolve($number);
    }

    public function getProvider()
    {
        $provider = $this->getMock('\Thelia\CurrencyConverter\Provider\ProviderInterface');

        $provider->method('resolve')
            ->willReturn(new Number(1));

        return $provider;
    }
}
