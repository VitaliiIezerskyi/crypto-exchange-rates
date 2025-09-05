<?php

declare(strict_types = 1);

namespace App\Enum;

use Symfony\Component\HttpFoundation\Response;

enum ApiErrorMessageEnum: string
{
    case UndefinedErrorMessage = 'api.errors.undefinedError';
    case InternalServerErrorMessage = 'api.errors.internalServerError';
    case NotFoundErrorMessage = 'api.errors.notFound';
    case BadRequestErrorMessage = 'api.errors.badRequest';
    case MethodNotAllowedErrorMessage = 'api.errors.methodNotAllowed';
    case AccessDeniedErrorMessage = 'api.errors.accessDenied';
    case UnauthorizedErrorMessage = 'api.errors.unauthorized';
    case ConflictErrorMessage = 'api.errors.conflict';
    case GoneErrorMessage = 'api.errors.gone';
    case LengthRequiredErrorMessage = 'api.errors.lengthRequired';
    case LockedErrorMessage = 'api.errors.locked';
    case NotAcceptableErrorMessage = 'api.errors.notAcceptable';
    case PreconditionFailedErrorMessage = 'api.errors.preconditionFailed';
    case PreconditionRequiredErrorMessage = 'api.errors.preconditionRequired';
    case UnsupportedMediaTypeErrorMessage = 'api.errors.unsupportedMediaType';
    case UnprocessableEntityErrorMessage = 'api.errors.unprocessableEntity';
    case TooManyRequestsErrorMessage = 'api.errors.tooManyRequests';
    case ServiceUnavailableErrorMessage = 'api.errors.serviceUnavailable';

    public static function getMessage(int $statusCode): self
    {
        return match ($statusCode) {
            Response::HTTP_INTERNAL_SERVER_ERROR => self::InternalServerErrorMessage,
            Response::HTTP_NOT_FOUND => self::NotFoundErrorMessage,
            Response::HTTP_BAD_REQUEST => self::BadRequestErrorMessage,
            Response::HTTP_METHOD_NOT_ALLOWED => self::MethodNotAllowedErrorMessage,
            Response::HTTP_FORBIDDEN => self::AccessDeniedErrorMessage,
            Response::HTTP_UNAUTHORIZED => self::UnauthorizedErrorMessage,
            Response::HTTP_CONFLICT => self::ConflictErrorMessage,
            Response::HTTP_GONE => self::GoneErrorMessage,
            Response::HTTP_LENGTH_REQUIRED => self::LengthRequiredErrorMessage,
            Response::HTTP_LOCKED => self::LockedErrorMessage,
            Response::HTTP_NOT_ACCEPTABLE => self::NotAcceptableErrorMessage,
            Response::HTTP_PRECONDITION_FAILED => self::PreconditionFailedErrorMessage,
            Response::HTTP_PRECONDITION_REQUIRED => self::PreconditionRequiredErrorMessage,
            Response::HTTP_UNSUPPORTED_MEDIA_TYPE => self::UnsupportedMediaTypeErrorMessage,
            Response::HTTP_UNPROCESSABLE_ENTITY => self::UnprocessableEntityErrorMessage,
            Response::HTTP_TOO_MANY_REQUESTS => self::TooManyRequestsErrorMessage,
            Response::HTTP_SERVICE_UNAVAILABLE => self::ServiceUnavailableErrorMessage,
            default => self::UndefinedErrorMessage,
        };
    }
}
