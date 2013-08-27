<?php
namespace nnscr\GISL\Method;

use Symfony\Component\Intl\NumberFormatter\NumberFormatter;

class ParseNumberMethod implements MethodInterface
{
    /**
     * Get the callable method name
     *
     * @return string
     */
    public function getName()
    {
        return 'parse_number';
    }

    /**
     * Call the method with the given arguments
     *
     * @param  mixed $target
     * @param  array $arguments
     * @return mixed
     */
    public function call($target, array $arguments)
    {
        $formatter = new \NumberFormatter($arguments[0], \NumberFormatter::PATTERN_DECIMAL);
        return $formatter->parse($target);
    }
}