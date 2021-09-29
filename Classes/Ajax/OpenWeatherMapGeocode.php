<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Ajax;

use JWeiland\Weather2\Domain\Repository\DwdWarnCellRepository;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class DeutscherWetterdienstWarnCellSearch
 */
class OpenWeatherMapGeocode
{

    /**
     * @return Response
     */
    public function renderGeocode(): Response
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $term = GeneralUtility::_GET('query');
        $apiKey = GeneralUtility::_GET('apiKey');

        $suggestions = [
            ['data' => 'dede',
            'value' => 'deded']
        ];



        return new JsonResponse(['suggestions' => $suggestions]);
    }


}