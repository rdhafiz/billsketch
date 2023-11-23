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
}
