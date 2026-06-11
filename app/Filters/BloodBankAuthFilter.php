<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class BloodBankAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->has('bldbnk_session_uid')) {
            return redirect()
                ->to(site_url('Blood_bank/blood_bank_login'))
                ->with('error', 'Please login to continue.');
        }

        // Optional: Extra safety checks
        if (
            !$session->get('bldbnk_session_uid') ||
            !$session->get('bldbnk_session_id')
        ) {
            $session->setTempdata('error', 'Blood Bank user ID is missing!', 3);
            return redirect()->to(site_url('Blood_bank/blood_bank_login'));
        }

        // Otherwise allow request to proceed
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing needed here
    }
}