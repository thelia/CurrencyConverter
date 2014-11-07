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


use Thelia\Math\Number;

/**
 * Interface ProviderInterface
 * @package Thelia\CurrencyConverter\Provider
 * @author Manuel Raynaud <manu@thelia.net>
 */
interface ProviderInterface
{

    /**
     *
     * The origin currency
     *
     * @param string $value ISO Code 4217 (example : USD, EUR). See http://fr.wikipedia.org/wiki/ISO_4217
     */
    public function from($value);

    /**
     *
     * the currency desired
     *
     * @param string $value ISO Code 4217 (example : USD, EUR). See http://fr.wikipedia.org/wiki/ISO_4217
     */
    public function to($value);

    /**
     * return the conversion
     *
     * @param \Thelia\Math\Number $number
     * @return mixed
     */
    public function resolve(Number $number);
}
