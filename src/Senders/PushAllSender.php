<?php

namespace ArtARTs36\PushAllSender\Senders;

use ArtARTs36\PushAllSender\Exceptions\PushException;
use ArtARTs36\PushAllSender\Exceptions\PushUndefinedException;
use ArtARTs36\PushAllSender\Exceptions\PushValidateException;
use ArtARTs36\PushAllSender\Exceptions\PushWrongApiKeyException;
use ArtARTs36\PushAllSender\Interfaces\PusherInterface;
use ArtARTs36\PushAllSender\Interfaces\PushValidatorInterface;
use ArtARTs36\PushAllSender\Push;
use ArtARTs36\PushAllSender\Requests\PushAllRequest;
use ArtARTs36\PushAllSender\Validators\PushValidator;
use ArtARTs36\PushAllSender\Validators\Rules\ActionsCountRule;
use ArtARTs36\PushAllSender\Validators\Rules\MessageLengthRule;
use ArtARTs36\PushAllSender\Validators\Rules\PriorityRule;
use ArtARTs36\PushAllSender\Validators\Rules\TitleLengthRule;
use ArtARTs36\PushAllSender\Validators\Rules\TypeRule;

class PushAllSender implements PusherInterface
{
    public const ERROR_WRONG_KEY = 'wrong key';
    public const API_URL = 'https://pushall.ru/api.php';

    /** @var int */
    private $channelId;

    /** @var string */
    private $apiKey;

    /** @var mixed */
    protected $answer;

    /** @var PushValidatorInterface */
    protected $validator;

    public function __construct(int $channelId, string $apiKey, ?PushValidatorInterface $validator = null)
    {
        $this->channelId = $channelId;
        $this->apiKey = $apiKey;
        $this->validator = $validator ?? new PushValidator([
            TitleLengthRule::class,
            MessageLengthRule::class,
            PriorityRule::class,
            TypeRule::class,
            ActionsCountRule::class,
        ]);
    }

    /**
     * Входная точка:
     *
     *  Формируем массив для отправки на PushAll
     *  Отправляем в this->send()
     */
    public function push(Push $push): bool
    {
        if (! $this->validator->validate($push)) {
            return false;
        }

        $request = new PushAllRequest(
            $this->channelId,
            $this->apiKey,
            $push
        );

        return $this->send($request->getAttributes());
    }

    /**
     * @inheritDoc
     */
    public function pushOrFail(Push $push): bool
    {
        if (! $this->validator->validate($push) && $this->validator->getLastErrorRule()) {
            throw new PushValidateException($this->validator->getLastErrorRule());
        }

        if (($sendStatus = $this->push($push))) {
            return $sendStatus;
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
     * @param array<string, mixed> $data
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
