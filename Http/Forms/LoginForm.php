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
     * @throws ValidationException
     */
    public static function validate($attributes)
    {
        $instance = new static($attributes);

        return $instance->failed() ? $instance->throw() : $instance;
    }

    public function throw()
    {
        ValidationException::throw($this->errors(), $this->attributes);
    }

    public function failed(): bool
    {
        return count($this->errors);
    }


    public function errors(): array
    {
        return $this->errors;
    }

    public function error($field, $message): static
    {
        $this->errors[$field] = $message;

        return $this;
    }
}