<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Working Days Per Month
    |--------------------------------------------------------------------------
    |
    | The standard number of working days in a month used to calculate
    | daily deductions such as Alpha (absent without leave).
    |
    */
    'working_days_per_month' => env('PAYROLL_WORKING_DAYS', 22),
];
