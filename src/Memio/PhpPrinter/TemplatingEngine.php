<?php

/*
 * This file is part of the memio/PHP-Printer package.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Memio\PhpPrinter;

interface TemplatingEngine
{
    /**
     * @throws TemplateNotFound
     */
    public function render(
        string $templateName,
        array $parameters = []
    ) : string;
}
