<?php

/*
 * This file is part of the memio/PHP-Printer package.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Memio\PhpPrinter\Simpla;

use Memio\PhpPrinter\Simpla\SimplaFilesystem;
use Memio\PhpPrinter\Simpla\SimplaRule;
use Memio\PhpPrinter\Simpla\SimplaTemplateCollection;
use Memio\PhpPrinter\TemplateNotFound;
use Memio\PhpPrinter\TemplatingEngine;
use PhpSpec\ObjectBehavior;

class SimplaTemplatingEngineSpec extends ObjectBehavior
{
    const PARAMETERS = [
        'name' => 'myDependency',
    ];

    const NAME = 'property';
    const PATH = '/tmp/property.tpl';
    const TEMPLATE = '    private $:name;';
    const CHANGED_TEMPLATE = '    private $myDependency;';

    function it_is_a_templating_engine_implementation(
        SimplaFilesystem $simplaFilesystem,
        SimplaRule $simplaRule
    ) {
        $this->beConstructedWith(
            $simplaFilesystem,
            $simplaRule,
            new SimplaTemplateCollection()
        );

        $this->shouldImplement(TemplatingEngine::class);
    }

    function it_applies_rules_to_the_found_template(
        SimplaFilesystem $simplaFilesystem,
        SimplaRule $simplaRule
    ) {
        $simplaTemplateCollection = new SimplaTemplateCollection();
        $simplaTemplateCollection->add(self::NAME, self::PATH);
        $this->beConstructedWith(
            $simplaFilesystem,
            $simplaRule,
            $simplaTemplateCollection
        );

        $simplaFilesystem->open(self::PATH)->willReturn(self::TEMPLATE);
        $simplaRule->apply(self::TEMPLATE, self::PARAMETERS)->willReturn(
            self::CHANGED_TEMPLATE
        );

        $this->render(self::NAME, self::PARAMETERS)->shouldBe(
            self::CHANGED_TEMPLATE
        );
    }

    function it_cannot_apply_rules_if_the_template_is_not_registered(
        SimplaFilesystem $simplaFilesystem,
        SimplaRule $simplaRule
    ) {
        $simplaTemplateCollection = new SimplaTemplateCollection();
        $this->beConstructedWith(
            $simplaFilesystem,
            $simplaRule,
            $simplaTemplateCollection
        );

        $simplaFilesystem->open(self::PATH)->shouldNotBeCalled();
        $simplaRule->apply(
            self::TEMPLATE,
            self::PARAMETERS
        )->shouldNotBeCalled();

        $this->shouldThrow(TemplateNotFound::class)->duringRender(
            self::NAME,
            self::PARAMETERS
        );
    }

    function it_cannot_apply_rules_if_the_template_cannot_be_opened(
        SimplaFilesystem $simplaFilesystem,
        SimplaRule $simplaRule
    ) {
        $simplaTemplateCollection = new SimplaTemplateCollection();
        $simplaTemplateCollection->add(self::NAME, self::PATH);
        $this->beConstructedWith(
            $simplaFilesystem,
            $simplaRule,
            $simplaTemplateCollection
        );

        $simplaFilesystem->open(self::PATH)->willThrow(
            TemplateNotFound::class
        );
        $simplaRule->apply(
            self::TEMPLATE,
            self::PARAMETERS
        )->shouldNotBeCalled();

        $this->shouldThrow(TemplateNotFound::class)->duringRender(
            self::NAME,
            self::PARAMETERS
        );
    }
}
