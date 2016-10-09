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

use Memio\PhpPrinter\TemplateNotFound;
use PhpSpec\ObjectBehavior;

class SimplaTemplateCollectionSpec extends ObjectBehavior
{
    const FIRST_TEMPLATE_NAME = 'first';
    const FIRST_TEMPLATE = [
        'name' => self::FIRST_TEMPLATE_NAME,
        'path' => '/tmp/first.tpl',
    ];
    const SECOND_TEMPLATE = [
        'name' => 'second',
        'path' => '/tmp/second.tpl',
    ];
    const ALTERNATIVE_FIRST_TEMPLATE = [
        'name' => self::FIRST_TEMPLATE_NAME,
        'path' => '/dev/null',
    ];
    const LOW_PRIORITY = -23;
    const HIGH_PRIORITY = 42;

    function it_is_empty_at_first()
    {
        $this->shouldThrow(
            TemplateNotFound::class
        )->duringGet(self::FIRST_TEMPLATE['name']);
    }

    function it_gets_the_path_of_a_template_that_has_been_added()
    {
        $this->add(
            self::FIRST_TEMPLATE['name'],
            self::FIRST_TEMPLATE['path']
        );
        $this->add(
            self::SECOND_TEMPLATE['name'],
            self::SECOND_TEMPLATE['path']
        );

        $this->get(
            self::FIRST_TEMPLATE['name']
        )->shouldBe(self::FIRST_TEMPLATE['path']);
    }

    function it_cannot_get_the_path_of_a_template_that_have_not_been_added()
    {
        $this->add(self::FIRST_TEMPLATE['name'], self::FIRST_TEMPLATE['path']);

        $this->shouldThrow(
            TemplateNotFound::class
        )->duringGet(self::SECOND_TEMPLATE['name']);
    }

    function it_can_overwrite_a_template_using_priorities()
    {
        $this->add(
            self::FIRST_TEMPLATE['name'],
            self::FIRST_TEMPLATE['path'],
            self::LOW_PRIORITY
        );
        $this->add(
            self::ALTERNATIVE_FIRST_TEMPLATE['name'],
            self::ALTERNATIVE_FIRST_TEMPLATE['path'],
            self::HIGH_PRIORITY
        );

        $this->get(
            self::FIRST_TEMPLATE['name']
        )->shouldBe(self::ALTERNATIVE_FIRST_TEMPLATE['path']);
    }
}
