<?php

namespace AppBundle\EventListener;

use AppBundle\Api\ApiProblem;
use AppBundle\Api\ApiProblemException;
use AppBundle\Api\ResponseFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private $debug;
    private $responseFactory;
    private $logger;

    public function __construct($debug, ResponseFactory $responseFactory, LoggerInterface $logger)
    {
        $this->debug = $debug;
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();

        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

        // allow 500 errors to be thrown
        if ($this->debug && $statusCode >= 500) {
            return;
        }

        $this->logException($event);

        if ($e instanceof ApiProblemException) {
            $apiProblem = $e->getApiProblem();
        } else {


            $apiProblem = new ApiProblem(
                $statusCode
            );

            /*
             * If it's an HttpException message (e.g. for 404, 403),
             * we'll say as a rule that the exception message is safe
             * for the client. Otherwise, it could be some sensitive
             * low-level exception, which should *not* be exposed
             */
            if ($e instanceof HttpExceptionInterface) {
                $message = $e->getMessage();

                if (strpos($message, 'not found') !== false) {
                    $message = 'Not Found';
                }

                $apiProblem->set('detail', $message);
            }
        }

        $response = $this->responseFactory->createResponse($apiProblem);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    /**
     * Adapted from the core Symfony exception handling in ExceptionListener
     *
     * @param GetResponseForExceptionEvent $event
     */
    private function logException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request = $event->getRequest();

        $isCritical = !$exception instanceof HttpExceptionInterface || $exception->getStatusCode() >= 500;

        if ($exception instanceof ConflictHttpException) {
            $isCritical = true;

            $previousException = $exception->getPrevious();

            if (!null === $previousException) {
                $exception = $previousException;
            }
        }

        $message = sprintf('Uncaught PHP Exception %s: "%s" at %s line %s, Data: %s',
            get_class($exception), $exception->getMessage(), $exception->getFile(),
            $exception->getLine(), $request->getRequestUri());

        $context = array('exception' => $exception);
        if ($isCritical) {
            $this->logger->critical($message, $context);
        } else {
            $this->logger->error($message, $context);
        }
    }
}
