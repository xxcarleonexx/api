<?php


namespace base;

use Exception;
use RuntimeException;

abstract class AbstractApi
{

    public const POST = 'POST';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';
    public const GET = 'GET';

    /** @var string */
    public $apiName = ''; //users

    /** @var string */
    protected $method = ''; //GET|POST|PUT|DELETE

    /** @var array */
    public $requestUri = [];
    /** @var array */
    public $requestParams = [];

    /** @var string */
    protected $action = '';

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Content-Type: application/json');

        //Массив GET параметров разделенных слешем
        $this->requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->requestParams = $_REQUEST;

        //Определение метода запроса
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method === self::POST && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] === self::DELETE) {
                $this->method = self::DELETE;
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] === self::PUT) {
                $this->method = self::PUT;
            }
            throw new RuntimeException('Unexpected Header');
        }
    }

    public function run()
    {
        //Первые 2 элемента массива URI должны быть "api" и название таблицы
        if (array_shift($this->requestUri) !== 'api' || array_shift($this->requestUri) !== $this->apiName) {
            throw new RuntimeException('API Not Found', 404);
        }
        //Определение действия для обработки
        $this->action = $this->getAction();

        //Если метод(действие) определен в дочернем классе API
        if (method_exists($this, $this->action)) {
            return $this->{$this->action}();
        }
        throw new RuntimeException('Invalid Method', 405);
    }

    protected function response($data, $status = 500)
    {
        header('HTTP/1.1 ' . $status . " " . $this->requestStatus($status));
        return json_encode($data);
    }

    /**
     * @param $code
     * @return string
     */
    private function requestStatus($code)
    {
        $status = [
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ];
        return $status[$code] ?? $status[500];
    }

    protected function getAction()
    {
        $method = $this->method;
        switch ($method) {
            case self::GET:
                if ($this->requestUri) {
                    return 'viewAction';
                } else {
                    return 'indexAction';
                }
            case self::POST:
                return 'createAction';
            case self::PUT:
                return 'updateAction';
            case self::DELETE:
                return 'deleteAction';
            default:
                return null;
        }
    }

    abstract protected function indexAction();

    abstract protected function viewAction();

    abstract protected function createAction();

    abstract protected function updateAction();

    abstract protected function deleteAction();
}
