<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Task;

use JWeiland\Weather2\Domain\Model\Weather;
use JWeiland\Weather2\Utility\WeatherUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Service\CacheService;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * OpenWeatherMapTask Class for Scheduler
 */
class OpenWeatherMapTask extends AbstractTask
{


    /**
     * Table name
     *
     * @var string
     */
    protected $dbExtTable = 'tx_weather2_domain_model_weather';

    /**
     * JSON response of openweathermap api
     *
     * @var \stdClass
     */
    protected $responseClass;


    public static $API_VERSION = 2.5;

    /**
     * City
     *
     * @var string $city
     */
    public $city = '';


    /**
     * @var float $lat
     */
    public $lat = 0.0;

    /**
     * @var float $lon
     */
    public $lon = 0.0;


    /**
     * Api key
     *
     * @var string $apiKey
     */
    public $apiKey = '';

    /**
     * Clear cache
     *
     * @var string $clearCache
     */
    public $clearCache = '';

    /**
     * Country
     *
     * @var string $country
     */
    public $country = '';


    /**
     * Record storage page
     *
     * @var int $recordStoragePage
     */
    public $recordStoragePage = 0;

    /**
     * Name of current task record
     *
     * @var string $name
     */
    public $name = '';

    /**
     * Error notification on or off?
     *
     * @var bool $errorNotification
     */
    public $errorNotification = false;

    /**
     * E-Mail address of sender
     *
     * @var string $emailSender
     */
    public $emailSender = '';

    /**
     * Name of sender
     *
     * @var string $emailSenderName
     */
    public $emailSenderName = '';

    /**
     * E-Mail of receiver
     *
     * @var string $emailReceiver
     */
    public $emailReceiver = '';



    /**
     * This method is the heart of the scheduler task. It will be fired if the scheduler
     * gets executed
     *
     * @return bool
     */
    public function execute(): bool
    {
        $logEntry = [];
        $logEntry[] = '**************** [%s] ****************';
        $logEntry[] = 'Scheduler: "JWeiland\\weather2\\Task\\OpenWeatherMapTask"';
        $logEntry[] = 'Scheduler settings: %s';
        $logEntry[] = 'Date format: "m.d.Y - H:i:s"';
        $this->logger->info(sprintf(
            implode("\n", $logEntry),
            date('m.d.Y - H:i:s', $GLOBALS['EXEC_TIME']),
            json_encode($this)
        ));

        $this->removeOldRecordsFromDb();


        $localNames = $this->fetchLocalNames($this->city,$this->apiKey);


        $this->url = sprintf(
            '%s?lat=%s&lon=%s&units=%s&exclude=minutely&appid=%s',
            'https://api.openweathermap.org/data/2.5/onecall',
            $this->lat,
            $this->lon,
            'metric',
            $this->apiKey
        );
        try {
            $response = GeneralUtility::makeInstance(RequestFactory::class)->request($this->url);
        } catch (\Throwable $exception) {
            $errorMessage = 'Exception while fetching data from API: ' . $exception->getMessage();
            $this->logger->error($errorMessage);
            $this->sendMail(
                'Error while requesting weather data',
                $errorMessage
            );
            return false;
        }
        if (!($this->checkResponseCode($response))) {
            return false;
        }
        $this->responseClass = json_decode((string)$response->getBody());
        $this->logger->debug(sprintf('Response class: %s', json_encode($this->responseClass)));
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $persistenceManager = $objectManager->get(PersistenceManager::class);

        $persistenceManager->add($this->getWeatherInstanceForResponseClass($this->responseClass));
        $persistenceManager->persistAll();

        if (!empty($this->clearCache)) {
            $cacheService = GeneralUtility::makeInstance(CacheService::class);
            $cacheService->clearPageCache(GeneralUtility::intExplode(',', $this->clearCache));
        }

        return true;
    }

