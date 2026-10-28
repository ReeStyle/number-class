<?php

namespace ReeStyleIt\NumberClass\Conversion\Ordinal;

use IntlException;
use Locale;
use NumberFormatter;
use ReeStyleIt\NumberClass\Exceptions\OrdinalFormattingException;

/** @internal Converts values already validated by the parent Ordinal class. */
readonly class Words
{
    public function __construct(private int $value, private string $locale) {}

    public function format(): string
    {
        try {
            $formatter = new NumberFormatter($this->locale, NumberFormatter::SPELLOUT);
            $rulesets = explode(';', $formatter->getTextAttribute(NumberFormatter::PUBLIC_RULESETS));
            $ruleset = null;

            foreach (['%spellout-ordinal', '%spellout-ordinal-masculine'] as $candidate) {
                if (in_array($candidate, $rulesets, true)) {
                    $ruleset = $candidate;
                    break;
                }
            }

            if ($ruleset === null) {
                throw new OrdinalFormattingException('Ordinal words are not supported for locale ' . $this->locale . '.');
            }

            if (! $formatter->setTextAttribute(NumberFormatter::DEFAULT_RULESET, $ruleset)) {
                throw new OrdinalFormattingException($formatter->getErrorMessage());
            }

            $result = $formatter->format($this->value, NumberFormatter::TYPE_INT64);
        } catch (IntlException $exception) {
            throw new OrdinalFormattingException($exception->getMessage(), previous: $exception);
        }

        if ($result === false) {
            throw new OrdinalFormattingException($formatter->getErrorMessage());
        }

        if ($this->value === 0 && Locale::getPrimaryLanguage($this->locale) === 'nl') {
            return 'nulde';
        }

        return str_replace("\u{00AD}", '', $result);
    }
}
