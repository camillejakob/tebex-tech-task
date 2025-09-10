<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gateways\LookupGateway;
use App\Http\Resources\UserProfileResource;

/**
 * Class LookupController
 *
 * @package App\Http\Controllers
 */
class LookupController extends Controller
{
    public function lookup(Request $request, LookupGateway $gateway): UserProfileResource
    {

        // validating in the controller as it is short
        // todo: move to a custom validation class if payload increases
        // or if we want to handle edge cases from within the validation
        $data = $request->validate([
            'username' => 'nullable|string',
            'id' => 'nullable|string',
            'type' => 'required|string|in:xbl,steam,minecraft'
        ]);

        $profile = $gateway->resolve($data['type'])
            ->lookup($data['username'] ?? null, $data['id'] ?? null);

        return new UserProfileResource($profile);
    }
}
