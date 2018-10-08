<?php

namespace App\Http\Controllers\API\v1\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\Http\Resources\SearchCollection as SearchResources;
use App\Http\Controllers\API\v1\Search\Validation\ValidationRules;
use App\Http\Helper\_helper;

class SEOController extends Controller
{

    use ValidationRules, _helper;

    public function showName(Request $request)
    {
        if (env('PUB_URL_TOKEN') == $request->token) {
            $req = [
                'available' => false,
                'data' => array()
            ];

            $searchWord = trim(preg_replace('/\s+/', ' ', strtoupper($request->searchtext)));

            $data = Company::leftJoin('company_certificate', 'companies.id', '=', 'company_certificate.company_id')
                ->select('companies.id', 'companies.name', 'companies.postfix', 'company_certificate.registration_no')
                ->whereNotIn('companies.status', [$this->settings('COMPANY_NAME_EXPIRED', 'key')->id])
                ->when($request, function ($query) use ($request, $searchWord) {
                    $query->where(\DB::raw("concat(eroc_companies.name,' ',eroc_companies.postfix)"), 'ilike', $request->criteria == 1 ? '%' . $searchWord . '%' : $searchWord . '%')
                        ->orWhere('company_certificate.registration_no', '=', $searchWord);
                })
                ->paginate(10); // get Company data

            $hasData = Company::leftJoin('company_certificate', 'companies.id', '=', 'company_certificate.company_id')
                ->select('companies.id', 'companies.name', 'companies.postfix', 'company_certificate.registration_no')
                ->whereNotIn('companies.status', [$this->settings('COMPANY_NAME_EXPIRED', 'key')->id])
                ->when($request, function ($query) use ($request, $searchWord) {
                    $query->where(\DB::raw("eroc_companies.name"), '=', $searchWord)
                        ->orWhere('company_certificate.registration_no', '=', $searchWord);
                })
                ->first();

            if (is_null($hasData)) {
                $req = [
                    'available' => true,
                    'data' => $this->availableName($request)
                ];
            }

            $merge = [
                'availableData' => new SearchResources($data),
                'notHasData' => $req
            ];

            return $merge;

        } else {
            return response()->json(['error' => 'Error decoding authentication request.']);
        }
    }

    public function availableName(Request $request)
    {
        $result = $this->checkRules($request->searchtext, $request->companyType);

        return $this->getfailsValidation($result);
    }

    private function getfailsValidation($result)
    {
        if (is_array($result)) {
            foreach ($result as $key => &$arrayElement) {
                if ($arrayElement['status'] != 'fail') {
                    unset($result[$key]);
                }
            }
        }

        return array_values($result);
    }
}
