<?php

/*
 * This file is part of the memio/PHP-Printer package.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Memio\PhpPrinter\Simpla;

use Memio\PhpPrinter\TemplateNotFound;

class SimplaTemplateCollection
{
    private $templates = [];

    public function add(string $name, string $path, int $priority = 0)
    {
        $this->templates[$name][$priority][] = $path;
        krsort($this->templates[$name]);
    }

    public function get(string $name) : string
    {
        if (false === isset($this->templates[$name])) {
            throw new TemplateNotFound("No templates found for: $name");
        }
        $highestPriority = current($this->templates[$name]);
        $latestAddition = end($highestPriority);

        return $latestAddition;
    }
}
