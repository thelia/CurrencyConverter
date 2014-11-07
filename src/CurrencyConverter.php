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

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     *
     * The origin currency
     *
     * @param string $value ISO Code 4217 (example : USD, EUR). See http://fr.wikipedia.org/wiki/ISO_4217
     * @return $this
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
     * @return $this
     */
    public function to($value)
    {
        $this->to = $value;

        return $this;
    }

    public function resolve(Number $number)
    {
        $this->validate();
        $this->provider->from($this->from);
        $this->provider->to($this->to);
        $result = $this->provider->resolve($number);

        if (!($result instanceof \Thelia\Math\Number)) {
            throw new \LogicException('your provider must return a Thelia\Math\Number instance');
        }

        $this->reset();

        return $result;
    }

    private function validate()
    {
        if (null === $this->from || null === $this->to) {
            throw new \RuntimeException('from and to parameters must be provided');
        }
    }

    private function reset()
    {
        $this->from = null;
        $this->to = null;
    }
}
