<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zimbra\Admin\AdminApi;
use Zimbra\Common\Enum\AccountBy;
use Zimbra\Common\Struct\AccountSelector;

class ZimbraController extends Controller
{
    protected $api;

    public function __construct(AdminApi $api)
    {
        $this->api = $api;
    }

    public function getAccountInfo($accountName)
    {
        $account = $this->api->getAccountInfo(new AccountSelector(AccountBy::NAME, $accountName));
        return response()->json($account);
    }
}
