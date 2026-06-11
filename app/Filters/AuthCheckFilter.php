<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthCheckFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // User role to redirect path mapping
        $userRoles = [
            'admin_session_uid'      => '/Admin',
            'doctor_session_uid'     => '/Doctor',
            'frontdesk_session_uid'  => '/Frontdesk',
            'bloodbnk_session_uid'   => '/Blood_bank/index',
            'accountant_session_uid' => '/Medical_Accountant'
        ];

        // Check if any role session exists
        foreach ($userRoles as $sessionKey => $redirectPath) {
            if ($session->has($sessionKey)) {
                return redirect()->to(base_url($redirectPath));
            }
        }
        // Allow access if no session is set
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Leave empty
    }
}
