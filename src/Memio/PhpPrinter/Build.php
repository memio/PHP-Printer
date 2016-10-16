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

use Memio\PhpPrinter\Simpla\Filesystem\FileGetContentsSimplaFilesystem;
use Memio\PhpPrinter\Simpla\Rule\ComposedSimplaRule;
use Memio\PhpPrinter\Simpla\Rule\PlaceholderSimplaRule;
use Memio\PhpPrinter\Simpla\SimplaTemplateCollection;
use Memio\PhpPrinter\Simpla\SimplaTemplatingEngine;

class Build
{
    private $composedSimplaRule;
    private $simplaTemplateCollection;
    private $simplaTemplatingEngine;

    public function composedSimplaRule() : ComposedSimplaRule
    {
        if (null === $this->composedSimplaRule) {
            $this->composedSimplaRule = new ComposedSimplaRule();
            $this->composedSimplaRule->add(new PlaceholderSimplaRule());
        }

        return $this->composedSimplaRule;
    }

    public function simplaTemplateCollection() : SimplaTemplateCollection
    {
        if (null === $this->simplaTemplateCollection) {
            $this->simplaTemplateCollection = new SimplaTemplateCollection();
        }

        return $this->simplaTemplateCollection;
    }

    public function simplaTemplatingEngine() : SimplaTemplatingEngine
    {
        if (null === $this->simplaTemplatingEngine) {
            $this->simplaTemplatingEngine = new SimplaTemplatingEngine(
                new FileGetContentsSimplaFilesystem(),
                $this->composedSimplaRule(),
                $this->simplaTemplateCollection()
            );
        }

        return $this->simplaTemplatingEngine;
    }
}
