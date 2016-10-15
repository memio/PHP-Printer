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

class ComposedSimplaRule implements SimplaRule
{
    private $simplaRules = [];

    public function add(SimplaRule $simplaRule, int $priority = 0)
    {
        $this->simplaRules[$priority][] = $simplaRule;
        krsort($this->simplaRules);
    }

    public function apply(string $template, array $parameters = []) : string
    {
        foreach ($this->simplaRules as $simplaRules) {
            foreach ($simplaRules as $simplaRule) {
                $template = $simplaRule->apply($template, $parameters);
            }
        }

        return $template;
    }
}
