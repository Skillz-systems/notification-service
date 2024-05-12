<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;

class CustomerOnboardingUrlGenerator
{
    private const BASE_URL = 'https://ngml.skillzserver.com/';

    // https://ngml.skillzserver.com/admin/records/customer/id
    private const ADMIN_BASE_PATH = '/admin/records/customer';
    // private const FRONTEND_BASE_PATH = '/onboard';

    /**
     * Generate a URL for the customer onboarding task with various parameters.
     *
     * @param int $customerId
     * @param int|null $staffId
     * @param bool $openModal
     * @param string $frontendRoute
     * @param array $extraParams
     * @return string
     */
    public function generateUrl(
        int $customerId,
        ?int $staffId = null,
        bool $openModal = false,
        bool $openTab = false,
        string $frontendRoute = 'customer-onboarding',
        array $extraParams = []
    ): string {
        $customer = Customer::findOrFail($customerId);
        $queryParams = [
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'frontend_route' => $frontendRoute,
        ];

        if ($staffId !== null) {
            $staff = User::findOrFail($staffId);
            $queryParams['staff_id'] = $staff->id;
            $queryParams['staff_name'] = $staff->name;
        }

        if ($openModal) {
            $queryParams['open_modal'] = 'true';
        }

        if ($openTab) {
            $queryParams['open_tab'] = 'true';
        }

        $queryParams = array_merge($queryParams, $extraParams);

        $url = self::BASE_URL . self::ADMIN_BASE_PATH . '?' . http_build_query($queryParams);

        return $url;
    }
}

// FIXME: USAGE
// use App\Services\CustomerOnboardingUrlGenerator;

// class OnboardingController extends Controller
// {
//     private $urlGenerator;

//     public function __construct(CustomerOnboardingUrlGenerator $urlGenerator)
//     {
//         $this->urlGenerator = $urlGenerator;
//     }

//     public function showOnboardingTask(int $customerId, ?int $staffId = null)
//     {
//         $url = $this->urlGenerator->generateUrl(
//             $customerId,
//             $staffId,
//             true,
//             false,
//             'customer-onboarding-form',
//             [
//                 'utm_source' => 'email',
//                 'utm_campaign' => 'onboarding',
//             ]
//         );

//         // Redirect or render the view with the generated URL
//         return redirect($url);
//     }
// }