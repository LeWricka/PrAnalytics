<?php

namespace App\Infrastructure\Controller\Corona;

use Borsaco\TelegramBotApiBundle\Service\Bot;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebScraper\ApiClient\Client;

class CoronaController
{
    private const SCRAPY_API_TOKEN = '1nQi5iU7mSuVfAS7aS69SVX5wMiAQSQtEvr0GZCffArEadY155ZwC0EXv7D4';
    const COUNT_FILE = "/tmp/count.txt";

    /**
     * @param Request         $request
     * @param Bot             $bot
     *
     * @param LoggerInterface $logger
     *
     * @return Response
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function __invoke(Request $request, Bot $bot, LoggerInterface $logger)
    {
        $jobData = $this->getJobData($request);

        if ($this->isThereAnyChange($jobData, $logger)) {
            $this->sendTelegramMessage($bot, $jobData);
        }

        return new Response('ok');
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function getJobData(Request $request): array
    {
        $client = new Client(['token' => self::SCRAPY_API_TOKEN]);
        $outputFile = "/tmp/scrapingjob{$request->request->get('scrapingjob_id')}.json";
        $client->downloadScrapingJobJSON($request->request->get('scrapingjob_id'), $outputFile);

        $jobData = json_decode(file_get_contents($outputFile), true);
        unlink($outputFile);

        return $jobData;
    }

    /**
     * @param                 $jobData
     *
     * @param LoggerInterface $logger
     *
     * @return bool
     */
    private function isThereAnyChange($jobData, LoggerInterface $logger): bool
    {
        $file = escapeshellarg(self::COUNT_FILE);
        $line = `tail -n 1 $file`;

        $countFile = fopen(self::COUNT_FILE, "w");
        fwrite($countFile, $jobData['positivos-acumulados']);
        $logger->error('jobData:'.$jobData['positivos-acumulados']);
        $logger->error('Previous:'.$line);

        return $jobData['positivos-acumulados'] !== $line;
    }

    /**
     * @param Bot $bot
     * @param     $jobData
     *
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function sendTelegramMessage(Bot $bot, $jobData): void
    {
        $coronaNavarra = $bot->getBot('corona_navarra');
        $coronaNavarra->sendMessage(
            [
                'chat_id' => '-1001359306229',
                'text' => "SITUACIÓN ACTUAL:\nInfectados: ".$jobData['positivos-acumulados']."\nFallecimientos: "
                    .$jobData['fallecimientos']."\nAltas: ".$jobData['altas']."\n\nFuente: https://coronavirus.navarra.es/"
            ]
        );
    }
}
