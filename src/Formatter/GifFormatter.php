<?php
declare(strict_types=1);

namespace Middlewares\Formatter;

use Throwable;

use function Safe\imagegif;

class GifFormatter extends AbstractImageFormatter
{
    public function contentType(): string
    {
        return 'image/gif';
    }

    public function format(Throwable $error): string
    {
        ob_start();
        imagegif($this->createImage($error));
        return ob_get_clean();
    }
}
