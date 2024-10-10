<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionEventListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        // Если это исключение 404, то возвращаем пустой ответ с кодом 404
        if ($exception instanceof NotFoundHttpException) {
            $response = new JsonResponse(null, 404);
            $event->setResponse($response);
        }
    }
}