    /**
     * Checks the JSON response
     *
     * @param ResponseInterface $response
     * @return bool Returns true if given data is valid or false in case of an error
     */
    private function checkResponseCode(ResponseInterface $response): bool
    {
        if ($response->getStatusCode() === 401) {
            $this->logger->error(WeatherUtility::translate('message.api_response_401', 'openweatherapi'));
            $this->sendMail(
                'Error while requesting weather data',
                WeatherUtility::translate('message.api_response_401', 'openweatherapi')
            );
            return false;
        }
        if ($response->getStatusCode() !== 200) {
            $this->logger->error(WeatherUtility::translate('message.api_response_null', 'openweatherapi'));
            $this->sendMail(
                'Error while requesting weather data',
                WeatherUtility::translate('message.api_response_null', 'openweatherapi')
            );
            return false;
        }

        return true;
    }

    /**
     * Returns filled Weather instance
     *
     * @param \stdClass $responseClass
     * @return Weather
     */
    private function getWeatherInstanceForResponseClass($responseClass): Weather
    {
        $weather = new Weather();
        $weather->setPid((int)$this->recordStoragePage);
        $weather->setPlaceName($this->city);
        $weather->setTaskId($this->getTaskUid());
        $weather->setDateMin($responseClass->current->dt);
        $weather->setDateMax($responseClass->daily[count($responseClass->daily)-1]->dt);

        $weather->setSerializedArray(serialize(json_decode(json_encode($responseClass), true)));

        return $weather;
    }



    /**
     * Sends a mail with $subject and $body to in task selected mail receiver.
     *
     * @param string $subject
     * @param string $body
     * @return bool
     */
    private function sendMail(string $subject, string $body): bool
    {
        if (!$this->errorNotification) {
            return false;
        } // only continue if notifications are enabled

        /** @var MailMessage $mail */
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $from = null;
        $fromAddress = '';
        $fromName = '';
        if (MailUtility::getSystemFromAddress()) {
            $fromAddress = MailUtility::getSystemFromAddress();
        }
        if (MailUtility::getSystemFrom()) {
            $fromName = MailUtility::getSystemFromName();
        }
        if ($this->emailSender) {
            $fromAddress = $this->emailSender;
        }
        if ($this->emailSenderName) {
            $fromName = $this->emailSenderName;
        }

        if ($fromAddress && $fromName && $this->emailReceiver) {
            $from = [$fromAddress => $fromName];
        } else {
            $this->logger->error(
                ($this->emailReceiver === false ? 'E-Mail receiver address is missing ' : '') .
                ($fromAddress === '' ? 'E-Mail sender address ' : '') .
                ($fromName === '' ? 'E-Mail sender name is missing' : '')
            );
            return false;
        }

        $mail->setSubject($subject)->setFrom($from)->setTo([(string)$this->emailReceiver]);
        if (method_exists($mail, 'addPart')) {
            // TYPO3 < 10 (Swift_Message)
            $mail->setBody($body);
        } else {
            // TYPO3 >= 10 (Symfony Mail)
            $mail->text($body);
        }
        $mail->send();

        if ($mail->isSent()) {
            $this->logger->notice('Notification mail sent!');
            return true;
        }
        $this->logger->error('Notification mail not sent because of an error!');
        return false;
    }

    protected function removeOldRecordsFromDb(): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_weather2_domain_model_weather');
        $connection->delete(
            'tx_weather2_domain_model_weather',
            ['pid' => $this->recordStoragePage, 'task_id' => $this->getTaskUid()]
        );
    }


    /**
     * Returns the information shown in the task-list
     *
     * @return string Information-text fot the scheduler task-list
     */
    public function getAdditionalInformation()
    {
        $message = 'Place: '.$this->city;

        return $message;
    }


    private function fetchLocalNames(
        $query,
        $apiKey
    ) {

        $url = sprintf(
            '%s?q=%s&units=%s&appid=%s',
            'https://api.openweathermap.org/geo/1.0/direct',
            $query,
            'metric',
            $apiKey
        );
        try {
            $response = GeneralUtility::makeInstance(RequestFactory::class)->request($url);
        } catch (\Throwable $exception) {
            $errorMessage = 'Exception while fetching data from API: ' . $exception->getMessage();
            $this->logger->error($errorMessage);
            return false;
        }

        $responseObj = json_decode((string)$response->getBody());

        if ($response->getStatusCode() === 200) {
            return json_decode(json_encode($responseObj[0]->local_names), true);
        }

        return [];
    }

}
