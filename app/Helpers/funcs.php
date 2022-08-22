<?php

/**
 * calls dd function on Database Query Log
 */
function qdd($break_point = false, $way = 'dd')
{
    $queries = \DB::getQueryLog();
//    dd($queries)
    if ($way == 'log') {
        loger($queries);
        return;
    }

    if ($way == 'dd') {
        pdd($queries, $break_point);
    }

    if ($way == 'count') {
        return count($queries);
    }
    if ($way == 'get') {
        return $queries;
    }
    return;
}

/**
 * @param $value
 * @param bool $break_point
 */
function pdd($value, $break_point = false)
{

    $break_point_in_request = request()->input('break_point');

    if ($break_point && $break_point != $break_point_in_request) {
        return;
    }

    dd($value);
}

/**
 * returns true if array is associated otherwise false
 * @param {arr}
 *
 * @return bool
 */
function isAssoc(array $arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}
