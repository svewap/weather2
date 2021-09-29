<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\UserFunc;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\DebugUtility;

/**
 * TCA userFunc stuff
 */
class Tca
{
    /**
     * label_userFunc for tx_weather2_domain_model_dwdwarncell
     *
     * @param array $parameters
     */
    public function getDwdWarnCellTitle(array &$parameters): void
    {
        $parameters['title'] = sprintf('%s (%s)', $parameters['row']['name'], $parameters['row']['warn_cell_id']);
    }

    /**
     * label_userFunc for tx_weather2_domain_model_weather
     *
     * @param array $parameters
     */
    public function weatherTitle(&$parameters)
    {
        $record = BackendUtility::getRecord($parameters['table'], $parameters['row']['uid']);
        $newTitle = $record['place_name'];

        $dateTime = new \DateTime('@'.$record['date_min']);
        $dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $newTitle .= ' (' . date_format($dateTime,'d.m.Y H:i'). '';

        $dateTime = new \DateTime('@'.$record['date_max']);
        $dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $newTitle .= '-' . date_format($dateTime,'d.m.Y H:i'). ')';

        $parameters['title'] = $newTitle;
    }
}
