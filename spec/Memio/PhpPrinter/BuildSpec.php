<?php

/*
 * This file is part of the memio/PHP-Printer package.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Memio\PhpPrinter;

use Memio\PhpPrinter\Simpla\Rule\ComposedSimplaRule;
use Memio\PhpPrinter\Simpla\SimplaTemplateCollection;
use Memio\PhpPrinter\Simpla\SimplaTemplatingEngine;
use PhpSpec\ObjectBehavior;

class BuildSpec extends ObjectBehavior
{
    function it_builds_a_composed_simpla_rule()
    {
        $this->composedSimplaRule()->shouldHaveType(
            ComposedSimplaRule::class
        );
    }

    function it_builds_a_simpla_template_collection()
    {
        $this->simplaTemplateCollection()->shouldHaveType(
            SimplaTemplateCollection::class
        );
    }

    function it_builds_a_simpla_templating_engine()
    {
        $this->simplaTemplatingEngine()->shouldHaveType(
            SimplaTemplatingEngine::class
        );
    }
}
