<?php
declare(strict_types=1);

namespace Middlewares\Formatter;

use Throwable;

class HtmlFormatter implements FormatterInterface
{
    public function contentType(): string
    {
        return 'text/html';
    }

    public function format(Throwable $error): string
    {
        $type = get_class($error);
        $code = $error->getCode();
        $message = $error->getMessage();

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>$type $code</title>
    <style>html{font-family: sans-serif;}</style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>$type $code</h1>
    <p>$message</p>
</body>
</html>
HTML;
    }
}
