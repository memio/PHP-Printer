<?php

/*
 * This file is part of the memio/PHP-Printer package.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Memio\PhpPrinter\Simpla\Filesystem;

use Memio\PhpPrinter\Simpla\SimplaFilesystem;
use Memio\PhpPrinter\TemplateNotFound;

class FileGetContentsSimplaFilesystem implements SimplaFilesystem
{
    /**
     * @throws TemplateNotFound
     */
    public function open($filename) : string
    {
        if (false === file_exists($filename)) {
            throw new TemplateNotFound("No file found for path: $filename");
        }
        if (false === is_readable($filename)) {
            throw new TemplateNotFound("File not readable for path: $filename");
        }

        return file_get_contents($filename);
    }
}
