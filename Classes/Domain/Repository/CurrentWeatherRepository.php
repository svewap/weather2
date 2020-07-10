<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Domain\Repository;

use JWeiland\Weather2\Domain\Model\CurrentWeather;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for CurrentWeathers
 */
class CurrentWeatherRepository extends Repository
{
    /**
     * Returns the latest weather
     *
     * @param string $selection
     * @return CurrentWeather|null
     */
    public function findBySelection(string $selection): ?CurrentWeather
    {
        $query = $this->createQuery();
        $query->matching($query->equals('name', trim($selection)));
        // Order desc to get the latest weather
        $query->setOrderings([
            'uid' => QueryInterface::ORDER_DESCENDING
        ]);

        return $query->execute()->getFirst();
    }
}
