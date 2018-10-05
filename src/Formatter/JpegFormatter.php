<?php
declare(strict_types=1);

namespace Middlewares\Formatter;

use Throwable;

use function Safe\imagejpeg;

class JpegFormatter extends AbstractImageFormatter
{
    public function contentType(): string
    {
        return 'image/jpeg';
    }

    public function format(Throwable $error): string
    {
        ob_start();
        imagejpeg($this->createImage($error));
        return ob_get_clean();
    }
}
