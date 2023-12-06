<?php

namespace App\Constants;

class InvoiceStatus
{
    const Draft = 1;
    const Pending = 2;
    const Processing = 3;
    const Partially_paid = 4;
    const Paid = 5;
    const Overdue = 6;
    const Canceled = 7;

    public static function getArray()
    {
        return [
            ['value' => self::Draft, 'name'=> 'Draft'],
            ['value' => self::Pending, 'name'=> 'Pending'],
            ['value' => self::Partially_paid, 'name'=> 'Partially paid'],
            ['value' => self::Paid, 'name'=> 'Paid'],
            ['value' => self::Overdue, 'name'=> 'Overdue'],
            ['value' => self::Canceled, 'name'=> 'Canceled'],
        ];
    }
    public static function getMapValue(int $status_value)
    {
        $statusArr = self::getArray();
        foreach ($statusArr as $item) {
            if ($item['value'] == $status_value) {
                return $item['name'];
            }
        }
    }
}
