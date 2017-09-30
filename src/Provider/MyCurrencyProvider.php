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

namespace Thelia\CurrencyConverter\Provider;

use Thelia\CurrencyConverter\Exception\CurrencyNotFoundException;
use Thelia\Math\Number;

/**
 *
 * Europen Central Bank provider.
 *
 * The European Central Bank provide all currencies quoted against the euro
 * The euro is the base currency
 *
 * Class TheMoneyConverterProvider
 * @package Thelia\CurrencyConverter\Provider
 * @author  Franck Allimant <franck@cqfdev.fr>
 */
class MyCurrencyProvider extends BaseProvider implements ProviderInterface
{
    protected $endPoint = "http://www.mycurrency.net/service/rates";

    protected $data;

    public function __construct($loadWebService = true)
    {
        if (true === $loadWebService) {
            $this->loadFromWebService();
        }
    }

    private function loadFromWebService()
    {
        $ch = curl_init($this->endPoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($ch);

        $this->loadFromJSON($data);
    }

    public function loadFromJSON($data)
    {
        if ($data) {
            $this->data = json_decode($data, true);
        }
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * @param Number $number
     * @return Number
     */
    public function convert(Number $number)
    {
        $rateFactor = $this->retrieveRateFactor();
        if ($this->to === 'USD') {
            return $number->multiply($rateFactor);
        } else {
            return $this->convertToOther($rateFactor, $number);
        }
    }

    /**
     * @param Number $rateFactor
     * @param Number $number
     * @return Number
     * @throws CurrencyNotFoundException if the `to` currency is not support
     */
    private function convertToOther(Number $rateFactor, Number $number)
    {
        $rateStr = $this->getRateFromFeed($this->to);
        $rate = $rateFactor->multiply($rateStr);
        return $number->multiply($rate);
    }
    /**
     * @return Number
     * @throws CurrencyNotFoundException if the `from` currency is not support
     */
    private function retrieveRateFactor()
    {
        if ($this->from === 'USD') {
            return new Number(1);
        }

        $rateStr = $this->getRateFromFeed($this->from);

        $rate = new Number($rateStr);
        $base = new Number(1);

        return $base->divide($rate);
    }

    /**
     * @param $code the currency code (ex. USD, EUR)
     * @return string the rate as a string.
     */
    private function getRateFromFeed($code)
    {
        foreach ($this->data as $item) {
            if ($item['currency_code'] == $code) {
                return "".$item['rate'];
            }
        }

        throw new CurrencyNotFoundException($code);
    }
}
