<?php

namespace Api\Infra;

use Api\Helper\CheckTokenAuth;
use Exception;

class Router
{
    private string $path;
    private array $controllers;

    public function __construct()
    {
    }

    public static function normalizeUrl(): array
    {
        $path = $_SERVER["PATH_INFO"] ?? '/';
        $urlArr = array_filter(array_values(explode('/', $path)));
        $url = '/' . implode('/', $urlArr);
        $offSetId = array_search("id", $urlArr);
        $id = implode(array_slice($urlArr, $offSetId, 1));

        return [$id, $url];
    }

    public function addRoute(string $path, array $controllers, string $nameRouteProtected)
    {
        try {
            $this->path = $path;
            $this->controllers = $controllers;
            $controllerOut = [];
            foreach ($controllers as $key => $controller) {
                $controllerOut = $controller;
                if (array_key_exists($path, $controller)) {
                    if ($key !== $nameRouteProtected) {
                        return $controller[$path];
                    }
                    (new CheckTokenAuth())->validToken() ?? '';
                    return $controller[$path];
                }
            }

            if (!array_key_exists($path, $controllerOut)) {
                throw new Exception();
            }
        } catch (Exception) {
            http_response_code(404);
            echo json_encode(
                [
                    'data' => false,
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Rota não encontrada'
                ],
                JSON_UNESCAPED_UNICODE
            );
            exit();
        }
    }
}
