<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class FrontdeskAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->has('frontdesk_session_uid')) {
            return redirect()
                ->to(site_url('Frontdesk_login/login_account'))
                ->with('error', 'Please login to continue.');
        }

        // Optional: Extra safety checks
        if (
            !$session->get('frontdesk_session_uid') ||
            !$session->get('frontdesk_session_id')
        ) {
            $session->setTempdata('error', 'Frontdesk ID is missing!', 3);
            return redirect()->to(site_url('Frontdesk_login/login_account'));
        }

        // Otherwise allow request to proceed
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing needed here
    }
}
