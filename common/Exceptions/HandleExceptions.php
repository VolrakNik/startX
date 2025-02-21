<?php

namespace Common\Exceptions;

use Common\MonologFormatter\GrafanaFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Throwable;

class HandleExceptions
{
    private Logger $logger;

    public function bootstrap(string $logPath): void
    {
        //Set logger
        $this->logger = new Logger('logger');
        $streamHandler = new StreamHandler($logPath);
        $streamHandlerCli = new StreamHandler('php://stdout');
        $streamHandler->setFormatter(new GrafanaFormatter());
        $streamHandlerCli->setFormatter(new GrafanaFormatter());
        $this->logger
            ->pushHandler($streamHandler)
            ->pushHandler($streamHandlerCli);

        error_reporting(-1);
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
        register_shutdown_function([$this, 'shutdownHandler']);
    }

    /**
     * @param Throwable $e
     * @return void
     */
    public function exceptionHandler(Throwable $e): void
    {
        header('Content-Type: application/json');

        if ($e instanceof HttpException) {
            http_response_code($e->getStatusCode());
            $response = ['message' => $e->getMessage(), 'code' => $e->getCode()];
        } elseif ($e instanceof ValidationException || $e instanceof CSRFException) {
            http_response_code(422);
            $response = ['message' => $e->getMessage(), 'code' => $e->getCode()];
        } else {
            http_response_code(500);
            if (method_exists($e, 'report')) {
                $e->report();
            } else {
                $this->logger->error(
                    $e->getMessage(),
                    ['type' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine(), 'trace' => $e->getTrace()]
                );
            }

            if (method_exists($e, 'response')) {
                $response = $e->response();
            } else {
                $response = ['message' => 'Something went wrong', 'code' => $e->getCode()];
            }
        }

        echo json_encode(['error' => $response]);
        exit();
    }

    /**
     * @param int $errno
     * @param string $errStr
     * @param string $errFile
     * @param int $errLine
     * @return bool
     */
    public function errorHandler(int $errno, string $errStr, string $errFile, int $errLine): bool
    {
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $this->logger->error(
            $errStr,
            ['type' => 0, 'message' => $errStr, 'file' => $errFile, 'line' => $errLine]
        );
        return true;
    }

    /**
     * @return void
     */
    public function shutdownHandler(): void
    {
        $error = error_get_last();
        if ($error === null) {
            return;
        }

        $this->logger->critical($error['message'], $error);
    }
}