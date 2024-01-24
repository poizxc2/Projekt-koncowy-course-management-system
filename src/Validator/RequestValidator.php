<?php

namespace App\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class RequestValidator
{
    public function __construct(
        private ValidatorInterface $validatorInterface
    ) {}

    public function validate(object $request): array
    {
        $errors = $this->validatorInterface->validate($request);

        $response = [];
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $field = $error->getPropertyPath();
                $message = $error->getMessage();
                $response[$field] = $message;
            }
        }
        return $response;
    }
}