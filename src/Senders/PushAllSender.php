<?php

namespace ArtARTs36\PushAllSender\Senders;

use ArtARTs36\PushAllSender\Exceptions\PushException;
use ArtARTs36\PushAllSender\Exceptions\PushUndefinedException;
use ArtARTs36\PushAllSender\Interfaces\PusherInterface;
use ArtARTs36\PushAllSender\Push;

/**
 * Class PushAllSender
 * @package ArtARTs36\PushAllSender\Senders
 */
class PushAllSender implements PusherInterface
{
    /** @var int  */
    public const PRIORITY = 1;

    /** @var string  */
    public const API_URL = 'https://pushall.ru/api.php';

    /** @var int */
    private $channelId;

    /** @var string */
    private $apiKey;

    /** @var mixed */
    private $answer;

    /**
     * PushAllSender constructor.
     * @param $channelId
     * @param $apiKey
     */
    public function __construct($channelId, $apiKey)
    {
        $this->channelId = $channelId;
        $this->apiKey = $apiKey;
    }

    /**
     * Входная точка:
     *
     *  Формируем массив для отправки на PushAll
     *  Отправляем в this->send()
     *
     * @param Push $push
     * @return bool|mixed|null
     */
    public function push(Push $push)
    {
        $request = [
            'type' => 'broadcast',
            'id' => $this->channelId,
            'key' => $this->apiKey,
            'text' => $push->message,
            'title' => $push->title,
            'priority' => static::PRIORITY,
        ];

        if ($push->url !== null) {
            $request['url'] = $push->url;
        }

        if (($id = $push->getRecipientId())) {
            $request['type'] = 'unicast';
            $request['uid'] = $id;
        }

        return $this->send($request);
    }

    /**
     * @inheritDoc
     */
    public function pushOrFail(Push $push)
    {
        if (($msg = $this->push($push))) {
            return $msg;
        }

        if (($answer = $this->getAnswer()) && !empty($answer['error'])) {
            throw new PushException($answer['error']);
        }

        throw new PushUndefinedException();
    }

    /**
     * @param array $data
     * @return bool
     */
    private function send(array $data)
    {
        curl_setopt_array(
            $ch = curl_init(),
            [
                CURLOPT_URL => static::API_URL,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_RETURNTRANSFER => true
            ]
        );

        $result = curl_exec($ch);
        curl_close($ch);

        return $this->analyseAnswer($result);
    }

    /**
     * @param $result
     * @return bool
     */
    protected function analyseAnswer($result): bool
    {
        $this->answer = $result = json_decode($result, true) ?? null;

        if (is_array($result) && !empty($result['success']) && $result['success'] === 1) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}
