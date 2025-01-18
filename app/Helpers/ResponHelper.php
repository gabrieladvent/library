<?php

namespace App\Helpers;

use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ResponHelper
{
    /**
     * Handle error response in JSON format.
     *
     * @param string $message
     * @param int $statusCode
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public static function handlerErrorResponJson($message, $statusCode = ResponseInterface::HTTP_BAD_REQUEST)
    {
        log_message('error', 'Error Response: ' . (is_array($message) ? json_encode($message) : $message));

        $response = Services::response();
        return $response->setStatusCode($statusCode)
            ->setJSON([
                'status' => 'error',
                'message' => $message,
            ]);
    }

    /**
     * Handle success response in JSON format.
     *
     * @param string $message
     * @param array $data
     * @param int $statusCode
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public static function handlerSuccessResponJson($message, $statusCode = ResponseInterface::HTTP_OK, $data = [])
    {
        log_message('info', 'Success Response: ' . (is_array($message) ? json_encode($message) : $message));

        $response = Services::response();
        return $response->setStatusCode($statusCode)
            ->setJSON([
                'status' => 'success',
                'message' => $message,
                'data' => $data
            ]);
    }

    /**
     * Handle error response with redirect.
     *
     * @param string|null $redirectUrl
     * @param string $message
     * @return \CodeIgniter\HTTP\RedirectResponse|false
     */
    public static function handlerErrorResponRedirect($redirectUrl, $message = 'error')
    {
        log_message('error', is_array($message) ? json_encode($message) : $message);
        session()->setFlashdata('error', $message);
        return redirect()->to(base_url($redirectUrl))->withInput();
    }

    /**
     * Handle success response with redirect.
     *
     * @param string|null $redirectUrl
     * @param string $message
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public static function handlerSuccessResponRedirect($redirectUrl, $message = 'success')
    {
        log_message('info', is_array($message) ? json_encode($message) : $message);
        session()->setFlashdata('success', $message);
        return redirect()->to(base_url($redirectUrl))->withInput();
    }
}
