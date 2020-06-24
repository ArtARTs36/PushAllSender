<?php

namespace ArtARTs36\PushAllSender\Senders;

use ArtARTs36\PushAllSender\Exceptions\PushException;
use ArtARTs36\PushAllSender\Exceptions\PushUndefinedException;
use ArtARTs36\PushAllSender\Exceptions\PushValidateException;
use ArtARTs36\PushAllSender\Exceptions\PushWrongApiKeyException;
use ArtARTs36\PushAllSender\Interfaces\PusherInterface;
use ArtARTs36\PushAllSender\Interfaces\PushValidatorInterface;
use ArtARTs36\PushAllSender\Push;
use ArtARTs36\PushAllSender\Validators\PushValidator;
use ArtARTs36\PushAllSender\Validators\Rules\MessageLengthRule;
use ArtARTs36\PushAllSender\Validators\Rules\TitleLengthRule;

/**
 * Class PushAllSender
 * @package ArtARTs36\PushAllSender\Senders
 */
class PushAllSender implements PusherInterface
{
    public const ERROR_WRONG_KEY = 'wrong key';

    /** @var string  */
    public const API_URL = 'https://pushall.ru/api.php';

    /** @var int */
    private $channelId;

    /** @var string */
    private $apiKey;

    /** @var mixed */
    protected $answer;

    /** @var PushValidatorInterface|PushValidator */
    protected $validator;

    /**
     * PushAllSender constructor.
     * @param int $channelId
     * @param string $apiKey
     * @param PushValidatorInterface|null $validator
     */
    public function __construct(int $channelId, string $apiKey, PushValidatorInterface $validator = null)
    {
        $this->channelId = $channelId;
        $this->apiKey = $apiKey;
        $this->validator = $validator ?? new PushValidator([
            TitleLengthRule::class,
            MessageLengthRule::class,
        ]);
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
    public function push(Push $push): bool
    {
        if (!$this->validator->validate($push)) {
            return false;
        }

        $request = [
            'type' => 'broadcast',
            'id' => $this->channelId,
            'key' => $this->apiKey,
            'text' => $push->message,
            'title' => $push->title,
            'priority' => $push->getPriority(),
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
     * @throws PushValidateException
     */
    public function pushOrFail(Push $push): bool
    {
        if (!$this->validator->validate($push)) {
            throw new PushValidateException($this->validator->getLastErrorRule());
        }

        if (($msg = $this->push($push))) {
            return $msg;
        }

        if (($answer = $this->getAnswer()) && !empty($answer['error'])) {
            switch ($answer['error']) {
                case static::ERROR_WRONG_KEY:
                    throw new PushWrongApiKeyException();

                default:
                    throw new PushException($answer['error']);
            }
        }

        throw new PushUndefinedException();
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function send(array $data): bool
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

        $this->parseAnswer($result);

        return $this->analyseAnswer();
    }

    /**
     * @param mixed $answer
     */
    protected function parseAnswer($answer): void
    {
        $this->answer = json_decode($answer, true) ?? null;
    }

    /**
     * @return bool
     */
    protected function analyseAnswer(): bool
    {
        if (is_array($this->answer) && !empty($this->answer['success']) && $this->answer['success'] === 1) {
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
