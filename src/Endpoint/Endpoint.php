<?php

namespace Zored\SpeechBundle\Endpoint;

use Symfony\Component\HttpFoundation\Response;
use Zored\SpeechBundle\Endpoint\Context\ContextInterface;
use Zored\SpeechBundle\Request\Passer\RequestPasserInterface;
use Zored\SpeechBundle\Response\Entity\Error;
use Zored\SpeechBundle\Response\ErrorCodeInterface;

class Endpoint implements EndpointInterface
{
    /**
     * @var RequestPasserInterface[]
     */
    private $passers;

    /**
     * @param RequestPasserInterface[] $passers
     */
    public function __construct(array $passers = [])
    {
        $this->passers = $passers;
    }

    /**
     * @param RequestPasserInterface[] $passers
     *
     * @return Endpoint
     */
    public function setPassers($passers)
    {
        $this->passers = $passers;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($json, ContextInterface $context)
    {
        $data = json_decode($json, true);
        if (json_last_error()) {
            return $this->getErrorContent('Could not parse JSON.');
        }

        $content = null;
        foreach ($this->passers as $type) {
            if (!$type->suitsArray($data)) {
                continue;
            }
            $content = $type->pass($json, $context);
            break;
        }

        if (empty($content)) {
            return $this->getErrorContent('Empty response for your request.', ErrorCodeInterface::INTERNAL_ERROR);
        }

        return $content;
    }

    /**
     * @param $message
     * @param int $code
     *
     * @return string
     */
    protected function getErrorContent($message, $code = ErrorCodeInterface::PARSE_ERROR)
    {
        return json_encode([
            'jsonrpc' => '2.0',
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ]);
    }
}
