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

class ComposedSimplaRuleSpec extends ObjectBehavior
{
    const PARAMETERS = [];

    const TEMPLATE = '';
    const FIRST_TEMPLATE = '1';
    const SECOND_TEMPLATE = '2';
    const THIRD_TEMPLATE = '3';

    const LOWEST_PRIORITY = -1;
    const MEDIUM_PRIORITY = 0;
    const HIGHEST_PRIORITY = 1;

    function it_is_a_simpla_rule_implementation()
    {
        $this->shouldImplement(SimplaRule::class);
    }

    function it_applies_all_aggregated_rules_according_to_their_priorities(
        SimplaRule $firstRule,
        SimplaRule $secondRule,
        SimplaRule $thirdRule
    ) {
        $this->add($firstRule, self::LOWEST_PRIORITY);
        $this->add($secondRule, self::HIGHEST_PRIORITY);
        $this->add($thirdRule, self::MEDIUM_PRIORITY);

        $template = self::TEMPLATE;
        $secondRule->apply($template, self::PARAMETERS)->willReturn(
            $template .= self::SECOND_TEMPLATE
        );
        $thirdRule->apply($template, self::PARAMETERS)->willReturn(
            $template .= self::THIRD_TEMPLATE
        );
        $firstRule->apply($template, self::PARAMETERS)->willReturn(
            $template .= self::FIRST_TEMPLATE
        );

        $this->apply(self::TEMPLATE, self::PARAMETERS)->shouldBe($template);
    }
}
