<?php

namespace Console\App\Service;

class Slack
{
    /**
     * @var string
     */
    protected $slackToken;

    private const MAINTAINER_MEMBERS = [
        'atomiix' => '<@UPBLRHWCU>',
        'eternoendless' => '<@U5GRYPEUC>',
        'jolelievre' => '<@UC4KB9BJS>',
        'matks' => '<@UB61HUD2A>',
        'matthieu-rolland' => '<@UKW0VAT8S>',
        'NeOMakinG' => '<@UQNF13DAR>',
        'PierreRambaud' => '<@UAC6UKKK8>',
        'Progi1984' => '<@UL16KUPC5>',
        'Quetzacoalt91' => '<@U02FDSY43>',
        'rokaszygmantas' => 'rokaszygmantas',
        'sowbiba' => '<@USKJT4C4Q>',
    ];

    public function __construct(string $slackToken = null)
    {
        $this->slackToken = $slackToken;
    }

    public function linkGithubUsername(string $message): string
    {
        return str_replace(array_keys(self::MAINTAINER_MEMBERS), array_values(self::MAINTAINER_MEMBERS), $message);
    }

    /**
     * @param string $channel
     * @param string $message
     */
    public function sendNotification(string $channel, string $message = 'test')
    {
        $ch = curl_init('https://slack.com/api/chat.postMessage');
        $data = http_build_query([
            'token' => $this->slackToken,
            'username' => 'PrestHubot',
            'channel' => $channel,
            'text' => $message,
            // Find and link channel names and usernames
            'link_names' => true,
            // Slack markup parsing
            'mrkdwn' => true,
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
}