<?php

namespace App\Constants;

class InvoiceRecurringStatus
{
    const Daily = 1;
    const Weekly = 7;
    const Fortnightly = 15;
    const Every_3_Weeks = 21;
    const Every_4_Weeks = 28;
    const Monthly = 30;
    const Bi_Monthly = 60;
    const Quarterly = 90;
    const Four_Monthly = 120;
    const Half_Yearly = 180;
    const Yearly = 360;
    const Two_Year = 720;

    public static function getArray()
    {
        return [
            ['value' => self::Daily, 'name' => 'Daily'],
            ['value' => self::Weekly, 'name' => 'Weekly'],
            ['value' => self::Fortnightly, 'name' => 'Fortnightly'],
            ['value' => self::Every_3_Weeks, 'name' => 'Every 3 Weeks'],
            ['value' => self::Every_4_Weeks, 'name' => 'Every 4 Weeks'],
            ['value' => self::Monthly, 'name' => 'Monthly'],
            ['value' => self::Bi_Monthly, 'name' => 'Bi Monthly'],
            ['value' => self::Quarterly, 'name' => 'Quarterly'],
            ['value' => self::Four_Monthly, 'name' => '4 Monthly'],
            ['value' => self::Half_Yearly, 'name' => 'Half Yearly'],
            ['value' => self::Yearly, 'name' => 'Yearly'],
            ['value' => self::Two_Year, 'name' => '2 Years'],
        ];
    }
    public static function getMapValue(int $recurring_value)
    {
        $recurringArr = self::getArray();
        foreach ($recurringArr as $item) {
            if ($item['value'] == $recurring_value) {
                return $item['name'];
            }
        }
    }
}
