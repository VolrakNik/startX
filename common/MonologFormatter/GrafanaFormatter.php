<?php

namespace Common\MonologFormatter;

use Monolog\Formatter\LineFormatter;

class GrafanaFormatter extends LineFormatter
{
    /**
     * @param string|null $dateFormat
     * @param bool $allowInlineLineBreaks
     * @param bool $ignoreEmptyContextAndExtra
     * @param bool $includeStacktraces
     */
    public function __construct(
        ?string $dateFormat = null,
        bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = false,
        bool $includeStacktraces = false
    ) {
        parent::__construct(
            "%datetime% | %level_name% | %message% | %context%" . PHP_EOL,
            $dateFormat,
            $allowInlineLineBreaks,
            $ignoreEmptyContextAndExtra,
            $includeStacktraces
        );
    }
}