<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if admin is logged in
        if (!$session->has('admin_session_uid')) {
            return redirect()->to(site_url('Login'))->with('error', 'Please login to continue.');
        }

        // Also check if admin ID exists (extra layer like your current code)
        if (
            !$session->get('admin_session_uid') ||
            !$session->get('admin_session_id')
        ) {
            $session->setTempdata('error', 'Admin ID is missing!', 3);
            return redirect()->to(site_url('Login'));
        }

        // Otherwise allow request to proceed
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing needed here
    }
} #Class - Closed
