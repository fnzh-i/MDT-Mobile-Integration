<?php
namespace App\Controllers;

abstract class BaseController {
    protected function getJsonInput(): array {
        $json = file_get_contents('php://input'); // or pwedeng other ways idk, sa Laravel mas madali ata to
        return json_decode($json, true) ?? [];
    }

    protected function sendResponse(mixed $data, int $code = 200): void {
        header('Content-Type: application/json');
        http_response_code($code);
        
        $response = [
            "status" => ($code >= 200 && $code < 300) ? "success" : "error",
            "data" => ($code >= 200 && $code < 300) ? $data : null,
            "message" => ($code >= 400) ? $data : null
        ];

        echo json_encode($response);
        exit;
    }
}
?>