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
 * Class ECBProvider
 * @package Thelia\CurrencyConverter\Provider
 * @author Manuel Raynaud <manu@thelia.net>
 */
class ECBProvider extends BaseProvider implements ProviderInterface
{
    protected $endPoint = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";

    protected $data;

    public function __construct($loadWebService = true)
    {
        if (true === $loadWebService) {
            $this->loadFromWebService();
        }
    }


    private function loadFromWebService($endPoint = null)
    {
        if (null !== $endPoint) {
            $this->endPoint = $endPoint;
        }

        $ch = curl_init($this->endPoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($ch);

        $xml = new \SimpleXMLElement($data);

        $this->data = $xml->Cube[0]->Cube[0];
    }

    public function loadFromXml($data)
    {
        $xml = new \SimpleXMLElement($data);

        $this->data = $xml->Cube[0]->Cube[0];
    }

    public function getData()
    {
        return $this->data;
    }

    public function convert(Number $number)
    {
        $rateFactor = $this->retrieveRateFactor();

        if ($this->to === 'EUR') {
            return $number->multiply($rateFactor);
        } else {
            return $this->convertToOther($rateFactor, $number);
        }
    }

    /**
     * @param \Thelia\Math\Number $rateFactor
     * @param \Thelia\Math\Number $number
     * @return \Thelia\Math\Number
     * @throws \Thelia\CurrencyConverter\Exception\CurrencyNotFoundException if the `to` currency is not support
     */
    private function convertToOther(Number $rateFactor, Number $number)
    {
        $conversion = false;
        foreach ($this->data->Cube as $last) {
            $code = strtoupper($last["currency"]);

            if ($code === $this->to) {
                $rate = $rateFactor->multiply((string) $last['rate']);
                $conversion = $number->multiply($rate);
            }
        }

        if ($conversion === false) {
            throw new CurrencyNotFoundException($this->to);
        }

        return $conversion;
    }

    /**
     * @return \Thelia\Math\Number
     * @throws \Thelia\CurrencyConverter\Exception\CurrencyNotFoundException if the `from` currency is not support
     */
    private function retrieveRateFactor()
    {
        $rateFactor = false;

        if ($this->from === 'EUR') {
            $rateFactor = new Number(1);
        } else {
            // Find the exchange rate for this currency
            foreach ($this->data->Cube as $last) {
                $code = strtoupper((string) $last["currency"]);

                if ($code === $this->from) {
                    // Get the rate factor
                    $rate = new Number((string) $last['rate']);
                    $base = new Number(1);
                    $rateFactor = $base->divide($rate);
                }
            }
        }

        if (false === $rateFactor) {
            throw new CurrencyNotFoundException($this->from);
        }

        return $rateFactor;
    }
}
