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

/**
 * Class BaseProvider
 * @package Thelia\CurrencyConverter\Provider
 * @author Manuel Raynaud <manu@thelia.net>
 */
abstract class BaseProvider implements ProviderInterface
{
    protected $from;

    protected $to;

    /**
     *
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
}
