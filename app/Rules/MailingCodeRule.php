<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MailingCodeRule implements ValidationRule
{
    /**
     * Allowed postal code patterns for:
     * - US
     * - Canada
     * - UK
     */
    protected array $patterns = [
        // US ZIP codes: 12345 or 12345-6789
        '/^\d{5}(-\d{4})?$/',

        // Canada: A1A 1A1 (space optional)
        '/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z]\s?\d[ABCEGHJ-NPRSTV-Z]\d$/i',

        // UK: very complex postcode system — canonical regex:
        '/^([Gg][Ii][Rr]\s?0[Aa]{2}|((([A-Za-z][0-9]{1,2})|' .
        '(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|' .
        '(([A-Za-z][0-9][A-Za-z])|' .
        '([A-Za-z][A-Ha-hJ-Yj-y][0-9][A-Za-z]?))))\s?[0-9][A-Za-z]{2}))$/',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || trim((string)$value) === '') {
            $fail('The :attribute must be a valid mailing/postal code.');
            return;
        }

        $code = trim((string)$value);

        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $code) === 1) {
                return; // Valid
            }
        }

        $fail('The :attribute must be a valid Canadian, US, or UK postal code.');
    }
}
