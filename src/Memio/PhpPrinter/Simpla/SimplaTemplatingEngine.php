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
use Memio\PhpPrinter\TemplatingEngine;

class SimplaTemplatingEngine implements TemplatingEngine
{
    private $simplaFilesystem;
    private $simplaRule;
    private $simplaTemplateCollection;

    public function __construct(
        SimplaFilesystem $simplaFilesystem,
        SimplaRule $simplaRule,
        SimplaTemplateCollection $simplaTemplateCollection
    ) {
        $this->simplaFilesystem = $simplaFilesystem;
        $this->simplaRule = $simplaRule;
        $this->simplaTemplateCollection = $simplaTemplateCollection;
    }

    /**
     * @throws TemplateNotFound
     */
    public function render(
        string $templateName,
        array $parameters = []
    ) : string {
        $path = $this->simplaTemplateCollection->get($templateName);
        $template = $this->simplaFilesystem->open($path);

        return $this->simplaRule->apply($template, $parameters);
    }
}
