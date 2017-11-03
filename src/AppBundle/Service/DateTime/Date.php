<?php
namespace AppBundle\Service\DateTime;

use \DateTime;
/**
 * Class Date
 * Does what you need to do with DateTime object
 *
 * @package AppBundle\Service\DateTime
 */
class Date
{
    /**
     * Converts given date to human readable format, like 2 days ago
     *
     * @param \DateTime $time
     * @return string
     */
    public function getHumanTiming(\DateTime $time)
    {
        $time = $time->getTimestamp();
        $time = time() - $time; // to get the time since that moment
        $time = ($time < 1) ? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) {
                continue;
            }
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits>1) ? 's' : '');
        }
    }

    /**
     * Format the given date into dd/mm/yyyy format
     *
     * @param \DateTime $time
     * @return string
     */
    public function convertDateFormat(\DateTime $time)
    {
        return $time->format('d/m/Y');
    }

    /**
     *
     * @param DateTime $date
     * @return int
     */
    public function dateToInt(DateTime $date)
    {
        return $date->getTimestamp();
    }

    /**
     *
     * @param null|int $integer
     * @return DateTime
     */
    public function intToDate($integer)
    {
        $date = new DateTime();
        $date->setTimestamp($integer);

        return $date;
    }
}