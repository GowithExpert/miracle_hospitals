<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginRequiredFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if any user is logged in
        if (
            !$session->has('admin_session_uid') &&
            !$session->has('doctor_session_uid') &&
            !$session->has('frontdesk_session_uid') &&
            !$session->has('bloodbnk_session_uid') &&
            !$session->has('accountant_session_uid')
        ) {
            // Not logged in: redirect to the master login page or specific login page
            return redirect()->to(base_url('/mgt'))->with('error', 'Please login first.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Leave empty
    }
}
