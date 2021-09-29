<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Domain\Model;

use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Weather
 */
class Weather extends AbstractEntity
{
    /**
     * @var string
     */
    protected $placeName = '';

    /**
     * @var int
     */
    protected $taskId = 0;

    /**
     * @var string
     */
    protected $serializedArray = '';

    /**
     * @var string
     */
    protected $localNames = '';


    /**
     * @var int
     */
    protected $dateMin = 0;

    /**
     * @var int
     */
    protected $dateMax = 0;

    /**
     * @return array
     */
    public function getUnserialzedArray() {
        return unserialize($this->getSerializedArray());
    }

    public function getCurrent() {
        $values = $this->getUnserialzedArray();
        return $values['current'];
    }

    public function getDays() {
        $values = $this->getUnserialzedArray();
        return $values['daily'];
    }

    public function getToday() {
        $values = $this->getUnserialzedArray();
        return $values['daily'][0];
    }

    public function getDay($offset) {
        $values = unserialize($this->getSerializedArray());
        DebugUtility::debug($values);
        return $values['current'];
    }


    /**
     * @return string $serializedArray
     */
    public function getSerializedArray(): string
    {
        return $this->serializedArray;
    }

    /**
     * @param string $serializedArray
     */
    public function setSerializedArray(string $serializedArray): void
    {
        $this->serializedArray = $serializedArray;
    }

    /**
     * @return string
     */
    public function getLocalNames(): string
    {
        return $this->localNames;
    }

    /**
     * @param string $localNames
     */
    public function setLocalNames(string $localNames): void
    {
        $this->localNames = $localNames;
    }

    /**
     * @return string
     */
    public function getLocalName($countryCode): string
    {
        return $this->localNames[$countryCode] ?? '';
    }

    /**
     * @return int
     */
    public function getTaskId(): int
    {
        return $this->taskId;
    }

    /**
     * @param int $taskId
     */
    public function setTaskId(int $taskId): void
    {
        $this->taskId = $taskId;
    }

    /**
     * @return string
     */
    public function getPlaceName(): string
    {
        return $this->placeName;
    }

    /**
     * @param string $placeName
     */
    public function setPlaceName(string $placeName): void
    {
        $this->placeName = $placeName;
    }

    /**
     * @return int
     */
    public function getDateMin(): int
    {
        return $this->dateMin;
    }

    /**
     * @param int $dateMin
     */
    public function setDateMin(int $dateMin): void
    {
        $this->dateMin = $dateMin;
    }

    /**
     * @return int
     */
    public function getDateMax(): int
    {
        return $this->dateMax;
    }

    /**
     * @param int $dateMax
     */
    public function setDateMax(int $dateMax): void
    {
        $this->dateMax = $dateMax;
    }



}
