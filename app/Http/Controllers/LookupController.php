<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gateways\LookupGateway;
use App\Http\Requests\LookupRequest;
use App\Http\Resources\UserProfileResource;

/**
 * Class LookupController
 *
 * @package App\Http\Controllers
 */
class LookupController extends Controller
{
    public function lookup(LookupRequest $request, LookupGateway $gateway): UserProfileResource
    {
        $profile = $gateway->resolve($request->get('type'))
            ->lookup($request->get('username'), $request->get('id'));

        return new UserProfileResource($profile);
    }
}
