<?php

namespace App\Http\Controllers\API\v1\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\Society;
use App\Http\Resources\SearchCollection as SearchResources;
use App\Http\Controllers\API\v1\Search\Validation\ValidationRules;
use App\Http\Helper\_helper;

class SEOController extends Controller
{

    use ValidationRules, _helper;

    public function showName(Request $request)
    {
        if (env('PUB_URL_TOKEN') == $request->token) {
            
			$originalWord = strtoupper($request->searchtext);
			
            $searchWord = $this->seo_friendly_url($originalWord);

            $separetWord = preg_replace('/\s+/', ' & ',str_replace(array('.', ''),',',strtoupper($searchWord)));
         
            $iLikeSearch =  trim(preg_replace('/\s+/', '', strtoupper($searchWord)));
            
            $pvdata = Company::leftJoin('company_certificate', 'companies.id', '=', 'company_certificate.company_id')
                    ->select('companies.id','companies.name','companies.postfix','company_certificate.registration_no')
                    ->whereNotIn('companies.status', [$this->settings('COMPANY_NAME_EXPIRED', 'key')->id])
                    ->Where('company_certificate.registration_no', 'ilike', '%'. $iLikeSearch  .'%')
                    ->first();
           
            $data = Company::leftJoin('company_certificate', 'companies.id', '=', 'company_certificate.company_id')
                    ->select('companies.id','companies.name','companies.postfix','company_certificate.registration_no')
                    ->whereNotIn('companies.status', [$this->settings('COMPANY_NAME_EXPIRED', 'key')->id])
                    ->when($request, function ($query) use ($request, $iLikeSearch ,$separetWord) {
                        if($request->criteria == 1 ){
                            $query->whereRaw('to_tsvector(eroc_companies.name ||\'  \'|| eroc_companies.postfix) @@ to_tsquery(?)', [$separetWord])
                            				->orWhere('company_certificate.registration_no', 'ilike', '%'. $iLikeSearch .'%');
                        }else{
                            $query->whereRaw('concat(eroc_companies.name,\'  \',eroc_companies.postfix) ilike (?)', [$iLikeSearch. '%']);
                        }
                    })
                    ->when($request, function ($query) use ($request, $separetWord, $pvdata, $iLikeSearch) {
                        if($request->criteria == 1 ){
							    if(is_null($pvdata)){
	                                $query->orderByRaw('ts_rank(to_tsvector(eroc_companies.name||\' \'||eroc_companies.postfix), to_tsquery(?)) DESC', [$separetWord]);
	                            }else {
									$query->orderByRaw('ts_rank(to_tsvector(eroc_company_certificate.registration_no), to_tsquery(?)) DESC', [$iLikeSearch]);
	                            }
                        }
                    })
                    ->paginate(10);
					 
            $hasData = Company::leftJoin('company_certificate', 'companies.id', '=', 'company_certificate.company_id')
                ->select('companies.id', 'companies.name', 'companies.postfix', 'company_certificate.registration_no')
                ->whereNotIn('companies.status', [$this->settings('COMPANY_NAME_EXPIRED', 'key')->id])
                ->when($request, function ($query) use ($request, $originalWord) {
                    $query->where(\DB::raw("TRIM(eroc_companies.name)"), '=', $originalWord)
                        ->orWhere('company_certificate.registration_no', '=', $originalWord);
                })
                ->first();
           
            $req = [
                'available' => is_null($hasData) ? true : false,
                'data' => is_null($hasData) ? $this->availableName($request) : array()
            ];

            $merge = [
                'availableData' => new SearchResources($data),
                'notHasData' => $req
            ];

            return $merge;

        } else {
            return response()->json(['error' => 'Error decoding authentication request.']);
        }
    }
//----------------sahani--------------
    public function showNamesociety(Request $request)
    {
        if (env('PUB_URL_TOKEN') == $request->token) {
            
			$originalWord = strtoupper($request->searchtext);
			
            $searchWord = $this->seo_friendly_url($originalWord);

            $separetWord = preg_replace('/\s+/', ' & ',str_replace(array('.', ''),',',strtoupper($searchWord)));
         
            $iLikeSearch =  trim(preg_replace('/\s+/', '', strtoupper($searchWord)));
            
            // $pvdata = Company::select('name')
            //         ->first();
           
            $data = Society::select('name','status')
            ->Where('name', 'ilike', '%'. $iLikeSearch  .'%')->orWhere('name', '=', $originalWord)->paginate(10);
					 
            $hasData = Society::select('name')
                ->when($request, function ($query) use ($request, $originalWord ) {
                    $query->where(\DB::raw("TRIM(eroc_societies.name)"), '=', $originalWord);
                })
                ->first();
           
            $req = [
                'available' => is_null($hasData) ? true : false,
                'data' => is_null($hasData) ? $this->availableNameSociety($request) : array(),
                'data1' => $originalWord
            ];

            $merge = [
                'availableData' => new SearchResources($data),
                'notHasData' => $req
            ];

            return $merge;

        } else {
            return response()->json(['error' => 'Error decoding authentication request.']);
        }
    }

    public function availableNameSociety(Request $request)
    {
        $result = $this->checkRulesSociety($request->searchtext, $request->companyType);

        return $this->getfailsValidation($result);
    }
    
 // --------end ----sahani--------------------
    function seo_friendly_url($string){
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '', $string);
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , ' ', $string);
        return trim($string);
    }

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        return $query->whereRaw('searchtext @@ to_tsquery(\'english\', ?)', [$search])
            ->orderByRaw('ts_rank(searchtext, to_tsquery(\'english\', ?)) DESC', [$search]);
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

    private function getClearCombination($result)
    {
        return trim(str_replace($this->getRestrictedWord(),'',$result));
    }
}
