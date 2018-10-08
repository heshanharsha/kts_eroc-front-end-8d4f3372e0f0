<?php

namespace App\Http\Controllers\API\v1\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helper\_helper;
use App\People;
use App\Company;
use Illuminate\Foundation\Auth\User;
use Mockery\CountValidator\Exception;
use App\CompanyDocuments;
use App\Http\Resources\RecervationCollection;
use Illuminate\Support\Facades\Auth;
use DB;
use App\CompanyPostfix;

class ReservationController extends Controller
{
    use _helper;

    public function setName(Request $request)
    {
        $addressId = $this->getAddress($request['email'])->address_id;

        if ($addressId) {
            $company = Company::updateOrCreate(
                [
                    'name' => strtoupper($request['englishName']),
                    'status' => [$this->settings('COMPANY_NAME_EXPIRED', 'key')->id]
                ],
                [
                    'id' => (int)$this->genarateCompanyId(),
                    'type_id' => $request['typeId'],
                    'name' => strtoupper($request['englishName']),
                    'name_si' => $request['sinhalaName'],
                    'name_ta' => $request['tamilname'],
                    'postfix' => is_null($request['postfix']) ? '' : $request['postfix'],
                    'abbreviation_desc' => $request['abreviations'],
                    'address_id' => $addressId,
                    'status' => $this->settings('COMPANY_NAME_EXPIRED', 'key')->id,
                    'created_by' => $this->getUserId($this->clearEmail($request['email']))->id
                ]
            );

            return response()->json(['company' => $company->id], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function getAddress($email)
    {
        return People::where('email', $this->clearEmail($email))->first();
    }

    public function getUserId($email)
    {
        return User::where('email', $this->clearEmail($email))->first();
    }

    public function uploadFile(Request $request)
    {   
        try {
            $storagePath = 'company/' . substr($request->id, 0, 2) . '/' . substr($request->id, 2, 2) . '/' . $request->id . '/NR';
            $path = $request->file('file')->storeAs($storagePath, uniqid() . '.pdf', 'sftp');
            if ($path) {
                $comDoc = new CompanyDocuments();
                $comDoc->company_id = $request->id;
                $comDoc->document_id = $request->docId;
                $comDoc->file_token = md5(uniqid());
                $comDoc->path = $path;
                $comDoc->status = $this->settings('DOCUMENT_PENDING', 'key')->id;
                $comDoc->save();
            }
          
            return response()->json(['key' => $comDoc->file_token], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function getUserData(Request $request)
    {
        $companies = Company::leftJoin('people', 'companies.created_by', '=', 'people.id')
            ->leftJoin('addresses', 'companies.address_id', '=', 'addresses.id')
            ->leftJoin('settings', 'settings.id', '=', 'companies.status')
            ->where('people.email', '=', $this->clearEmail($request->email))->orderBy('companies.updated_at', 'desc')
            ->whereNotIn('companies.status', [$this->settings('COMPANY_NAME_EXPIRED', 'key')->id])
            ->select(
                'companies.id',
                'companies.name',
                'companies.name_si',
                'companies.name_ta',
                'companies.postfix',
                'companies.abbreviation_desc',
                'companies.email',
                'companies.created_at',
                'companies.updated_at',
                'addresses.address1',
                'addresses.address2',
                'addresses.city',
                'addresses.district',
                'addresses.province',
                'addresses.country',
                'addresses.postcode',
                'settings.value as status',
                'settings.key'
            );
            
        if (!is_null($request->key)) {
            $companies = $companies->where(\DB::raw("concat(eroc_companies.name,' ',eroc_companies.postfix)"), 'ilike', '%' . $request->key . '%');
        }

        return new RecervationCollection(
            $companies->paginate(5)
        );
    }

    public function getNameReservationData(Request $request)
    {
        
        $company = Company::leftJoin('people', 'companies.created_by', '=', 'people.id')
                    ->leftJoin('addresses', 'companies.address_id', '=', 'addresses.id')
                    ->leftJoin('settings', 'settings.id', '=', 'companies.status')
                    ->where('companies.id', '=', $request->id)
                    ->select(
                        'companies.id',
                        'companies.name',
                        'companies.name_si',
                        'companies.name_ta',
                        'companies.postfix',
                        'companies.abbreviation_desc',
                        'companies.email',
                        'companies.created_at',
                        'companies.updated_at',
                        'addresses.address1',
                        'addresses.address2',
                        'addresses.city',
                        'addresses.district',
                        'addresses.province',
                        'addresses.country',
                        'addresses.postcode',
                        'settings.value as status',
                        'settings.key'
                    )->firstOrFail();

        $notIn = array(
            $this->settings('DOCUMENT_REQUEST_TO_RESUBMIT', 'key')->id, 
            $this->settings('DOCUMENT_REQUESTED', 'key')->id,
            $this->settings('DOCUMENT_DELETED', 'key')->id
        );

        $companyDocument = CompanyDocuments::leftJoin('documents','documents.id', '=', 'company_documents.document_id')
                        ->where('company_id', $request->id)
                        ->where('documents.status', 1)
                        ->whereNotIn('company_documents.status', $notIn)
                        ->get();
                  
        $companyResubmitedDoc = CompanyDocuments::leftJoin('company_document_status','company_documents.id', '=', 'company_document_status.company_document_id')
                    ->leftjoin('documents','documents.id', '=', 'company_documents.document_id')
                    ->where('company_id', $request->id )
                    ->where('company_documents.status', $this->settings('DOCUMENT_REQUESTED', 'key')->id )
                    ->get();
                  
        $response = [
            'companyInfor' => $company,
            'companyResubmitedDoc' =>  $companyResubmitedDoc,
            'companyDocument' =>  $companyDocument
        ];
          
        return response()->json($response, 200);
    }

    public function setNameReSubmit(Request $request)
    {
        try {
            $company = Company::updateOrCreate(
                [
                    'id' => $request->data['refId']
                ],
                [
                    'name' => $request->data['companyName'],
                    'name_si' => $request->data['sinhalaName'],
                    'name_ta' => $request->data['tamileName'],
                    'abbreviation_desc' => $request->data['abbreviation_desc'],
                    'status' => $this->settings('COMPANY_NAME_RESUBMITTED', 'key')->id
                ]
            );
           
            return response()->json(['success' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function getSearchResult(Request $request)
    {
        return new RecervationCollection(
            Company::where(\DB::raw("concat(eroc_companies.name,' ',eroc_companies.postfix)"), 'ilike', '%' . $request->key . '%')
                ->where('email', $this->clearEmail($request->email))
                ->paginate(10)
        );
    }
   
    public function setPayment(Request $request)
    {
        try {
            $company = Company::find($request->id);
            if ($company) {
                $company->status = $this->settings('COMPANY_NAME_PENDING', 'key')->id;
                $company->update();
                return response()->json(['success' => 'success'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function isGetfix(Request $request){
        try {
            $fix = CompanyPostfix::where('company_type_id', $request->hasfix)->get();
            return response()->json($fix->isEmpty(), 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
