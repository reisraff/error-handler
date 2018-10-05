<?php
declare(strict_types=1);

namespace Middlewares\Formatter;

use Throwable;

use function Safe\json_encode;

class JsonFormatter implements FormatterInterface
{
    public function contentType(): string
    {
        return 'application/json';
    }

    public function format(Throwable $error): string
    {
        $json = [
            'type' => get_class($error),
            'code' => $error->getCode(),
            'message' => $error->getMessage(),
        ];

        return json_encode($json);
    }
}
