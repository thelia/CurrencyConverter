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

namespace Thelia\CurrencyConverter;

use Thelia\CurrencyConverter\Exception\MissingProviderException;
use Thelia\CurrencyConverter\Provider\ProviderInterface;
use Thelia\Math\Number;

/**
 * Class Currency
 * @package Thelia\CurrencyConverter
 * @author Manuel Raynaud <manu@thelia.net>
 */
class CurrencyConverter
{
    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @var string Currency ISO Code 4217
     */
    protected $from;

    /**
     * @var string Currency ISO Code 4217
     */
    protected $to;

    /**
     * @param ProviderInterface $provider optional parameter
     */
    public function __construct(ProviderInterface $provider = null)
    {
        $this->provider = $provider;
    }

    /**
     * @param ProviderInterface $provider
     * @return self
     */
    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * The origin currency
     *
     * @param string $value ISO Code 4217 (example : USD, EUR). See http://fr.wikipedia.org/wiki/ISO_4217
     * @return self
     */
    public function from($value)
    {
        $this->from = $value;

        return $this;
    }

    /**
     *
     * the currency desired
     *
     * @param string $value ISO Code 4217 (example : USD, EUR). See http://fr.wikipedia.org/wiki/ISO_4217
     * @return self
     */
    public function to($value)
    {
        $this->to = $value;

        return $this;
    }

    /**
     *
     * convert a currency from one to another one
     *
     * eg : $converter->from('EUR')->to('USD')->convert(Number('1'));
     *
     * @param \Thelia\Math\Number $number
     * @return \Thelia\Math\Number
     */
    public function convert(Number $number)
    {
        $this->validate();
        $this->provider->from($this->from);
        $this->provider->to($this->to);
        $result = $this->provider->convert($number);

        if (!($result instanceof \Thelia\Math\Number)) {
            throw new \LogicException('your provider must return a Thelia\Math\Number instance');
        }

        $this->reset();

        return $result;
    }

    /**
     * Verify if the conversation can be done.
     *
     * @throws MissingProviderException thrown if the provider is missing
     * @throws \RuntimeException thrown if to or from parameters are missing
     */
    private function validate()
    {
        if (null === $this->provider) {
            throw new MissingProviderException('A provider must be set for converting a currency');
        }

        if (null === $this->from || null === $this->to) {
            throw new \RuntimeException('from and to parameters must be provided');
        }
    }

    /**
     * remove from and to configuration.
     */
    private function reset()
    {
        $this->from = null;
        $this->to = null;
    }
}
