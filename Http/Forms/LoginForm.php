<?php

namespace  Http\Forms;

use Core\ValidationException;
use Core\Validator;

class  LoginForm
{
    protected array $errors = [];
    public function __construct(public array $attributes)
    {
        if (! Validator::email($attributes['email'])){
            $this->errors['email'] = 'Please provide a valid email address';
        }

        if (! Validator::string($attributes['password'], 7, 255)){
            $this->errors['password'] = 'Please provide a valid password';
        }
    }

    /**
     * @param $attributes
     * @return LoginForm|null
     * @throws ValidationException
     */
    public static function validate($attributes): ?LoginForm
    {
        $instance = new static($attributes);

        return $instance->failed() ? $instance->throw() : $instance;
    }

    /**
     * @return void
     * @throws ValidationException
     */
    public function throw(): void
    {
        ValidationException::throw($this->errors(), $this->attributes);
    }

    /**
     * @return bool
     */
    public function failed(): bool
    {
        return count($this->errors);
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * @param $field
     * @param $message
     * @return $this
     */
    public function error($field, $message): static
    {
        $this->errors[$field] = $message;

        return $this;
    }
}