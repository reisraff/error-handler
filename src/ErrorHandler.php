<?php
declare(strict_types=1);

namespace Middlewares;

use Middlewares\Formatter\FormatterInterface;
use Middlewares\Formatter\PlainFormatter;
use Middlewares\Utils\Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class ErrorHandler implements MiddlewareInterface
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    /** @var FormatterInterface[] */
    private $formatters = [];

    public function __construct(
        ResponseFactoryInterface $responseFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->responseFactory = $responseFactory ?? Factory::getResponseFactory();
        $this->streamFactory = $streamFactory ?? Factory::getStreamFactory();
    }

    /**
     * Add additional error formatters
     */
    public function addFormatters(FormatterInterface ...$formatters): self
    {
        foreach ($formatters as $formatter) {
            $this->formatters[$formatter->contentType()] = $formatter;
        }

        return $this;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->errorResponse($this->errorFormatter($request), $e);
        }
    }

    protected function errorFormatter(ServerRequestInterface $request): FormatterInterface
    {
        $accept = $request->getHeaderLine('Accept');

        foreach ($this->formatters as $type => $formatter) {
            if (stripos($accept, $type) !== false) {
                return $formatter;
            }
        }

        return new PlainFormatter();
    }

    protected function errorResponse(FormatterInterface $formatter, Throwable $e): ResponseInterface
    {
        $responseBody = $this->streamFactory->createStream($formatter->format($e));

        $response = $this->responseFactory->createResponse($this->errorStatus($e));
        $response = $response->withHeader('Content-Type', $formatter->contentType());
        $response = $response->withBody($responseBody);

        return $response;
    }

    protected function errorStatus(Throwable $e): int
    {
        if ($e instanceof HttpErrorException) {
            return $e->getCode();
        }

        if (method_exists($e, 'getStatusCode')) {
            return $e->getStatusCode();
        }

        return 500;
    }
}
