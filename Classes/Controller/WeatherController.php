<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Controller;

use JWeiland\Weather2\Domain\Repository\WeatherRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * WeatherController
 */
class WeatherController extends ActionController
{
    /**
     * @var WeatherRepository
     */
    protected $weatherRepository;

    public function __construct(WeatherRepository $weatherRepository)
    {
        if (is_callable('parent::__construct')) {
            parent::__construct();
        }
        $this->weatherRepository = $weatherRepository;
    }

    /**
     * action show displays the newest Weather model
     */
    public function currentAction(): void
    {
        $weather = $this->weatherRepository->findBySelection($this->settings['selection']);
        $this->view->assign('weather', $weather);
    }

    public function dayAction(): void
    {
        $weather = $this->weatherRepository->findBySelection($this->settings['selection']);
        $this->view->assign('weather', $weather);
    }
}
