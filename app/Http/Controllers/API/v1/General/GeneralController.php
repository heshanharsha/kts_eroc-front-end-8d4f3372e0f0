<?php

namespace App\Http\Controllers\API\v1\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helper\_helper;
use DB;
use App\Http\Resources\DocCollection;
use App\Documents;
use App\Company;
use App\CompanyPostfix;
use App\DocumentsGroup;
use App\TempFile;
use App\CompanyDocuments;
use Storage;

class GeneralController extends Controller
{

    use _helper;

    public function setCompany()
    {
        ini_set('MAX_EXECUTION_TIME', -1);

        try {

            $get = DB::select(DB::raw("select registration_no,REPLACE(company_name,'(PRIVATE) LIMITED', '') as name, '(PRIVATE) LIMITED' as postfix from eroc_mycompany where pvnumber = 'PV' group by registration_no,name"));

            foreach ($get as $key => $value) {
                $company = Company::Create([
                    'id' => (int)$this->genarateCompanyId(),
                    'type_id' => 19,
                    'name' => strtoupper($value->name),
                    'name_si' => '',
                    'name_ta' => '',
                    'postfix' => $value->postfix,
                    'abbreviation_desc' => 'Test Abbreviation',
                    'address_id' => 1,
                    'status' => $this->settings('COMPANY_NAME_EXPIRED', 'key')->id,
                    'created_by' => 1
                ]);

                DB::table('company_certificate')->insert([
                    'company_id' => $company->id,
                    'registration_no' => $value->registration_no,
                    'path' => '/'
                ]);
            }
            echo "Success";

        } catch (\Exception $e) {
        }

    }

    public function getCompanyType()
    {
        return response()->json($this->settings('COMPANY_TYPES'), 200);
    }

    public function getSubCompanyType(Request $request)
    {
        return CompanyPostfix::where('company_type_id', $request->id)->get();
    }

    public function getdocDynamic(Request $request)
    {

        $docs = DocumentsGroup::where('company_type', $request->type)
            ->where('request_type', $request->req)
            ->where('status', 1)
            ->get();

        if (count($docs) > 0) {
            foreach ($docs as $key => $value) {

                $group = Documents::where('document_group_id', '=', $value->id)->where('status', 1)->get();

                foreach ($group as $ky => $val) {
                    $fields[$ky] = [
                        'id' => $val->id,
                        'name' => $val->description,
                        'is_required' => $val->is_required
                    ];
                }

                $collection[$key] = [
                    'id' => $value->id,
                    'description' => $value->description,
                    'fields' => $fields
                ];
            }

            return response()->json(array_values($collection), 200);

        } else {
            return response()->json(['error' => 'Error decoding authentication request.'], 401);
        }
    }

    public function getMemberTitle()
    {
        return $this->settings('NAME_TITLE');
    }

    public function getDocument(Request $request)
    {
        $document = CompanyDocuments::where('file_token', $request->token)->first();
        
        if (!empty($document)) {
            // return response()->download(Storage::disk('sftp')->get($document->path));
            return Storage::disk('sftp')->get($document->path);
        } else {
            return response()->json(['error' => 'Error decoding authentication request.'], 401);
        }
    }

   

    public function isFileDestroy(Request $request) {
      
        $document = CompanyDocuments::where('file_token', $request->token)->first();
        
        if (!empty($document)) {
            $document->delete();
            $delete = Storage::disk('sftp')->delete($document->path);
            return response()->json($delete, 200);
        } else {
            return response()->json(false, 401);
        }
    }


    public function getStatusCount(Request $request)
    {
        
        $data = [
            'all' => $this->status($request->email, 'all'),
            'pending' => $this->status($request->email, 'COMPANY_NAME_PENDING'),
            'approval' => $this->status($request->email, 'COMPANY_NAME_APPROVED'),
            'submited' => $this->status($request->email, 'COMPANY_STATUS_PENDING'),
            'rejected' => $this->status($request->email, 'COMPANY_NAME_REJECTED'),
            'canceled' => $this->status($request->email, 'COMPANY_NAME_CANCELED'),
        ];
    
        return $data;
    }

    public function status($email, $status)
    {
        $count = Company::Join('people', 'companies.created_by', '=', 'people.id')
            ->where('people.email', '=', $this->clearEmail($email));
        if ($status != 'all') {
            $count = $count->where('companies.status', $this->settings($status, 'key')->id);
        }
        return $count->count();
    }

}
