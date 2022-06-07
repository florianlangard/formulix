<?php

namespace App\Service;

use DateTime;
use DateTimeZone;

class DateChecker
{
    /**
     * Check if event is still to come, or outdated to authorize new or edit prediction
     *
     * @param Datetime $date
     * @return boolean
     */
    public function checkDate(Datetime $date): bool
    {
        $currentDate = new DateTime('now', new DateTimeZone('UTC'));
        if ($currentDate > $date) {
            return false;
        }
        return true;
    }
}