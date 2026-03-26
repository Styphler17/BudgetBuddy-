<?php
/**
 * Centralized input validator.
 *
 * Usage:
 *   $v = new Validator(['email' => 'test@x.com', 'amount' => '-5']);
 *   $v->required('email')->email('email')->positive('amount');
 *   if ($v->fails()) { $errors = $v->errors(); }
 */
class Validator {

    private array $data;
    private array $errors = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    /** Field must be present and non-empty. */
    public function required(string $field, string $label = ''): static {
        $label = $label ?: ucfirst($field);
        if (!isset($this->data[$field]) || trim((string)$this->data[$field]) === '') {
            $this->errors[$field] = "{$label} is required.";
        }
        return $this;
    }

    /** Must be a valid email address. */
    public function email(string $field, string $label = ''): static {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label} must be a valid email address.";
        }
        return $this;
    }

    /** Minimum string length. */
    public function minLength(string $field, int $min, string $label = ''): static {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && mb_strlen((string)$this->data[$field]) < $min) {
            $this->errors[$field] = "{$label} must be at least {$min} characters.";
        }
        return $this;
    }

    /** Maximum string length. */
    public function maxLength(string $field, int $max, string $label = ''): static {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && mb_strlen((string)$this->data[$field]) > $max) {
            $this->errors[$field] = "{$label} must not exceed {$max} characters.";
        }
        return $this;
    }

    /** Must be a numeric value. */
    public function numeric(string $field, string $label = ''): static {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && $this->data[$field] !== '' && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "{$label} must be a number.";
        }
        return $this;
    }

    /** Must be a positive number (> 0). */
    public function positive(string $field, string $label = ''): static {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && $this->data[$field] !== '') {
            if (!is_numeric($this->data[$field]) || (float)$this->data[$field] <= 0) {
                $this->errors[$field] = "{$label} must be a positive number.";
            }
        }
        return $this;
    }

    /** Must be one of the allowed values. */
    public function in(string $field, array $allowed, string $label = ''): static {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && !in_array($this->data[$field], $allowed, true)) {
            $this->errors[$field] = "{$label} contains an invalid value.";
        }
        return $this;
    }

    /** Must be a valid date (Y-m-d). */
    public function date(string $field, string $label = ''): static {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && $this->data[$field] !== '') {
            $d = DateTime::createFromFormat('Y-m-d', $this->data[$field]);
            if (!$d || $d->format('Y-m-d') !== $this->data[$field]) {
                $this->errors[$field] = "{$label} must be a valid date (YYYY-MM-DD).";
            }
        }
        return $this;
    }

    /** Two fields must match. */
    public function matches(string $field, string $otherField, string $label = ''): static {
        $label = $label ?: ucfirst($field);
        if (($this->data[$field] ?? '') !== ($this->data[$otherField] ?? '')) {
            $this->errors[$field] = "{$label} does not match.";
        }
        return $this;
    }

    /** Returns true if there are validation errors. */
    public function fails(): bool {
        return !empty($this->errors);
    }

    /** Returns the array of errors keyed by field name. */
    public function errors(): array {
        return $this->errors;
    }

    /** Returns the first error message, or null. */
    public function firstError(): ?string {
        return empty($this->errors) ? null : array_values($this->errors)[0];
    }

    /** Returns the validated/sanitized value for a field. */
    public function get(string $field, mixed $default = null): mixed {
        return $this->data[$field] ?? $default;
    }
}
