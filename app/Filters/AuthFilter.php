<?php

namespace App\Filters;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $key = getenv('JWT_SECRET');
        if (!$key) {
            return service('response')->setJSON([
                'status' => false,
                'message' => 'Server error: JWT secret not configured.'
            ])->setStatusCode(500);
        }

        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return service('response')->setJSON([
                'status' => false,
                'message' => 'Authorization token required. Please login.'
            ])->setStatusCode(401);
        }
        
        $token = $matches[1];

        if (cache("blacklist_$token")) {
            return service('response')->setJSON([
                'status' => false,
                'message' => 'Token is blacklisted. Please login again.'
            ])->setStatusCode(401);
        }
    


        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            // Simpan informasi user di request (opsional)
            $request->user = $decoded;
        } catch (\Firebase\JWT\ExpiredException $e) {
            // Token expired
            return service('response')->setJSON([
                'status' => false,
                'message' => 'Token expired. Please login again.'
            ])->setStatusCode(401);
        } catch (\Exception $e) {
            // Token tidak valid
            return service('response')->setJSON([
                'status' => false,
                'message' => 'Invalid token. Please login again.'
            ])->setStatusCode(401);
        }

        return;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}