<?php

namespace App\Http\Controllers;

use App\Http\ApiResponse;
use Illuminate\Http\Request;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Abstractions\ApiException;

enum StatusEnum: string
{
    case TRUE = 'True';
    case FALSE = 'False';

    public function toBoolean(): bool
    {
        return match($this) {
            self::TRUE => true,
            self::FALSE => false,
        };
    }
}

class GraphController extends Controller
{
    protected GraphServiceClient $graphClient;

    public function __construct(GraphServiceClient $graphClient)
    {
        $this->graphClient = $graphClient;
    }


    public function getAuditLogs(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->graphClient->users()->byUserId("")->get()->wait();

        $userPrincipalName = $request->get("userPrincipalName");
        try {
            $user = $this->graphClient->auditLogs()->signIns()->count();


            $response = new ApiResponse(200, $user, null);
            return response()->json($response->toArray());
        } catch (ApiException $ex) {
            $response = new ApiResponse(500, $ex->getError()->getMessage(), null);
            return response()->json($response->toArray());
        } catch (\Exception $e) {
            $response = new ApiResponse(500, $e->getMessage(), null);
        }
        return response()->json($response->toArray());
    }
    public function updatePhoneNumber(Request $request)
    {
        $userPrincipalName = $request->get("userPrincipalName");
        $phoneNumber = $request->get("phoneNumber");

        try {
            $user = $this->graphClient->users()->byUserId($userPrincipalName)->get()->wait();
            $user->setMobilePhone("+90 5349512434");
            $this->graphClient->users()->byUserId($userPrincipalName)->patch($user)->wait();


            $response = new ApiResponse(200, "Success", null);
            return response()->json($response->toArray());
        } catch (ApiException $ex) {
            $response = new ApiResponse(500, $ex->getError()->getMessage(), null);
            return response()->json($response->toArray());
        }


    }


    public function setUserStatus(Request $request)
    {

        $account = $request->get("userPrincipalName");
        $enabled = StatusEnum::from($request->get('enabled'));;
        $isEnabled = $enabled->toBoolean();

        try {
            $user = $this->graphClient->users()->byUserId($account)->get()->wait();
            $user->setAccountEnabled($isEnabled);
            $this->graphClient->users()->byUserId($account)->patch($user)->wait();

            $response = new ApiResponse(200, "Success", null);
            return response()->json($response->toArray());

        } catch (ApiException $ex) {
            $response = new ApiResponse(500, $ex->getError()->getMessage(), null);
            return response()->json($response->toArray());
        }
    }

}
