<?php
declare(strict_types=1);

namespace Middlewares\Formatter;

use Throwable;

class PlainFormatter implements FormatterInterface
{
    public function contentType(): string
    {
        return 'text/plain';
    }

    public function format(Throwable $error): string
    {
        return sprintf("%s %s\n%s", get_class($error), $error->getCode(), $error->getMessage());
    }
}
