<?php
declare(strict_types=1);

namespace Middlewares\Formatter;

use Throwable;

class XmlFormatter implements FormatterInterface
{
    public function contentType(): string
    {
        return 'text/xml';
    }

    public function format(Throwable $error): string
    {
        $type = get_class($error);
        $code = $error->getCode();
        $message = $error->getMessage();

        return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<error>
    <type>$type</type>
    <code>$code</code>
    <message>$message</message>
</error>
XML;
    }
}
