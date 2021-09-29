<?php

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Unit\Controller;

use JWeiland\Weather2\Controller\WeatherController;
use JWeiland\Weather2\Domain\Repository\WeatherRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
 * Test case for class JWeiland\Weather2\Controller\WeatherController.
 *
 * @author Markus Kugler <projects@jweiland.net>
 * @author Pascal Rinker <projects@jweiland.net>
 */
class WeatherControllerTest extends UnitTestCase
{
    /**
     * @var \JWeiland\Weather2\Controller\WeatherController
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            WeatherController::class,
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );
    }

    public function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function showActionCallsRepositoryFindBySelectionWithSettingAsArgument()
    {
        $weather = new \JWeiland\Weather2\Domain\Model\Weather();

        $weatherRepository = $this->getAccessibleMock(
            WeatherRepository::class,
            ['findBySelection'],
            [],
            '',
            false
        );
        $this->inject($this->subject, 'weatherRepository', $weatherRepository);

        $view = $this->getAccessibleMock(
            TemplateView::class,
            ['assign'],
            [],
            '',
            false
        );
        $this->inject($this->subject, 'view', $view);

        $this->subject->_set('settings', ['selection' => 'testSelection']);
        $weatherRepository->expects(self::once())->method('findBySelection')->with('testSelection');
        $this->subject->showAction();
    }
}
