<?php

/*
 * This file is part of the memio/PHP-Printer package.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Memio\PhpPrinter\Simpla\Rule;

use Memio\PhpPrinter\Simpla\SimplaRule;

class PlaceholderSimplaRule implements SimplaRule
{
    public function apply(string $template, array $parameters = []) : string
    {
        foreach ($parameters as $name => $value) {
            $template = preg_replace("/%$name%/", $value, $template);
        }

        return $template;
    }
}
