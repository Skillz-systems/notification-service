<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;

class TaskUrlGenerator
{
    private const BASE_URL = 'https://example.com/tasks';

    /**
     * Generate a URL for the tasks endpoint based on the owner type and ID.
     *
     * @param string $ownerType The owner type (user, customer, or supplier)
     * @param int $ownerId The ID of the owner
     * @return string The generated URL
     */
    public function generateUrl(string $ownerType, int $ownerId): string
    {
        $baseUrl = self::BASE_URL;

        switch ($ownerType) {
            case 'staff':
                $baseUrl .= "/users/{$ownerId}";
                break;
            case 'customer':
                $baseUrl .= "/customers/{$ownerId}";
                break;
            case 'supplier':
                $baseUrl .= "/suppliers/{$ownerId}";
                break;
            case 'other':
                $baseUrl .= "/other/{$ownerId}";
                break;
            default:
                throw new \InvalidArgumentException("Invalid owner type: $ownerType");
        }

        return $baseUrl;
    }
}