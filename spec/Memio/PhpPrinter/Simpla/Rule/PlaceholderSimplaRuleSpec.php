<?php

/*
 * This file is part of the memio/PHP-Printer package.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Memio\PhpPrinter\Simpla\Rule;

use Memio\PhpPrinter\Simpla\SimplaRule;
use PhpSpec\ObjectBehavior;

class PlaceholderSimplaRuleSpec extends ObjectBehavior
{
    const NAME = 'name';
    const VALUE = 'world';
    const PARAMETERS = [
        self::NAME => self::VALUE,
    ];
    const MISSING_PARAMETERS = [
        'question' => 'how are you?',
    ];

    const PLACEHOLDER = '%'.self::NAME.'%';
    const TEMPLATE = 'Hello '.self::PLACEHOLDER.'!';

    const REPLACED_TEMPLATE = 'Hello '.self::VALUE.'!';

    function it_is_a_simpla_rule_implementation()
    {
        $this->shouldImplement(SimplaRule::class);
    }

    function it_replaces_placeholders_delimited_with_percent_characters()
    {
        $this->apply(self::TEMPLATE, self::PARAMETERS)->shouldBe(
            self::REPLACED_TEMPLATE
        );
    }

    function it_ignores_parameters_not_present_in_template()
    {
        $this->apply(self::TEMPLATE, self::MISSING_PARAMETERS)->shouldBe(
            self::TEMPLATE
        );
    }
}
