<?php

namespace App\Http\Controllers;

use App\Http\ApiResponse;
use Illuminate\Http\Request;
use Microsoft\Kiota\Abstractions\ApiException;
use Zimbra\Account\AccountApi;
use Zimbra\Admin\AdminApi;
use Zimbra\Admin\Struct\AccountInfo;
use Zimbra\Admin\Struct\Attr;
use Zimbra\Admin\Struct\DistributionListSelector;
use Zimbra\Admin\Struct\MailboxByAccountIdSelector;
use Zimbra\Admin\Struct\ServerSelector;
use Zimbra\Common\Enum\AccountBy;
use Zimbra\Common\Enum\DistributionListBy;
use Zimbra\Common\Enum\SessionType;
use Zimbra\Common\Struct\AccountSelector;
use Zimbra\Mail\MailApi;

class ZimbraController extends Controller
{
    protected $api;

    public function __construct(AdminApi $api)
    {
        $this->api = $api;
    }

    public function setZimbraMailStatus(Request $request, string $status)
    {
        // status kilitli => locked , kapalı => closed , actif => active
        $accountName = $request->get("accountName");
        $updatedAttrs = [
            new Attr('zimbraAccountStatus', $status),
        ];

        $id = $this->getZimbraID($accountName);

        $res = $this->api->modifyAccount($id, $updatedAttrs);

        return response()->json($res, 200);
    }

    public function getZimbraStatus(Request $request)
    {
        $response = $this->api->getAllCos();

        $attrs = [];
        $attrList = $response->getCosList();
        print_r($attrList);
    }

    public function getZimbra()
    {

    }

    public function getDistributionList(Request $request, string $functionName)
    {
        $dl = $request->get("dl");
        $attrs = [];
        $allowedFunctions = ['getAttrList', 'getMembers'];
        try {
            if (in_array($functionName, $allowedFunctions)) {

                $list = $this->api->getDistributionList(new DistributionListSelector(DistributionListBy::NAME, $dl));

                if (method_exists($list->getDl(), $functionName)) {
                    $resultList = call_user_func([$list->getDl(), $functionName]);

                    foreach ($resultList as $attr) {
                        // key - value ise
                        if (is_object($attr) && method_exists($attr, 'getKey') && method_exists($attr, 'getValue')) {
                            $attrs[$attr->getKey()] = $attr->getValue();
                        } else { //  array ise
                            $attrs[] = $attr;
                        }

                    }
                    $response = new ApiResponse(200, "success", $attrs);
                    return response()->json($response->toArray());
                } else {
                    $response = new ApiResponse(400, "Function does not exist", null);
                    return response()->json($response->toArray());
                }


            } else {
                $response = new ApiResponse(400, "Invalid function name", null); // Fonksiyon beyaz listede değilse
                return response()->json($response->toArray());
            }
        } catch (\Exception $e) {
            $response = new ApiResponse(500, $e->getMessage(), null);
            return response()->json($response->toArray());
        }


    }

    public function getAccountAttributes(Request $request)
    {
        #$accountName = $request->get("accountName");
        $accountName = "suat.canbay@gazi.edu.tr";

        $data = $this->api->getAccount(new AccountSelector(AccountBy::NAME, $accountName));
        $attrs = [];
        $attrList = $data->getAccount()->getAttrList();
        foreach ($attrList as $attr) {
            $attrs[$attr->getKey()] = $attr->getValue();
        }
        return response()->json($attrs);
    }

    public function getZimbraID($accountName)
    {
        $data = $this->api->getAccountInfo(new AccountSelector(AccountBy::NAME, $accountName));
        $attrList = $data->getAttrList();
        $attrs = [];
        foreach ($attrList as $attr) {
            $attrs[$attr->getKey()] = $attr->getValue();
        }
        return $attrs['zimbraId'];
    }

    public function setPassword(Request $request)
    {
        $accountName = $request->get("accountName");
        $password = $request->get("password");

        $id = $this->getZimbraID($accountName);
        $this->api->setPassword($id, $password);

        return response()->json("success", 200);
    }

}
