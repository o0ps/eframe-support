<?php

if ( ! function_exists('route_parameter')) {
    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    function route_parameter($key, $default = null)
    {
        return data_get(app('request')->route(), '2.' . $key, $default);
    }
}

if ( ! function_exists('currency')) {
    /**
     * @param       $amount
     * @param       $from
     * @param       $to
     * @param null  $date
     * @param array $options
     *
     * @return float
     */
    function currency($amount, $from, $to, $date = null, $options = [])
    {
        $basic_currency = 'EUR';

        $from = strtoupper($from);
        $to   = strtoupper($to);

        if (null === $date) {
            $eur_from_rate = \Swap\Laravel\Facades\Swap::latest("{$basic_currency}/{$from}", $options);
            $eur_to_rate   = \Swap\Laravel\Facades\Swap::latest("{$basic_currency}/{$to}", $options);
        } else {
            $eur_from_rate = \Swap\Laravel\Facades\Swap::historical("{$basic_currency}/{$from}", $date, $options);
            $eur_to_rate   = \Swap\Laravel\Facades\Swap::historical("{$basic_currency}/{$to}", $date, $options);
        }

        $to_from_rate = $eur_to_rate->getValue() / $eur_from_rate->getValue();

        return $amount * $to_from_rate;
    }
}