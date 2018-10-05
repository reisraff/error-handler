<?php
declare(strict_types=1);

namespace Middlewares\Formatter;

use Throwable;

interface FormatterInterface
{
    /**
     * Get the output content type
     */
    public function contentType(): string;

    /**
     * Format an error as a string
     */
    public function format(Throwable $error): string;
}
