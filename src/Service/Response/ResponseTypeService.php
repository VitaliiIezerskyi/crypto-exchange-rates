<?php declare(strict_types = 1);

namespace App\Service\Response;

final class ResponseTypeService
{
    public const int HTTP_OK = 200;
    public const int HTTP_CREATED = 201;
    public const int HTTP_ACCEPTED = 202;
    public const int HTTP_NO_CONTENT = 204;
    public const int HTTP_PARTIAL_CONTENT = 206;

    public const int HTTP_MOVED_PERMANENTLY = 301;
    public const int HTTP_FOUND = 302;

    public const int HTTP_BAD_REQUEST = 400;
    public const int HTTP_UNAUTHORIZED = 401;
    public const int HTTP_FORBIDDEN = 403;
    public const int HTTP_NOT_FOUND = 404;
    public const int HTTP_CONFLICT = 409;
    public const int HTTP_PRECONDITION_FAILED = 412;
    public const int HTTP_INTERNAL_SERVER_ERROR = 500;

    public function getTypes(): array
    {
        return [
            self::HTTP_OK => [
                'message' => 'All good',
                'description' => 'All good',
            ],
            self::HTTP_CREATED => [
                'message' => 'All good',
                'description' => 'All good',
            ],
            self::HTTP_ACCEPTED => [
                'message' => 'All good',
                'description' => 'All good',
            ],
            self::HTTP_NO_CONTENT => [
                'message' => 'All good',
                'description' => 'All good',
            ],
            self::HTTP_PARTIAL_CONTENT => [
                'message' => 'Partial content',
                'description' => 'Partial content',
            ],
            self::HTTP_MOVED_PERMANENTLY => [
                'message' => 'Moved permanently',
                'description' => 'Moved permanently',
            ],
            self::HTTP_FOUND => [
                'message' => 'Temporary redirect',
                'description' => 'Temporary redirect',
            ],
            self::HTTP_BAD_REQUEST => [
                'message' => 'Not all fields are filled out correctly',
                'description' => 'Not all parameters correct',
            ],
            self::HTTP_UNAUTHORIZED => [
                'message' => 'Incorrect login or password',
                'description' => 'Login or password or ApiKey not correct',
            ],
            self::HTTP_FORBIDDEN => [
                'message' => 'Oops, error occurred',
                'description' => 'Not enough rights',
            ],
            self::HTTP_NOT_FOUND => [
                'message' => 'Oops, error occurred',
                'description' => 'Object not found',
            ],
            self::HTTP_CONFLICT => [
                'message' => 'Oops, error occurred',
                'description' => 'Conflict',
            ],
            self::HTTP_PRECONDITION_FAILED => [
                'message' => 'User access disabled',
                'description' => 'User access disabled',
            ],
            self::HTTP_INTERNAL_SERVER_ERROR => [
                'message' => 'Server error',
                'description' => 'Server error',
            ],
        ];
    }

    public function getError(int $code): string
    {
        $responseTypes = $this->getTypes();

        return \array_key_exists($code, $responseTypes)
            ? $responseTypes[$code]['message']
            : 'Oops, error occurred';
    }

    public function getDescription(int $code): string
    {
        $responseTypes = $this->getTypes();

        return \array_key_exists($code, $responseTypes)
            ? $responseTypes[$code]['description']
            : 'Unknown error';
    }
}
