<?php

namespace App\Http\Controllers\API\v1\incorporation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CompanyPostfix;
use App\Company;
use App\Address;
use App\ForeignAddress;
use App\Setting;
use App\CompanyMember;
use App\DocumentsGroup;
use App\Documents;
use App\Country;
use App\Share;
use App\ShareGroup;
use App\CompanyFirms;
use App\CompanyDocuments;
use App\User;
use App\People;

use Storage;
use App;
use URL;
use App\Http\Helper\_helper;

use PDF;

class IncorporationController extends Controller
{
    use _helper;

    public function downloadForm(){

        $data = array();

        $pdf = PDF::loadView('forms.form1', $data);
        return $pdf->download('invoice.pdf');
    }


    function removeSecForDirector(Request $request){
        
        $company_id = $request->companyId;
        $user_id = $request->userId;

        $shaareUser = CompanyMember::where('id', $user_id)->first();
        $sec_nic_or_pass = ($shaareUser->is_srilankan  =='yes') ? $shaareUser->nic : $shaareUser->passport_no;
        $sec_nic_or_pass_field = ($shaareUser->is_srilankan  =='yes') ? 'nic' : 'passport_no';

        $delete = CompanyMember::where('company_id',$company_id)
                                ->where('designation_type',$this->settings('SECRETARY','key')->id)
                                ->where($sec_nic_or_pass_field,$sec_nic_or_pass )
                                ->delete();
        if($delete){
            return response()->json([
                'message' => 'Successfully remove seretary position',
                'status' =>true
            ], 200);
        }else{
            return response()->json([
                'message' => 'Failed removing seretary position',
                'status' =>false
            ], 200);
        }
        
    }

    function removeShForDirector(Request $request){

        $company_id = $request->companyId;
        $user_id = $request->userId;

        $shaareUser = CompanyMember::where('id', $user_id)->first();
        $sh_nic_or_pass = ($shaareUser->is_srilankan  =='yes') ? $shaareUser->nic : $shaareUser->passport_no;
        $sh_nic_or_pass_field = ($shaareUser->is_srilankan  =='yes') ? 'nic' : 'passport_no';

    
        $delete = CompanyMember::where('company_id',$company_id)
                                ->where('designation_type',$this->settings('SHAREHOLDER','key')->id)
                                ->where($sh_nic_or_pass_field,$sh_nic_or_pass )
                                ->delete();
        if($delete){
            return response()->json([
                'message' => 'Successfully remove shareholder position',
                'status' =>true
            ], 200);
        }else{
            return response()->json([
                'message' => 'Failed removing shareholder position',
                'status' =>false
            ], 200);
        }
        
    }

    function removeShForSec(Request $request){

        $company_id = $request->companyId;
        $user_id = $request->userId;

        $shaareUser = CompanyMember::where('id', $user_id)->first();
        $sh_nic_or_pass = ($shaareUser->is_srilankan  =='yes') ? $shaareUser->nic : $shaareUser->passport_no;
        $sh_nic_or_pass_field = ($shaareUser->is_srilankan  =='yes') ? 'nic' : 'passport_no';

    
        $delete = CompanyMember::where('company_id',$company_id)
                                ->where('designation_type',$this->settings('SHAREHOLDER','key')->id)
                                ->where($sh_nic_or_pass_field,$sh_nic_or_pass )
                                ->delete();
        if($delete){
            return response()->json([
                'message' => 'Successfully remove shareholder position',
                'status' =>true
            ], 200);
        }else{
            return response()->json([
                'message' => 'Failed removing shareholder position',
                'status' =>false
            ], 200);
        }
        
    }

   

    function uploadedDocs($companyId){
          //uploaded docs array
      $uploaded_docs = array();
    
      $company_info = Company::where('id',$companyId)->first();
      $companyTypeKey = $this->settings($company_info->type_id,'id')->key;

      $docs = $this->documents();
      $docs_type_ids=array();
      if( isset($docs[$companyTypeKey]['upload'])){
          foreach($docs[$companyTypeKey]['upload'] as $doc){
            $docs_type_ids[] = $doc['dbid'];
          }
      }
    
     // $docs_type_ids = array(16,17,18,22);
      foreach($docs_type_ids as $id ){

        $doc =CompanyDocuments::where('document_id', $id)
                        ->where('company_id', $companyId)
                        ->get();

        if(count($doc)){
            
         foreach($doc as $d ){
            if($d->company_member_id){

                $uploaded_docs[$id][ $d->company_member_id ] = (isset($d->path)) ? basename($d->path) : '';
            }else{
                $uploaded_docs[$id]  = (isset($d->path)) ? basename($d->path) : '';
            }  
               
         }
        }
   
      }

      return $uploaded_docs;
    }

    public function loadData(Request $request){

        
        if(!$request->companyId){

            return response()->json([
                'message' => 'We can \'t find a company.',
                'status' =>false
            ], 200);
        }

        $loginUserEmail = $this->clearEmail($request->loginUser);

        $loginUserInfo = User::where('email', $loginUserEmail)->first();
        $loginUserId = $loginUserInfo->people_id;

        $userPeople = People::where('id',$loginUserId)->first();
        $userAddressId = $userPeople->address_id;
        $userAddress = Address::where('id', $userAddressId)->first();

        $company_types = CompanyPostfix::all();
        $company_types = $company_types->toArray();
        $company_info = Company::where('id',$request->companyId)->first();

       // print_r($company_info);
       
        /*return response()->json([
            'message' => 'We can \'t find a company.',
            'status' =>false,
            'data' => $company_info
        ], 200);*/
    
        $company_address = Address::where('id',$company_info->address_id)->first();
        
        $company_objectives  = $this->settings('COMPANY_OBJECTIVE','type');
       // print_r($company_type);
       // $company_objectives = Setting::where('setting_type_id',4)->get();

        /******director list *****/
      
        $director_list = CompanyMember::where('company_id',$request->companyId)
                                       ->where('designation_type',$this->settings('DERECTOR','key')->id)
                                       ->where('status',1)
                                       ->get();
        $directors = array();
        foreach($director_list as $director){

             
             $director_nic_or_pass = ($director->is_srilankan  =='yes') ? $director->nic : $director->passport_no;
             $director_nic_or_pass_field = ($director->is_srilankan  =='yes') ? 'nic' : 'passport_no';

             //director as a secrotory list
             $directors_as_sec = CompanyMember::select('id','first_name','last_name','title','nic','passport_no')
                                        ->where('company_id', $request->companyId)
                                        ->where('designation_type',$this->settings('SECRETARY','key')->id)
                                        ->where($director_nic_or_pass_field,$director_nic_or_pass)
                                        ->get()
                                        ->count();

            //director as a shareholder list
            $directors_as_sh = CompanyMember::select('id','first_name','last_name','title','nic','passport_no')
                                       ->where('company_id', $request->companyId)
                                       ->where('designation_type',$this->settings('SHAREHOLDER','key')->id)
                                       ->where($director_nic_or_pass_field,$director_nic_or_pass)
                                       ->get()
                                       ->count();
                                        


             $address_id =  $director->foreign_address_id ? $director->foreign_address_id : $director->address_id;
            // $address = Address::where('id',$address_id)->first();

             if(!$director->foreign_address_id){
                $address = Address::where('id',$address_id)->first();
             }else{
                $address = ForeignAddress::where('id',$address_id)->first();
             }

             $rec = array(
                'id' => $director['id'],
                'type' => ($director->is_srilankan  =='yes' ) ? 'local' : 'foreign',

                'firstname' => $director->first_name,
                'lastname' => $director->last_name,
                'title' => $director->title,

                'province' =>  ( $address->province) ? $address->province : '',
                'district' =>  ($address->district) ? $address->district : '',
                'city' =>  ( $address->city) ? $address->city : '',
                'localAddress1' => ($address->address1) ? $address->address1 : '',
                'localAddress2' => ($address->address2) ? $address->address2 : '',
                'postcode' => ($address->postcode) ? $address->postcode : '',

                'nic'       => $director->nic,
                'passport'  => $director->passport_no,
                'country'   => $director->passport_issued_country,
              //  'share'     => $director->no_of_shares,
                'date'      => '1970-01-01' == $director->date_of_appointment ? null : $director->date_of_appointment,
                'phone' => $director->telephone,
                'mobile' => $director->mobile,
                'email' => $director->email,
                'occupation' => $director->occupation,
                'directors_as_sec' =>$directors_as_sec,
                'directors_as_sh' => $directors_as_sh

             );
             $directors[] = $rec;
        }

        /******secretory list *****/
        $sec_list = CompanyMember::where('company_id',$request->companyId)
        ->where('designation_type',$this->settings('SECRETARY','key')->id)
        ->where('status',1)
        ->get();
        $secs = array();
        foreach($sec_list as $sec){

        $sec_nic_or_pass = ($sec->is_srilankan  =='yes') ? $sec->nic : $sec->passport_no;
        $sec_nic_or_pass_field = ($sec->is_srilankan  =='yes') ? 'nic' : 'passport_no';

        //sec as a shareholder list
        $sec_as_sh = CompanyMember::select('id','first_name','last_name','title','nic','passport_no')
            ->where('company_id', $request->companyId)
             ->where('designation_type',$this->settings('SHAREHOLDER','key')->id)
             ->where($sec_nic_or_pass_field,$sec_nic_or_pass)
             ->get();
        $sec_as_sh_count = $sec_as_sh->count();
        $sec_sh_comes_from_director = false;
        if($sec_as_sh->count() == 1){
            $is_sec_from_director = CompanyMember::select('id','first_name','last_name','title','nic','passport_no')
            ->where('company_id', $request->companyId)
             ->where('designation_type',$this->settings('DERECTOR','key')->id)
             ->where($sec_nic_or_pass_field,$sec_nic_or_pass)
             ->get()->count();
            
            if($is_sec_from_director == 1){
                $sec_as_sh_count =0;
                $sec_sh_comes_from_director = true;
            }
        }   
        

        $address_id =  $sec->foreign_address_id ? $sec->foreign_address_id : $sec->address_id;

         if(!$sec->foreign_address_id){
            $address = Address::where('id',$address_id)->first();
         }else{
            $address = ForeignAddress::where('id',$address_id)->first();
         }

         $firm_info = array();
         if($sec->company_member_firm_id){
             $firm_info = CompanyFirms::where('id',$sec->company_member_firm_id)->first();

             $firm_address = Address::where('id', $firm_info->address_id)->first();

             $firm_info['address']=$firm_address;

         }

        $rec = array(
        'id' => $sec['id'],
        'type' => ($sec->is_srilankan =='yes' ) ? 'local' : 'foreign',
        'firstname' => $sec->first_name,
        'lastname' => $sec->last_name,
        'province' =>  ( $address->province) ? $address->province : '',
        'district' =>  ($address->district) ? $address->district : '',
        'city' =>  ( $address->city) ? $address->city : '',
        'localAddress1' => ($address->address1) ? $address->address1 : '',
        'localAddress2' => ($address->address2) ? $address->address2 : '',
        'postcode' => ($address->postcode) ? $address->postcode : '',

        'nic'       => $sec->nic,
        'passport'  => $sec->passport_no,
        'country'   => $sec->passport_issued_country,
        //'share'     =>0,
        'date'      => '1970-01-01' == $sec->date_of_appointment ? null : $sec->date_of_appointment,
        'isReg'        => ($sec->is_registered_secretary =='yes') ? true :false,
        'regDate'      => ($sec->is_registered_secretary =='yes') ? $sec->secretary_registration_no :'',
        'phone' => $sec->telephone,
        'mobile' => $sec->mobile,
        'email' => $sec->email,
        'occupation' => $sec->occupation,
        'secType' => ( $sec->is_natural_person == 'yes') ? 'natural' : 'firm',
        'secCompanyFirmId' => $sec->company_member_firm_id,
        'sec_as_sh' => $sec_as_sh_count,
        'sec_sh_comes_from_director' => $sec_sh_comes_from_director,
        'firm_info' =>$firm_info,
        'pvNumber' => ($sec->company_member_firm_id) ? $firm_info['registration_no'] : '',
        'firm_name' => ($sec->company_member_firm_id) ? $firm_info['name'] : '',
        'firm_province' => ($sec->company_member_firm_id) ? $firm_address['province'] : '',
        'firm_district' => ($sec->company_member_firm_id) ? $firm_address['district'] : '',
        'firm_city' => ($sec->company_member_firm_id) ? $firm_address['city'] : '',
        'firm_localAddress1' => ($sec->company_member_firm_id) ? $firm_address['address1'] : '',
        'firm_localAddress2' => ($sec->company_member_firm_id) ? $firm_address['address2'] : '',
        'firm_postcode' => ($sec->company_member_firm_id) ? $firm_address['postcode'] : ''







        );
        $secs[] = $rec;
        }


        /******share holder list *****/
        $shareholder_list = CompanyMember::where('company_id',$request->companyId)
        ->where('designation_type',$this->settings('SHAREHOLDER','key')->id)
        ->where('status',1)
        ->get();
        $shareholders = array();
        foreach($shareholder_list as $shareholder){


        $address_id =  $shareholder->foreign_address_id ? $shareholder->foreign_address_id : $shareholder->address_id;

        if(!$shareholder->foreign_address_id){
           $address = Address::where('id',$address_id)->first();
        }else{
           $address = ForeignAddress::where('id',$address_id)->first();
        }

        //check share row
        $shareRecord = array(
            'type' => '', 'name' => '' , 'no_of_shares' =>0
        );
        $shareRow = Share::where('company_member_id', $shareholder->id)->first();

       // print_R($shareRow->company_member_id);
         $shareType ='';
         $noOfShares ='';
         $groupName= '';
         $sharegroupId='';


        if(isset($shareRow->company_member_id ) && $shareRow->company_member_id ){

            $shareGroupInfo = ShareGroup::where('id', $shareRow->group_id)->first();

            $shareRecord['type'] = $shareGroupInfo['type'];
            $shareRecord['name'] = $shareGroupInfo['name'];
            $shareRecord['sharegroupId'] = $shareGroupInfo['id'];
            $shareRecord['no_of_shares'] = $shareGroupInfo['no_of_shares'];

            $shareType = $shareGroupInfo['type'] == 'core_share' ? 'core' :'single';
            $noOfShares = $shareGroupInfo['no_of_shares'];

            if($shareType == 'core'){
                $groupName= $shareGroupInfo['name'];
                $sharegroupId = $shareGroupInfo['id'];
            }


        

        }

        $rec = array(
        'id' => $shareholder['id'],
        'type' => ($shareholder->is_srilankan =='yes' ) ? 'local' : 'foreign',
        'firstname' => $shareholder->first_name,
        'lastname' => $shareholder->last_name,
       
        'province' =>  ( $address->province) ? $address->province : '',
        'district' =>  ($address->district) ? $address->district : '',
        'city' =>  ( $address->city) ? $address->city : '',
        'localAddress1' => ($address->address1) ? $address->address1 : '',
        'localAddress2' => ($address->address2) ? $address->address2 : '',
        'postcode' => ($address->postcode) ? $address->postcode : '',

        'nic'       => $shareholder->nic,
        'passport'  => $shareholder->passport_no,
        'country'   => $shareholder->passport_issued_country,
       // 'share'     => $shareholder->no_of_shares,
        'date'      => '1970-01-01' == $shareholder->date_of_appointment ? null : $shareholder->date_of_appointment,
        'phone' => $shareholder->telephone,
        'mobile' => $shareholder->mobile,
        'email' => $shareholder->email,
        'occupation' => $shareholder->occupation,
        'shareRow' => $shareRecord,
        'shList'  =>$shareholder,
        'shareType' => $shareType,
        'noOfShares' => $noOfShares,
        'coreGroupSelected' => $sharegroupId



        );
        $shareholders[] = $rec;
        }


          /******company documents *****/
        $documentsGroups = DocumentsGroup::where('company_type',$company_info->type_id )
                                                ->where('request_type','COM_REG')
                                                ->get();

        $documentList = array();

        foreach($documentsGroups as $group ){

            $group_id = $group->id;

            $docs =  \DB::table('documents')->where('document_group_id', $group_id )->get();

            if(count($docs)){

                $data = array(

                    'group_name' => $group->description,
                    'documents'  =>  $docs,
                    'docs_count' => count($docs)
    
                );
                $documentList[] = $data;

            }


        }

        $companyType = $this->settings($company_info->type_id,'id');

      //  $this->generate_files($companyType->key,$request->companyId);

      ////////share groups////////
      $core_groups_list = array();
      $core_groups = ShareGroup::where('type','core_share')->get();
      if(count($core_groups)){
          foreach($core_groups as $g ){
        
          $grec = array(
              'group_id' => $g->id,
              'group_name' => $g->name
          );
          $core_groups_list[] = $grec;
        }
      }

    return response()->json([
            'message' => 'Incorpartiaon Data is successfully loaded.',
            'status' =>true,
            'data'   => array(

                            'compnayTypes' => $company_types,
                            'companyInfo'  => $company_info,
                            'processStatus' => $this->settings($company_info->status,'id')->key,
                            'companyAddress' => $company_address,
                            'companyObjectives' => $company_objectives,
                            'companyType'    =>$companyType,
                            'countries'     => Country::all(),
                            'loginUser'     => $userPeople,
                            'loginUserAddress'=> $userAddress,
                            
                            'directors' => $directors,
                            'secs' => $secs,
                            'shareholders' => $shareholders,
                            'documents' =>$documentList,
                            'public_path' =>  storage_path(),
                            'companyTypes' => $this->settings('COMPANY_TYPES','key'),
                            'docList' => $this->getDocs($companyType->key),
                            'coreShareGroups' => $core_groups_list,
                            'uploadedDocs' => $this->uploadedDocs($request->companyId),
                            'enableStep2Next' => count($directors) && count($secs) && count($shareholders)
                           
                         
                            

                        )
        ], 200);
          
    }

    public function resubmit(Request $request ){

        $company_update =  array(

            'status'    => $this->settings('COMPANY_STATUS_RESUBMITTED','key')->id 
        );
        Company::where('id', $request->company_id)->update($company_update);

        return response()->json([
            'message' => ' Successfully Resubmitted',
            'status' =>true,
           
        ], 200);
    }


    public function submitPay(Request $request ){

        $company_update =  array(

            'status'    => $this->settings('COMPANY_STATUS_PENDING','key')->id 

        );
        Company::where('id', $request->company_id)->update($company_update);

        return response()->json([
            'message' => 'Payment Successful.',
            'status' =>true,
           
        ], 200);
    }


    public function submitStep1(Request $request){

        $company_id = $request->companyId;
        $company_info = Company::where('id',$company_id)->first();

        $addressId = $company_info->address_id;


        $company_update =  array(

            'objective' => $request->objective,
            'email'     => $request->email,
            'type_id'   => $request->companyType,


        );
        Company::where('id', $company_id)->update($company_update);

        $address_update = array(

            'address1' => $request->address1,
            'address2' => $request->address2,
            'city' => $request->city,
            'district' => $request->district,
            'province'  => $request->province

        );
        Address::where('id', $addressId)->update($address_update);



        return response()->json([
            'message' => 'data.',
            'status' =>true,
            'data'   => Company::where('id',$request->companyId)->first()
        ], 200);


          
    }

    function removeStakeHolder(Request  $request ){

          $stakeholder_id = $request->userId;

          $delete = CompanyMember::where('id', $stakeholder_id)->delete();

          if($delete){

            return response()->json([
                'message' => 'Successfully deleted the stakeholder',
                'status' =>true
               
            ], 200);

          }else{
            return response()->json([
                'message' => 'Failed deleting the stakeholder. Please try again',
                'status' =>false
              
            ], 200);
          }

         
    }

    function submitStep2(Request $request){

        $company_id = $request->companyId;

         $loginUserEmail = $this->clearEmail($request->loginUser);

       


        $direcotrList = array();
        $secList = array();
        $shareHolderList = array();

        if( 
            $request->action != 'remove' && 
           !( is_array($request->directors['directors']) && count($request->directors['directors']) &&
            is_array($request->secretories['secs']) && count($request->secretories['secs']) &&
            is_array($request->shareholders['shs']) && count($request->shareholders['shs'])

           )
        ){

            return response()->json([
                'message' => 'Please add atleast one stakeholder from each group',
                'status' =>false,
                'data'   => array()
            ], 200);

        }


        


        $company_info = Company::where('id',$company_id)->first();
        $companyType = $this->settings($company_info->type_id,'id');


        //loop through add director list
        foreach($request->directors['directors'] as $director ){


                $address = new Address;
          
                $address->province = $director['province'];
                $address->district =  $director['district'];
                $address->city =  $director['city'];
                $address->address1 =  $director['localAddress1'];
                $address->address2 =  $director['localAddress2'];
                $address->postcode = $director['postcode'];
                $address->country = $director['country'];
              
                $address->save();
                $addressId = $address->id;

 
             //if director as a shareholder
             if( 
                  (isset($director['isShareholder']) &&  $director['isShareholder'] ) ||
                  ( isset($director['isShareholderEdit']) &&  $director['isShareholderEdit'] ) 
             ){



                if( 
                    ( isset($director['shareType']) && $director['shareType'] ) ||
                    ( isset($director['shareTypeEdit']) && $director['shareTypeEdit'] )
                 ){

 

                    $dir_to_share_address = new Address;
                    $dir_to_share_address->province = $director['province'];
                    $dir_to_share_address->district =  $director['district'];
                    $dir_to_share_address->city =  $director['city'];
                    $dir_to_share_address->address1 =  $director['localAddress1'];
                    $dir_to_share_address->address2 =  $director['localAddress2'];
                    $dir_to_share_address->postcode = $director['postcode'];
                    $dir_to_share_address->country = 'Sri Lanka';
                
                    $dir_to_share_address->save();
                    $shareHolderAddressId = $dir_to_share_address->id;
    
                    if( 
                        ( isset($director['shareType']) &&  $director['shareType'] == 'single' && isset($director['noOfSingleShares']) &&  intval($director['noOfSingleShares']) ) ||
                        ( isset($director['shareTypeEdit']) && $director['shareTypeEdit'] == 'single' && $director['noOfSingleSharesEdit'] &&  intval($director['noOfSingleSharesEdit']) )
                    ) {

              
                      
                        $dir_shareholder = new CompanyMember;
                        $dir_shareholder->company_id = $company_id;
                        $dir_shareholder->designation_type = $this->settings('SHAREHOLDER','key')->id;
                        $dir_shareholder->is_srilankan = 'yes';
                        $dir_shareholder->title = $director['title'];
                        $dir_shareholder->first_name = $director['firstname'];
                        $dir_shareholder->last_name =$director['lastname'];
                        $dir_shareholder->address_id = $shareHolderAddressId;
                        $dir_shareholder->nic = strtoupper($director['nic']);
    
                        $dir_shareholder->passport_issued_country ='Sri Lanka';
                        $dir_shareholder->telephone =$director['phone'];
                        $dir_shareholder->mobile =$director['mobile'];
                        $dir_shareholder->email =$director['email'];
                        $dir_shareholder->occupation =$director['occupation'];
                     //   $dir_shareholder->no_of_shares ='100';
                        $dir_shareholder->date_of_appointment = date('Y-m-d',strtotime($director['date']) );
                        $dir_shareholder->status =1;
    
                        $dir_shareholder->save();
                        $newDirShareHolderID = $dir_shareholder->id;

                        $singleShares=0;

                        if(isset($director['noOfSingleShares']) &&  intval($director['noOfSingleShares'])){
                            $singleShares = intval($director['noOfSingleShares']);
                        }
                        if( isset($director['noOfSingleSharesEdit']) &&  intval($director['noOfSingleSharesEdit']) ){
                            $singleShares = intval($director['noOfSingleSharesEdit']);
                        }
    
    
                        //add to single share group
                        $dir_shareholder_sharegroup = new ShareGroup;
                        $dir_shareholder_sharegroup->type ='single_share';
                        $dir_shareholder_sharegroup->name ='single_share_no_name';
                        $dir_shareholder_sharegroup->no_of_shares =$singleShares;
                        $dir_shareholder_sharegroup->status = 1;
    
                        $dir_shareholder_sharegroup->save();
                        $dir_shareholder_sharegroupID = $dir_shareholder_sharegroup->id;
    
                        //add to share table
    
                        $dir_shareholder_share = new Share;
                        $dir_shareholder_share->company_member_id = $newDirShareHolderID;
                        $dir_shareholder_share->group_id = $dir_shareholder_sharegroupID;
                        $dir_shareholder_share->save();
                    }
    
                    if(
                       ( isset($director['shareType']) &&  $director['shareType'] == 'core' &&  isset($director['coreGroupSelected']) && intval( $director['coreGroupSelected']) ) || 
                       ( isset($director['shareTypeEdit']) &&  $director['shareTypeEdit'] == 'core' &&  isset($director['coreGroupSelectedEdit']) && intval( $director['coreGroupSelectedEdit']) ) 
                        
                    ){

                       
    
                        $dir_shareholder = new CompanyMember;
                        $dir_shareholder->company_id = $company_id;
                        $dir_shareholder->designation_type = $this->settings('SHAREHOLDER','key')->id;
                        $dir_shareholder->is_srilankan = 'yes';
                        $dir_shareholder->title = $director['title'];
                        $dir_shareholder->first_name = $director['firstname'];
                        $dir_shareholder->last_name =$director['lastname'];
                        $dir_shareholder->address_id = $shareHolderAddressId;
                        $dir_shareholder->nic = strtoupper($director['nic']);
    
                        $dir_shareholder->passport_issued_country ='Sri Lanka';
                        $dir_shareholder->telephone =$director['phone'];
                        $dir_shareholder->mobile =$director['mobile'];
                        $dir_shareholder->email =$director['email'];
                        $dir_shareholder->occupation =$director['occupation'];
                      //  $dir_shareholder->no_of_shares ='100';
                        $dir_shareholder->date_of_appointment = date('Y-m-d',strtotime($director['date']) );
                        $dir_shareholder->status =1;
    
                        $dir_shareholder->save();
                        $newDirShareHolderID = $dir_shareholder->id;


                        $selectedShareGroup='';
                        if(isset($director['coreGroupSelected']) && intval( $director['coreGroupSelected'])){
                            $selectedShareGroup =  $director['coreGroupSelected'];
                        }
                        if(isset($director['coreGroupSelectedEdit']) && intval( $director['coreGroupSelectedEdit'])){
                            $selectedShareGroup =  $director['coreGroupSelectedEdit'];
                        }

                        //add to share table
                        $dir_shareholder_share = new Share;
                        $dir_shareholder_share->company_member_id = $newDirShareHolderID;
                        $dir_shareholder_share->group_id =intval( $selectedShareGroup);
                        $dir_shareholder_share->save();
                    }
    
                    if( 

                        ( isset($director['shareType']) && $director['shareType'] == 'core' && empty( $director['coreGroupSelected'])  && $director['coreShareGroupName'] && intval($director['coreShareValue']) ) ||
                        ( isset($director['shareTypeEdit']) && $director['shareTypeEdit'] == 'core' && empty( $director['coreGroupSelectedEdit'])  && $director['coreShareGroupNameEdit'] && intval($director['coreShareValueEdit']) )
                    ) {

                      
    
    
                        $dir_shareholder = new CompanyMember;
                        $dir_shareholder->company_id = $company_id;
                        $dir_shareholder->designation_type = $this->settings('SHAREHOLDER','key')->id;
                        $dir_shareholder->is_srilankan = 'yes';
                        $dir_shareholder->title = $director['title'];
                        $dir_shareholder->first_name = $director['firstname'];
                        $dir_shareholder->last_name =$director['lastname'];
                        $dir_shareholder->address_id = $shareHolderAddressId;
                        $dir_shareholder->nic = strtoupper($director['nic']);
    
                        $dir_shareholder->passport_issued_country ='Sri Lanka';
                        $dir_shareholder->telephone =$director['phone'];
                        $dir_shareholder->mobile =$director['mobile'];
                        $dir_shareholder->email =$director['email'];
                        $dir_shareholder->occupation =$director['occupation'];
                      //  $dir_shareholder->no_of_shares ='100';
                        $dir_shareholder->date_of_appointment = date('Y-m-d',strtotime($director['date']) );
                        $dir_shareholder->status =1;
    
                        $dir_shareholder->save();
                        $newDirShareHolderID = $dir_shareholder->id;

                        $coreShareGroupName='';
                        $coreShareValue = '';
                        if(isset($director['shareType']) && $director['shareType'] == 'core' && empty( $director['coreGroupSelected'])  && $director['coreShareGroupName'] && intval($director['coreShareValue'])){

                            $coreShareGroupName = $director['coreShareGroupName'];
                            $coreShareValue = $director['coreShareValue'];
                        }
                        if(isset($director['shareTypeEdit']) && $director['shareTypeEdit'] == 'core' && empty( $director['coreGroupSelectedEdit']) && $director['coreShareGroupNameEdit'] && intval($director['coreShareValueEdit'])){

                            $coreShareGroupName = $director['coreShareGroupNameEdit'];
                            $coreShareValue = $director['coreShareValueEdit'];
                        }
    
    
                        //add to single share group
                        $dir_shareholder_sharegroup = new ShareGroup;
                        $dir_shareholder_sharegroup->type ='core_share';
                        $dir_shareholder_sharegroup->name = $coreShareGroupName;
                        $dir_shareholder_sharegroup->no_of_shares =intval( $coreShareValue );
                        $dir_shareholder_sharegroup->status = 1;
    
                        $dir_shareholder_sharegroup->save();
                        $dir_shareholder_sharegroupID = $dir_shareholder_sharegroup->id;
    
                        //add to share table
                        $dir_shareholder_share = new Share;
                        $dir_shareholder_share->company_member_id = $newDirShareHolderID;
                        $dir_shareholder_share->group_id = $dir_shareholder_sharegroupID;
                        $dir_shareholder_share->save();
                    }

                 
    
               }
    

             } //end if director is a shareholder


             //if director is a secretory
             if( ( isset($director['isSec']) &&  $director['isSec'] ) || (isset($director['isSecEdit']) &&  $director['isSecEdit']) ){

                $dir_to_sec_address = new Address;
                $dir_to_sec_address->province = $director['province'];
                $dir_to_sec_address->district =  $director['district'];
                $dir_to_sec_address->city =  $director['city'];
                $dir_to_sec_address->address1 =  $director['localAddress1'];
                $dir_to_sec_address->address2 =  $director['localAddress2'];
                $dir_to_sec_address->postcode = $director['postcode'];
                $dir_to_sec_address->country = 'Sri Lanka';
                
                $dir_to_sec_address->save();
                $secAddressId = $dir_to_sec_address->id;

             

                $dir_sec = new CompanyMember;
                        $dir_sec->company_id = $company_id;
                        $dir_sec->designation_type = $this->settings('SECRETARY','key')->id;
                        $dir_sec->is_srilankan = 'yes';
                        $dir_sec->title = $director['title'];
                        $dir_sec->first_name = $director['firstname'];
                        $dir_sec->last_name =$director['lastname'];
                        $dir_sec->address_id = $secAddressId;
                        $dir_sec->nic = strtoupper($director['nic']);
    
                        $dir_sec->passport_issued_country ='Sri Lanka';
                        $dir_sec->telephone =$director['phone'];
                        $dir_sec->mobile =$director['mobile'];
                        $dir_sec->email =$director['email'];
                        $dir_sec->occupation =$director['occupation'];
                      //  $dir_sec->no_of_shares ='0';
                        $dir_sec->date_of_appointment = date('Y-m-d',strtotime($director['date']) );
                        $dir_sec->is_registered_secretary = 'no';
                        $dir_sec->secretary_registration_no = NULL;
                        $dir_sec->is_natural_person ="yes";
                        $dir_sec->status =1;
    
                        $dir_sec->save();
                        $newDirSecID = $dir_sec->id;



             }


             

            if(isset($director['id']) && $director['id'] ){
                $updateDirector = CompanyMember::find($director['id']);
            }else{
                $updateDirector = new CompanyMember;
            }

            $updateDirector->company_id = $company_id;
            $updateDirector->designation_type =  $this->settings('DERECTOR','key')->id;
            $updateDirector->is_srilankan =  $director['type'] != 'local' ?  'no' : 'yes';
            $updateDirector->title = $director['title'];
            $updateDirector->first_name = $director['firstname'];
            $updateDirector->last_name = $director['lastname'];
            $updateDirector->address_id = $addressId;
            $updateDirector->nic = strtoupper($director['nic']);
            $updateDirector->passport_no = $director['passport'];
            $updateDirector->passport_issued_country = $director['country'];
            $updateDirector->telephone = $director['phone'];
            $updateDirector->mobile =$director['mobile'];
            $updateDirector->email = $director['email'];
           // $updateDirector->foreign_address_id =($director['type'] !='local') ? $addressId: 0;
            $updateDirector->occupation = $director['occupation'];
           // $updateDirector->no_of_shares = $director['share'];
            $updateDirector->date_of_appointment = date('Y-m-d',strtotime($director['date']) );
            $updateDirector->status = 1;

            $updateDirector->save();
            
        }
        
      

        //loop through add secrotory list
        foreach($request->secretories['secs'] as $sec ){

        
                $address = new Address;

                $address->province = $sec['province'];
                $address->district =  $sec['district'];
                $address->city =  $sec['city'];
                $address->address1 =  $sec['localAddress1'];
                $address->address2 =  $sec['localAddress2'];
                $address->country = $sec['country'];
                $address->postcode = $sec['postcode'];
              
                $address->save();
                $addressId = $address->id;


            if(isset($sec['id']) && $sec['id'] ){
                $updateSec = CompanyMember::find($sec['id']);
            }else{
                $updateSec = new CompanyMember;
            }



            /*** */
            //if sec as a shareholder
            if(
                ( isset($sec['isShareholder']) &&  $sec['isShareholder'] ) || 
                ( isset($sec['isShareholderEdit']) &&  $sec['isShareholderEdit'] )
            ){

                if(
                     ( isset($sec['shareType']) && $sec['shareType'] ) || 
                     ( isset($sec['shareTypeEdit']) && $sec['shareTypeEdit'] )
                ){

                    $sec_to_share_address = new Address;
                    $sec_to_share_address->province = $sec['province'];
                    $sec_to_share_address->district =  $sec['district'];
                    $sec_to_share_address->city =  $sec['city'];
                    $sec_to_share_address->address1 =  $sec['localAddress1'];
                    $sec_to_share_address->address2 =  $sec['localAddress2'];
                    $sec_to_share_address->postcode = $sec['postcode'];
                    $sec_to_share_address->country = 'Sri Lanka';
                
                    $sec_to_share_address->save();
                    $sec_to_share_addressId = $sec_to_share_address->id;
    
                    if( 
                        ( isset($sec['shareType']) && $sec['shareType'] == 'single' && isset($sec['noOfSingleShares']) && intval($sec['noOfSingleShares']) ) || 
                        ( isset($sec['shareTypeEdit']) && $sec['shareTypeEdit'] == 'single' && isset($sec['noOfSingleSharesEdit']) && intval($sec['noOfSingleSharesEdit']) )
                        
                    ) {
    
                      
                        $sec_shareholder = new CompanyMember;
                        $sec_shareholder->company_id = $company_id;
                        $sec_shareholder->designation_type = $this->settings('SHAREHOLDER','key')->id;
                        $sec_shareholder->is_srilankan = 'yes';
                        $sec_shareholder->title = 'Mr.'; //$sec['title'];
                        $sec_shareholder->first_name = $sec['firstname'];
                        $sec_shareholder->last_name =$sec['lastname'];
                        $sec_shareholder->address_id = $sec_to_share_addressId;
                        $sec_shareholder->nic = strtoupper($sec['nic']);
    
                        $sec_shareholder->passport_issued_country ='Sri Lanka';
                        $sec_shareholder->telephone =$sec['phone'];
                        $sec_shareholder->mobile =$sec['mobile'];
                        $sec_shareholder->email =$sec['email'];
                        $sec_shareholder->occupation =$sec['occupation'];
                      //  $sec_shareholder->no_of_shares ='100';
                        $sec_shareholder->date_of_appointment = date('Y-m-d',strtotime($sec['date']) );
                        $sec_shareholder->status =1;
    
                        $sec_shareholder->save();
                        $newSecShareHolderID = $sec_shareholder->id;

                        $singleShares=0;

                        if(isset($sec['noOfSingleShares']) &&  intval($sec['noOfSingleShares'])){
                            $singleShares = intval($sec['noOfSingleShares']);
                        }
                        if( isset($sec['noOfSingleSharesEdit']) &&  intval($sec['noOfSingleSharesEdit']) ){
                            $singleShares = intval($sec['noOfSingleSharesEdit']);
                        }
    
    
                        //add to single share group
                        $sec_shareholder_sharegroup = new ShareGroup;
                        $sec_shareholder_sharegroup->type ='single_share';
                        $sec_shareholder_sharegroup->name ='single_share_no_name';
                        $sec_shareholder_sharegroup->no_of_shares = $singleShares;
                        $sec_shareholder_sharegroup->status = 1;
    
                        $sec_shareholder_sharegroup->save();
                        $sec_shareholder_sharegroupID = $sec_shareholder_sharegroup->id;
    
                        //add to share table
    
                        $sec_shareholder_share = new Share;
                        $sec_shareholder_share->company_member_id = $newSecShareHolderID;
                        $sec_shareholder_share->group_id = $sec_shareholder_sharegroupID;
                        $sec_shareholder_share->save();
                    }
    
                    if(
                       ( isset( $sec['shareType']) &&  $sec['shareType'] == 'core' &&  isset($sec['coreGroupSelected']) && intval( $sec['coreGroupSelected']) ) || 
                       ( isset( $sec['shareTypeEdit']) &&  $sec['shareTypeEdit'] == 'core' &&  isset($sec['coreGroupSelectedEdit']) && intval( $sec['coreGroupSelectedEdit']) )
                    
                    ){
    
                        $sec_shareholder = new CompanyMember;
                        $sec_shareholder->company_id = $company_id;
                        $sec_shareholder->designation_type = $this->settings('SHAREHOLDER','key')->id;
                        $sec_shareholder->is_srilankan = 'yes';
                        $sec_shareholder->title = 'Mr.'; //$sec['title'];
                        $sec_shareholder->first_name = $sec['firstname'];
                        $sec_shareholder->last_name =$sec['lastname'];
                        $sec_shareholder->address_id = $sec_to_share_addressId;
                        $sec_shareholder->nic = strtoupper($sec['nic']);
    
                        $sec_shareholder->passport_issued_country ='Sri Lanka';
                        $sec_shareholder->telephone =$sec['phone'];
                        $sec_shareholder->mobile =$sec['mobile'];
                        $sec_shareholder->email =$sec['email'];
                        $sec_shareholder->occupation =$sec['occupation'];
                      //  $sec_shareholder->no_of_shares ='100';
                        $sec_shareholder->date_of_appointment = date('Y-m-d',strtotime($sec['date']) );
                        $sec_shareholder->status =1;
    
                        $sec_shareholder->save();
                        $newSecShareHolderID = $sec_shareholder->id;

                        $selectedShareGroup='';
                        if(isset($sec['coreGroupSelected']) && intval( $sec['coreGroupSelected'])){
                            $selectedShareGroup =  $director['coreGroupSelected'];
                        }
                        if(isset($sec['coreGroupSelectedEdit']) && intval( $sec['coreGroupSelectedEdit'])){
                            $selectedShareGroup =  $sec['coreGroupSelectedEdit'];
                        }
    
                        //add to share table
                        $sec_shareholder_share = new Share;
                        $sec_shareholder_share->company_member_id = $newSecShareHolderID;
                        $sec_shareholder_share->group_id =intval($selectedShareGroup );
                        $sec_shareholder_share->save();
                    }
    
                    if( 
                        ( isset( $sec['shareType'] ) &&  $sec['shareType'] == 'core' && empty( $sec['coreGroupSelected'])  && $sec['coreShareGroupName'] && intval($sec['coreShareValue']) ) || 
                        ( isset( $sec['shareTypeEdit'] ) &&  $sec['shareTypeEdit'] == 'core' && empty( $sec['coreGroupSelectedEdit'])  && $sec['coreShareGroupNameEdit'] && intval($sec['coreShareValueEdit']) )
                        
                    ) {
    
    
                        $sec_shareholder = new CompanyMember;
                        $sec_shareholder->company_id = $company_id;
                        $sec_shareholder->designation_type = $this->settings('SHAREHOLDER','key')->id;
                        $sec_shareholder->is_srilankan = 'yes';
                        $sec_shareholder->title = 'Mr.'; //$sec['title'];
                        $sec_shareholder->first_name = $sec['firstname'];
                        $sec_shareholder->last_name =$sec['lastname'];
                        $sec_shareholder->address_id = $sec_to_share_addressId;
                        $sec_shareholder->nic = strtoupper($sec['nic']);
    
                        $sec_shareholder->passport_issued_country ='Sri Lanka';
                        $sec_shareholder->telephone =$sec['phone'];
                        $sec_shareholder->mobile =$sec['mobile'];
                        $sec_shareholder->email =$sec['email'];
                        $sec_shareholder->occupation =$sec['occupation'];
                    //    $sec_shareholder->no_of_shares ='100';
                        $sec_shareholder->date_of_appointment = date('Y-m-d',strtotime($sec['date']) );
                        $sec_shareholder->status =1;
    
                        $sec_shareholder->save();
                        $newSecShareHolderID = $sec_shareholder->id;

                        $coreShareGroupName='';
                        $coreShareValue = '';
                        if(isset($sec['shareType']) && $sec['shareType'] == 'core' && empty( $sec['coreGroupSelected'])  && $sec['coreShareGroupName'] && intval($sec['coreShareValue'])){

                            $coreShareGroupName = $sec['coreShareGroupName'];
                            $coreShareValue = $sec['coreShareValue'];
                        }
                        if(isset($sec['shareTypeEdit']) && $sec['shareTypeEdit'] == 'core' && empty( $sec['coreGroupSelectedEdit']) && $sec['coreShareGroupNameEdit'] && intval($sec['coreShareValueEdit'])){

                            $coreShareGroupName = $sec['coreShareGroupNameEdit'];
                            $coreShareValue = $sec['coreShareValueEdit'];
                        }
    
    
                        //add to single share group
                        $sec_shareholder_sharegroup = new ShareGroup;
                        $sec_shareholder_sharegroup->type ='core_share';
                        $sec_shareholder_sharegroup->name = $coreShareGroupName;
                        $sec_shareholder_sharegroup->no_of_shares =intval( $coreShareValue );
                        $sec_shareholder_sharegroup->status = 1;
    
                        $sec_shareholder_sharegroup->save();
                        $sec_shareholder_sharegroupID = $sec_shareholder_sharegroup->id;
    
                        //add to share table
                        $sec_shareholder_share = new Share;
                        $sec_shareholder_share->company_member_id = $newSecShareHolderID;
                        $sec_shareholder_share->group_id = $sec_shareholder_sharegroupID;
                        $sec_shareholder_share->save();
                    }
    
    
               }
    

             } //end if sesc is a shareholder


            /**** */

            
    
            $companyFirmId =  isset( $sec['secCompanyFirmId'] ) && $sec['secCompanyFirmId']  ? $sec['secCompanyFirmId'] : null ;

            if( isset($sec['secType'] ) && $sec['secType'] == 'firm' && empty($sec['id'] ) ) { //add company details
                $companyFirm = new CompanyFirms;
           
                $companyFirmAddress = new Address;
                $companyFirmAddress->province = $sec['firm_province'];
                $companyFirmAddress->district =  $sec['firm_district'];
                $companyFirmAddress->city =  $sec['firm_city'];
                $companyFirmAddress->address1 =  $sec['firm_localAddress1'];
                $companyFirmAddress->address2 =  $sec['firm_localAddress2'];
                $companyFirmAddress->postcode = $sec['firm_postcode'];
                $companyFirmAddress->country = $sec['country'];
              
                $companyFirmAddress->save();
                $companyFirmAddressId = $companyFirmAddress->id;


               
                $companyFirm->registration_no = $sec['pvNumber'];
                $companyFirm->name = $sec['firm_name'];
                $companyFirm->address_id = $companyFirmAddressId;

                $companyFirm->save();
                $companyFirmId = $companyFirm->id;
               

                 
            }

            if( isset($sec['secType'] ) && $sec['secType'] == 'firm' && isset($sec['id'] ) ) { //edit company details
                $companyFirm=   CompanyFirms::find( $sec['secCompanyFirmId'] );

            

                if(
                    !empty( $sec['firm_province'] ) &&
                    !empty( $sec['firm_district'] ) &&
                    !empty( $sec['firm_city'] ) &&
                    !empty( $sec['firm_localAddress1'] ) &&
                    !empty( $sec['firm_localAddress2'] ) &&
                    !empty( $sec['firm_postcode'] ) &&
                    !empty( $sec['pvNumber'] ) &&
                    !empty( $sec['firm_name'] )
                   
                  ){

                    $companyFirmAddress = new Address;
                    $companyFirmAddress->province = $sec['firm_province'];
                    $companyFirmAddress->district =  $sec['firm_district'];
                    $companyFirmAddress->city =  $sec['firm_city'];
                    $companyFirmAddress->address1 =  $sec['firm_localAddress1'];
                    $companyFirmAddress->address2 =  $sec['firm_localAddress2'];
                    $companyFirmAddress->postcode = $sec['firm_postcode'];
                    $companyFirmAddress->country ='Sri Lanka';
                
                    $companyFirmAddress->save();
                    $companyFirmAddressId = $companyFirmAddress->id;
                


                
                    $companyFirm->registration_no = $sec['pvNumber'];
                    $companyFirm->name = $sec['firm_name'];

                    $companyFirm->address_id = $companyFirmAddressId;
            

                    $companyFirm->save();
                    $companyFirmId = $companyFirm->id;

                }
               

                 
            }

           
            $updateSec->company_id = $company_id;
            $updateSec->designation_type = $this->settings('SECRETARY','key')->id;
            $updateSec->is_srilankan =  $sec['type'] != 'local' ?  'no' : 'yes';
           // $updateSec->title = $sec['title'];
            $updateSec->first_name = $sec['firstname'];
            $updateSec->last_name = $sec['lastname'];
            $updateSec->address_id = $addressId;
            $updateSec->nic = strtoupper($sec['nic']);
            $updateSec->passport_no = $sec['passport'];
            $updateSec->passport_issued_country = $sec['country'];
            $updateSec->telephone = $sec['phone'];
            $updateSec->mobile =$sec['mobile'];
            $updateSec->email = $sec['email'];
           // $updateSec->foreign_address_id =($sec['type'] !='local') ? $addressId: 0;
            $updateSec->occupation = $sec['occupation'];
          //  $updateSec->no_of_shares =0;
            $updateSec->date_of_appointment = date('Y-m-d',strtotime($sec['date']) );
            $updateSec->is_registered_secretary = ($sec['isReg'] == true ) ? 'yes' : 'no';
            $updateSec->secretary_registration_no =  ($sec['regDate'] ) ? $sec['regDate'] : NULL;
            $updateSec->status = 1;
            $updateSec->is_natural_person = $sec['secType'] =='natural' ? 'yes' : 'no';
            $updateSec->company_member_firm_id = $companyFirmId;

            $updateSec->save();
            
        }
        

        //loop through add shareholder list
        foreach($request->shareholders['shs'] as $shareholder ){

 
            if($shareholder['type'] == 'local'){

                $address = new Address;
                
                $address->province = $shareholder['province'];
                $address->district =  $shareholder['district'];
                $address->city =  $shareholder['city'];
                $address->address1 =  $shareholder['localAddress1'];
                $address->address2 =  $shareholder['localAddress2'];
                $address->postcode =  $shareholder['postcode'];
                $address->country = 'Sri Lanka';
              
                $address->save();
                $addressId = $address->id;

            }else{

                $address = new ForeignAddress;

                $address->province = $shareholder['province'];
                $address->district =  null;
                $address->city =  $shareholder['city'];
                $address->address1 =  $shareholder['localAddress1'];
                $address->address2 =  $shareholder['localAddress2'];
                $address->country = $shareholder['country'];
                $address->postcode =  $shareholder['postcode'];
              
                $address->save();
                $addressId = $address->id;

            }
           
           

            if(isset($shareholder['id']) && $shareholder['id'] ){
                $updateSh = CompanyMember::find($shareholder['id']);
            }else{
                $updateSh = new CompanyMember;
            }

            $updateSh->company_id = $company_id;
            $updateSh->designation_type = $this->settings('SHAREHOLDER','key')->id;
            $updateSh->is_srilankan =  $shareholder['type'] != 'local' ?  'no' : 'yes';
           // $updateSh->title = $director['title'];
            $updateSh->first_name = $shareholder['firstname'];
            $updateSh->last_name = $shareholder['lastname'];
            $updateSh->address_id = ($shareholder['type'] =='local') ? $addressId : 0;
            $updateSh->nic = strtoupper($shareholder['nic']);
            $updateSh->passport_no = $shareholder['passport'];
            $updateSh->passport_issued_country = $shareholder['country'];
            $updateSh->telephone = $shareholder['phone'];
            $updateSh->mobile =$shareholder['mobile'];
            $updateSh->email = $shareholder['email'];
            $updateSh->foreign_address_id =($shareholder['type'] !='local') ? $addressId: 0;
            $updateSh->occupation = $shareholder['occupation'];
          //  $updateSh->no_of_shares = $shareholder['share'];
            $updateSh->date_of_appointment = date('Y-m-d',strtotime($shareholder['date']) );
            $updateSh->status = 1;

            $updateSh->save();

            $shareHolderId = ( isset($shareholder['id']) && $shareholder['id'] ) ? $shareholder['id'] : $updateSh->id;

            

            if(  $shareholder['shareType'] == 'single' && intval($shareholder['noOfShares']) ) {

                if(isset($shareholder['id']) && $shareholder['id'] ){
                    $shareRow = Share::where('company_member_id', $shareholder['id'] )->first();
                    $shareholder_share = Share::find($shareRow['id']);
                }else{
                    $shareholder_share = new Share;
                }

                if(isset($shareholder['id']) && $shareholder['id'] ){
                    $shareholder_sharegroup = ShareGroup::find($shareRow['group_id']);
                }else{
                    $shareholder_sharegroup = new ShareGroup;
                }
    
                //add to single share group
                
                $shareholder_sharegroup->type ='single_share';
                $shareholder_sharegroup->name ='single_share_no_name';
                $shareholder_sharegroup->no_of_shares =intval( $shareholder['noOfShares'] );
                $shareholder_sharegroup->status = 1;

                $shareholder_sharegroup->save();
                $shareholder_sharegroupID = $shareholder_sharegroup->id;

                //add to share table

        
                $shareholder_share->company_member_id = $shareHolderId;
                $shareholder_share->group_id = $shareholder_sharegroupID;
                $shareholder_share->save();
            }

            if($shareholder['shareType'] == 'core' && isset($shareholder['coreGroupSelected']) &&  intval( $shareholder['coreGroupSelected']) ){

                if(isset($shareholder['id']) && $shareholder['id'] ){
                    $shareRow = Share::where('company_member_id', $shareholder['id'] )->first();
                    $shareholder_share = Share::find($shareRow['id']);
                }else{
                    $shareholder_share = new Share;
                }

                if(isset($shareholder['id']) && $shareholder['id'] ){
                    $shareholder_sharegroup = ShareGroup::find($shareRow['group_id']);
                }else{
                    $shareholder_sharegroup = new ShareGroup;
                }
                //add to share table
               
                $shareholder_share->company_member_id = $shareHolderId;
                $shareholder_share->group_id =intval( $shareholder['coreGroupSelected']);
                $shareholder_share->save();
            }

            if(
              $shareholder['shareType'] == 'core' &&
               ( empty( $shareholder['coreGroupSelected'])  ||  !intval( $shareholder['coreGroupSelected']) )  &&
                isset( $shareholder['coreShareGroupName']) && 
                $shareholder['coreShareGroupName'] && 
              intval($shareholder['noOfShares']) ) {


                //add to single share group
                $shareholder_sharegroup = new ShareGroup;
                $shareholder_sharegroup->type ='core_share';
                $shareholder_sharegroup->name = $shareholder['coreShareGroupName'];
                $shareholder_sharegroup->no_of_shares =intval( $shareholder['noOfShares'] );
                $shareholder_sharegroup->status = 1;

                $shareholder_sharegroup->save();
                $shareholder_sharegroupID = $shareholder_sharegroup->id;

                //add to share table
                $shareholder_share = new Share;
                $shareholder_share->company_member_id = $shareHolderId;
                $shareholder_share->group_id = $shareholder_sharegroupID;
                $shareholder_share->save();
            }
            
        }
    
        return response()->json([
            'message' => 'Successfully submitted stakeholders',
            'status' =>true,
            'data'   => array(
                 'docList' => @$this->generate_files($companyType->key,$request->companyId,$loginUserEmail),
                 'uploadList' => $this->files_for_upload($companyType->key,$request->companyId),
                 'uploadedList' => $this->uploadedDocs($request->companyId),
                
            )
        ], 200);
    }



    function checkNic(Request $request ){

         $nic = strtoupper($request->nic);
         $company_id = $request->companyId;
         $member_type = $request->memberType; // 1- director, 2- secrotory , 3 - shareholder

         if($member_type == 1 ) {
             $member_type = $this->settings('DERECTOR','key')->id;
         }
         if($member_type == 2 ){
              $member_type = $this->settings('SECRETARY','key')->id;
         }
         if($member_type == 3 ){
            $member_type = $this->settings('SHAREHOLDER','key')->id;
         }

         $members =CompanyMember::where('company_id','!=', $company_id)
                                ->where('nic', $nic)
                                ->where('designation_type', $member_type )
                                ->get();


         $members_count = count($members);

        

        /* if($members_count > 1 ){

            return response()->json([
                'message' => 'NIC already exist. Please create different Director',
                'status' =>false,
                'data'   => array(
                     'member_count' =>$members_count
                )
            ], 200);
         }*/
         if($members_count >= 1 ){

            $address = Address::where( 'id',$members[0]->address_id )->get()->first();

            return response()->json([
                'message' => 'Director record exists under this NIC.',
                'status' =>true,
                'data'   => array(
                    // 'member_count' =>$members_count,
                     'member_count' =>1,
                      'member_record'      => array($members[0]),
                      'address_record'     => $address
                )
            ], 200);
         }else{
            return response()->json([
                'message' => 'No record found under this NIC',
                'status' =>true,
                'data'   => array(
                     'member_count' =>0
                )
            ], 200);
         }


    }

    function getDocs($doc_type){

        $docs = $this->documents();

        return isset(  $docs[$doc_type] ) ?   $docs[$doc_type]  : false;

    }

    function documents(){


             $docs = array( 

                'COMPANY_TYPE_PRIVATE' => array(

                    'download' =>array(

                           array('name' =>'FORM 01', 'savedLocation' => "", 'view'=>'form1', 'specific' =>'','file_name_key' =>'form01' ),
                           array('name' =>'CONSENT AND CERTIFICATE OF DIRECTOR - FORM 18', 'savedLocation' => "", 'view' => 'form18', 'specific'=> 'director','file_name_key' =>'form18' ),
                           array('name' =>'CONSENT AND CERTIFICATE OF SECRETARY - FORM 19', 'savedLocation'=>"", 'view' => 'form19', 'specific'=> 'sec','file_name_key' =>'form19' )
                    ),
                        'upload' =>array( 
                            array('dbid' =>'18',  'name' =>'FORM 01','required' => true,'specific'=> '', 'type' => 'FORM01','uploaded_path' =>'' ),
                            array('dbid' =>'16','name' =>'CONSENT AND CERTIFICATE OF DIRECTOR - FORM 18', 'required' => true, 'specific'=> 'director', 'type' => 'FORM18','uploaded_path' =>''),
                            array('dbid' =>'17','name' =>'CONSENT AND CERTIFICATE OF SECRETARY - FORM 19','required' => true, 'specific'=> 'sec', 'type' => 'FORM19','uploaded_path' =>'' ),
                            array('dbid' =>'22','name' =>'Articles of the Association', 'required' => true,'specific'=> '' ,'type' => 'FORMAASSOC','uploaded_path' =>''),
                        )

                    ),

                    'COMPANY_TYPE_PUBLIC' => array(

                        'download' =>array(

                            array('name' =>'FORM 01', 'savedLocation' => "", 'view'=>'form1', 'specific' =>'','file_name_key' =>'form01' ),
                            array('name' =>'CONSENT AND CERTIFICATE OF DIRECTOR - FORM 18 ', 'savedLocation' => "", 'view' => 'form18', 'specific'=> 'director','file_name_key' =>'form18' ),
                            array('name' =>'CONSENT AND CERTIFICATE OF SECRETARY - FORM 19', 'savedLocation'=>"", 'view' => 'form19', 'specific'=> 'sec','file_name_key' =>'form19' )
                     ),
                        'upload' =>array( 
                            array('dbid' =>'18',  'name' =>'FORM 01','required' => true,'specific'=> '', 'type' => 'FORM01','uploaded_path' =>'' ),
                            array('dbid' =>'16','name' =>'CONSENT AND CERTIFICATE OF DIRECTOR - FORM 18', 'required' => true, 'specific'=> 'director', 'type' => 'FORM18','uploaded_path' =>''),
                            array('dbid' =>'17','name' =>'CONSENT AND CERTIFICATE OF SECRETARY - FORM 19','required' => true, 'specific'=> 'sec', 'type' => 'FORM19','uploaded_path' =>'' ),
                            array('dbid' =>'22','name' =>'Articles of the Association', 'required' => true,'specific'=> '' ,'type' => 'FORMAASSOC','uploaded_path' =>''),
                        )
    
    
                    ),
                    'COMPANY_TYPE_UNLIMITED' => array(

                        'download' =>array(

                            array('name' =>'FORM 01', 'savedLocation' => "", 'view'=>'form1', 'specific' =>'','file_name_key' =>'form01' ),
                            array('name' =>'CONSENT AND CERTIFICATE OF DIRECTOR - FORM 18', 'savedLocation' => "", 'view' => 'form18', 'specific'=> 'director','file_name_key' =>'form18' ),
                            array('name' =>'CONSENT AND CERTIFICATE OF SECRETARY - FORM 19', 'savedLocation'=>"", 'view' => 'form19', 'specific'=> 'sec','file_name_key' =>'form19' )
                         ),
                         'upload' =>array( 
                             array('dbid' =>'18',  'name' =>'FORM 01','required' => true,'specific'=> '', 'type' => 'FORM01','uploaded_path' =>'' ),
                             array('dbid' =>'16','name' =>'CONSENT AND CERTIFICATE OF DIRECTOR - FORM 18', 'required' => true, 'specific'=> 'director', 'type' => 'FORM18','uploaded_path' =>''),
                             array('dbid' =>'17','name' =>'CONSENT AND CERTIFICATE OF SECRETARY - FORM 19','required' => true, 'specific'=> 'sec', 'type' => 'FORM19','uploaded_path' =>'' ),
                             array('dbid' =>'22','name' =>'Articles of the Association', 'required' => true,'specific'=> '' ,'type' => 'FORMAASSOC','uploaded_path' =>''),
                         )
        
        
                    ),
                    'COMPANY_TYPE_GUARANTEE_32' => array(

                       'download' =>array(

                            array('name' =>'FORM 05', 'savedLocation' => "", 'view'=>'form5', 'specific' =>'','file_name_key' =>'form05' ),
                            array('name' =>'CONSENT AND CERTIFICATE OF DIRECTOR - FORM 18', 'savedLocation' => "", 'view' => 'form18', 'specific'=> 'director','file_name_key' =>'form18' ),
                            array('name' =>'CONSENT AND CERTIFICATE OF SECRETARY - FORM 19', 'savedLocation'=>"", 'view' => 'form19', 'specific'=> 'sec','file_name_key' =>'form19' )
                        ),
                        'upload' =>array( 
                            array('dbid' =>'19',  'name' =>'FORM 05','required' => true,'specific'=> '', 'type' => 'FORM05','uploaded_path' =>'' ),
                            array('dbid' =>'16','name' =>'CONSENT AND CERTIFICATE OF DIRECTOR - FORM 18', 'required' => true, 'specific'=> 'director', 'type' => 'FORM18','uploaded_path' =>''),
                            array('dbid' =>'17','name' =>'CONSENT AND CERTIFICATE OF SECRETARY - FORM 19','required' => true, 'specific'=> 'sec', 'type' => 'FORM19','uploaded_path' =>'' ),
                            array('dbid' =>'22','name' =>'Articles of the Association', 'required' => true,'specific'=> '' ,'type' => 'FORMAASSOC','uploaded_path' =>''),
                        )

            
                    ),  
                    'COMPANY_TYPE_GUARANTEE_34' => array(
   
                        'download' =>array(

                            array('name' =>'FORM 05', 'savedLocation' => "", 'view'=>'form5', 'specific' =>'','file_name_key' =>'form05' ),
                            array('name' =>'CONSENT AND CERTIFICATE OF DIRECTOR - FORM 18', 'savedLocation' => "", 'view' => 'form18', 'specific'=> 'director','file_name_key' =>'form18' ),
                            array('name' =>'CONSENT AND CERTIFICATE OF SECRETARY - FORM 19', 'savedLocation'=>"", 'view' => 'form19', 'specific'=> 'sec','file_name_key' =>'form19' )
                        ),
                        'upload' =>array( 
                            array('dbid' =>'19',  'name' =>'FORM 05','required' => true,'specific'=> '', 'type' => 'FORM05','uploaded_path' =>'' ),
                            array('dbid' =>'16','name' =>'CONSENT AND CERTIFICATE OF DIRECTOR - FORM 18', 'required' => true, 'specific'=> 'director', 'type' => 'FORM18','uploaded_path' =>''),
                            array('dbid' =>'17','name' =>'CONSENT AND CERTIFICATE OF SECRETARY - FORM 19','required' => true, 'specific'=> 'sec', 'type' => 'FORM19','uploaded_path' =>'' ),
                            array('dbid' =>'22','name' =>'Articles of the Association', 'required' => true,'specific'=> '' ,'type' => 'FORMAASSOC','uploaded_path' =>''),
                        )
            
                    ),  
                    'COMPANY_TYPE_OVERSEAS' => array(

                        'download' =>array(
                            array('name' =>'FORM 44', 'savedLocation' => "", 'view'=>'form44', 'specific' =>'','file_name_key' =>'form44' ),
                            array('name' =>'FORM 45', 'savedLocation' => "", 'view' => 'form45', 'specific'=> '','file_name_key' =>'form45' ),
                            array('name' =>'FORM 46', 'savedLocation'=>"", 'view' => 'form46', 'specific'=> '','file_name_key' =>'form46' )
                        ),
                        'upload' =>array( 
                            array('dbid' =>'100',  'name' =>'FORM 44','required' => true,'specific'=> '', 'type' => 'FORM44','uploaded_path' =>'' ),
                            array('dbid' =>'101','name' =>'FORM 45', 'required' => true, 'specific'=> '', 'type' => 'FORM45','uploaded_path' =>''),
                            array('dbid' =>'102','name' =>'FORM 46','required' => true, 'specific'=> '', 'type' => 'FORM46','uploaded_path' =>'' ),
                            array('dbid' =>'22','name' =>'Recently certified articles of association', 'required' => true,'specific'=> '' ,'type' => 'FORMAASSOC','uploaded_path' =>''),
                        )

            
                    ),
                    'COMPANY_TYPE_OFFSHORE' => array(

                        'download' =>array(  
            
                            array('name' =>'FORM 44', 'savedLocation' => "", 'view'=>'form44', 'specific' =>'','file_name_key' =>'form44' ),
                            array('name' =>'FORM 45', 'savedLocation' => "", 'view' => 'form45', 'specific'=> '','file_name_key' =>'form45' ),
                            array('name' =>'FORM 46', 'savedLocation'=>"", 'view' => 'form46', 'specific'=> '','file_name_key' =>'form46' )
                        ),
                        'upload' =>array(
            
                            array('dbid' =>'100',  'name' =>'FORM 44','required' => true,'specific'=> '', 'type' => 'FORM44','uploaded_path' =>'' ),
                            array('dbid' =>'101','name' =>'FORM 45', 'required' => true, 'specific'=> '', 'type' => 'FORM45','uploaded_path' =>''),
                            array('dbid' =>'102','name' =>'FORM 46','required' => true, 'specific'=> '', 'type' => 'FORM46','uploaded_path' =>'' ),
                            array('dbid' =>'103','name' =>'Recently certified copy of Company Incorporation certificate','required' => true, 'specific'=> '', 'type' => 'RCCCIC','uploaded_path' =>'' ),
                            array('dbid' =>'104','name' =>'Recently certified memorandum of association Copy','required' => true, 'specific'=> '', 'type' => 'RCMAC','uploaded_path' =>'' ),
                            array('dbid' =>'105','name' =>'Registered power of attorney Confirmation letter','required' => true, 'specific'=> '', 'type' => 'RPACL','uploaded_path' =>'' ),
 
                               
                                    
                        )
            
            
                    ), 

             );

             return $docs;
    }


    /**************************generate downloadable files***********************************/
        private function slugify($text) {
       
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
        }


        private function stakeholder_store($company_id){

            $director_list = CompanyMember::where('company_id',$company_id)
                                       ->where('designation_type',$this->settings('DERECTOR','key')->id)
                                       ->where('status',1)
                                       ->get();
        $directors = array();
        foreach($director_list as $director){

            $address_id =  ($director->foreign_address_id ) ? $director->foreign_address_id : $director->address_id;

             if(!$director->foreign_address_id){
                $address = Address::where('id',$address_id)->first();
             }else{
                $address = ForeignAddress::where('id',$address_id)->first();
             }

             $rec = array(
                 'id' => $director->id,
                'type' => ($director->is_srilankan) ? 'local' : 'foreign',
                'title' =>  $director->title,
                'firstname' => $director->first_name,
                'lastname' => $director->last_name,
                'province' =>  ( $address->province) ? $address->province : '',
                'district' =>  ($address->district) ? $address->district : '',
                'city' =>  ( $address->city) ? $address->city : '',
                'localAddress1' => ($address->address1) ? $address->address1 : '',
                'localAddress2' => ($address->address2) ? $address->address2 : '',
                'postcode' => ($address->postcode) ? $address->postcode : '',
                'nic'       => $director->nic,
                'passport'  => $director->passport_no,
                'country'   => $director->passport_issued_country,
                //'share'     => $director->no_of_shares,
                'date'      => $director->date_of_appointment,
                'phone' => $director->telephone,
                'mobile' => $director->mobile,
                'email' => $director->email,
                'occupation' => $director->occupation

             );
             $directors[] = $rec;
        }

        /******secretory list *****/
        $sec_list = CompanyMember::where('company_id',$company_id)
        ->where('designation_type',$this->settings('SECRETARY','key')->id)
        ->where('status',1)
        ->get();
        $secs = array();
        foreach($sec_list as $sec){

        $address_id =  ($sec->foreign_address_id ) ? $sec->foreign_address_id : $sec->address_id;
        
        if(!$sec->foreign_address_id){
            $address = Address::where('id',$address_id)->first();
         }else{
            $address = ForeignAddress::where('id',$address_id)->first();
         }

        $rec = array(
            'id' => $sec->id,
            'type' => ($sec->is_srilankan) ? 'local' : 'foreign',
            'title' =>  $sec->title,
        'firstname' => $sec->first_name,
        'lastname' => $sec->last_name,

        'province' =>  ( $address->province) ? $address->province : '',
        'district' =>  ($address->district) ? $address->district : '',
        'city' =>  ( $address->city) ? $address->city : '',
        'localAddress1' => ($address->address1) ? $address->address1 : '',
        'localAddress2' => ($address->address2) ? $address->address2 : '',
        'postcode' => ($address->postcode) ? $address->postcode : '',

        'nic'       => $sec->nic,
        'passport'  => $sec->passport_no,
        'country'   => $sec->passport_issued_country,
        //'share'     =>0,
        'date'      => $sec->date_of_appointment,
        'isReg'        => ($sec->is_registered_secretary =='yes') ? true :false,
        'regDate'      => ($sec->is_registered_secretary =='yes') ? $sec->secretary_registration_no :'',
        'phone' => $sec->telephone,
        'mobile' => $sec->mobile,
        'email' => $sec->email,
        'occupation' => $sec->occupation

        );
        $secs[] = $rec;
        }


        /******share holder list *****/
        $shareholder_list = CompanyMember::where('company_id',$company_id)
        ->where('designation_type',$this->settings('SHAREHOLDER','key')->id)
        ->where('status',1)
        ->get();
        $shareholders = array();
        foreach($shareholder_list as $shareholder){

   

        $address_id =  ($shareholder->foreign_address_id ) ? $shareholder->foreign_address_id : $shareholder->address_id;
        if(!$shareholder->foreign_address_id){
            $address = Address::where('id',$address_id)->first();
         }else{
            $address = ForeignAddress::where('id',$address_id)->first();
         }
          
         $shareRec = array(
             'value' => 0,
             'type' => ''
         );
         $shareRow = Share::where('company_member_id', $shareholder->id)->first();
         if(isset($shareRow->id) ){
                $shareGroup = ShareGroup::where('id', $shareRow->group_id)->first();
                
                $shareRec['value'] = $shareGroup['no_of_shares'];
                $shareRec['type'] = ($shareGroup['type'] == 'core_share') ? 'core share': 'single share';


         }

        $rec = array(
            'id' => $shareholder->id,
            'type' => ($shareholder->is_srilankan) ? 'local' : 'foreign',
            'title' =>  $shareholder->title,
        'firstname' => $shareholder->first_name,
        'lastname' => $shareholder->last_name,
       
        'province' =>  ( $address->province) ? $address->province : '',
        'district' =>  ($address->district) ? $address->district : '',
        'city' =>  ( $address->city) ? $address->city : '',
        'localAddress1' => ($address->address1) ? $address->address1 : '',
        'localAddress2' => ($address->address2) ? $address->address2 : '',
        'postcode' => ($address->postcode) ? $address->postcode : '',


        'nic'       => $shareholder->nic,
        'passport'  => $shareholder->passport_no,
        'country'   => $shareholder->passport_issued_country,
       // 'share'     => $shareholder->no_of_shares,
        'date'      => $shareholder->date_of_appointment,
        'phone' => $shareholder->telephone,
        'mobile' => $shareholder->mobile,
        'email' => $shareholder->email,
        'occupation' => $shareholder->occupation,
        'share' => $shareRec

        );
        $shareholders[] = $rec;
        }

         

        return array(

            'directors' => $directors,
            'secs'      => $secs,
            'shs'       => $shareholders
        );


        }


        function generate_files($doc_type,$companyId,$loginUserEmail){

            $loginUserInfo = User::where('email', $loginUserEmail)->first();
            $loginUserId = $loginUserInfo->people_id;
    
            $userPeople = People::where('id',$loginUserId)->first();
            $userAddressId = $userPeople->address_id;  
            $userAddress = Address::where('id', $userAddressId)->first();

            $docs = $this->getDocs($doc_type );

            $downloaded = $docs['download'];

            $generated_files = array(

                'other' => array(),
                'director' => array(),
                'sec'   => array()


            );
         //   return  $generated_files;
           // $pdff = App::make('dompdf.wrapper');

            if(count($downloaded)){
                foreach($downloaded as $file ){
                      
                    $name = $file['name'];
                    $file_name_key = $file['file_name_key'];

                    $stakeholder_store = $this->stakeholder_store($companyId);

                    $company_info = Company::where('id',$companyId)->first(); 
                    $company_address = Address::where('id',$company_info->address_id)->first();


                if($file['specific']  == 'director'){

                    $companyType = $this->settings($company_info->type_id,'id');

                    foreach( $stakeholder_store['directors'] as $director ){

                        $data = array(
                            'public_path' => public_path(),
                            'eroc_logo' => url('/').'/images/forms/eroc.png',
                            'gov_logo' => url('/').'/images/forms/govlogo.jpg',
                            'css_file' => url('/').'/images/forms/form1/form1.css',
                            'director' => $director,
                            'company_info' => $company_info,
                            'company_address' => $company_address,
                            'company_type' => $companyType->value,
                            'loginUser' => $userPeople,
                            'loginUserAddress' => $userAddress
                        );
                        
                            $directory = $companyId;
                            Storage::makeDirectory($directory);
            

                            $view = 'forms.'.$file['view'];
                            $director_id = $director['id'];
            
                            $pdf = PDF::loadView($view, $data);
                            $pdf->save(storage_path("app/$directory").'/'.$file_name_key.'-'.$director_id.'.pdf');

                            $file_row = array();
                            $file_row['name'] = $file['name'];
                            $file_row['stakeholder_name'] = $director['firstname'].' '.$director['lastname'];
                            $file_row['stakeholder_id'] = $director['id'];
                            $file_row['file_name_key'] = $file_name_key;
    
                         //   $file_row['download_link']  = "http://localhost/eroc/backend/storage/app/$directory/$file_name_key-$director_id.pdf";
                           // $file_row['download_link']  = URL::to('/')."/storage/app/$directory/$file_name_key-$director_id.pdf";
                           // $file_row['download_link']  = url('/').Storage::url("app/$directory/$file_name_key-$director_id.pdf");
                         
                           //$file_row['download_link']  = "http://localhost/git/eroc/front-end/storage/app/$directory/$file_name_key-$director_id.pdf";
                          // $file_row['download_link']  = "http://220.247.219.173/frontend/API/eRoc/storage/app/$directory/$file_name_key-$director_id.pdf";
                          $file_row['download_link']  = str_replace('public','',url('/')).Storage::url("app/$directory/$file_name_key-$director_id.pdf");
                      

                           $generated_files['director'][] = $file_row;

                    }
    
                }else if($file['specific']  == 'sec'){
                    $companyType = $this->settings($company_info->type_id,'id');
                    foreach( $stakeholder_store['secs'] as $sec ){

                        $data = array(
                            'public_path' => public_path(),
                            'eroc_logo' => url('/').'/images/forms/eroc.png',
                            'gov_logo' => url('/').'/images/forms/govlogo.jpg',
                            'css_file' => url('/').'/images/forms/form1/form1.css',
                            'sec' => $sec,
                            'company_info' => $company_info,
                            'company_address' => $company_address,
                            'company_type' => $companyType->value,
                            'loginUser' => $userPeople,
                            'loginUserAddress' => $userAddress
                        );
                        
                            $directory = $companyId;
                            Storage::makeDirectory($directory);
                            $view = 'forms.'.$file['view'];
                         
                            $sec_id = $sec['id'];
            
                            $pdf = PDF::loadView($view, $data);
                            $pdf->save(storage_path("app/$directory").'/'.$file_name_key.'-'. $sec['id'].'.pdf');

                            $file_row = array();
                            $file_row['name'] = $file['name'];
                            $file_row['stakeholder_name'] = $sec['firstname'].' '.$sec['lastname'];
                            $file_row['stakeholder_id'] = $sec['id'];
                            $file_row['file_name_key'] = $file_name_key;
    
                            //$file_row['download_link']  = "http://localhost/eroc/backend/storage/app/$directory/$file_name_key-$sec_id.pdf";
                        // $file_row['download_link']  = URL::to('/')."/storage/app/$directory/$file_name_key-$sec_id.pdf";
                         //   $file_row['download_link']  = url('/').Storage::url("app/$directory/$file_name_key-$sec_id.pdf");
                         //$file_row['download_link']  = "http://localhost/git/eroc/front-end/storage/app/$directory/$file_name_key-$sec_id.pdf";
                        // $file_row['download_link']  = "http://220.247.219.173/frontend/API/eRoc/storage/app/$directory/$file_name_key-$sec_id.pdf";
                        $file_row['download_link']  = str_replace('public','',url('/')).Storage::url("app/$directory/$file_name_key-$sec_id.pdf");
                            $generated_files['sec'][] = $file_row;

                    }


                }else{
                    $companyType = $this->settings($company_info->type_id,'id');
                    $data = array(
                        'public_path' => public_path(),
                        'eroc_logo' => url('/').'/images/forms/eroc.png',
                        'gov_logo' => url('/').'/images/forms/govlogo.jpg',
                        'css_file' => url('/').'/images/forms/form1/form1.css',
                        'directors' => $stakeholder_store['directors'],
                        'secs' => $stakeholder_store['secs'],
                        'shs' => $stakeholder_store['shs'],
                        'company_info' => $company_info,
                        'company_address' => $company_address,
                        'company_type' => $companyType->value,
                        'loginUser' => $userPeople,
                        'loginUserAddress' => $userAddress
                    );
            
                  //  dd($userPeople->first_name);
            
                    $directory = $companyId;
                    Storage::makeDirectory($directory);
  
                    $view = 'forms.'.$file['view'];
                    $pdf = PDF::loadView($view, $data);
                    $pdf->save(storage_path("app/$directory").'/'.$file_name_key.'.pdf');

                    $file_row = array();
                    $file_row['name'] = $file['name'];
                    $file_row['file_name_key'] = $file_name_key;
  
                   // $file_row['download_link']  = "http://localhost/eroc/backend/storage/app/$directory/$file_name_key.pdf";
                   // $file_row['download_link']  = URL::to('/')."/storage/app/$directory/$file_name_key.pdf";
                  //  $file_row['download_link']  = url('/').Storage::url("app/$directory/$file_name_key.pdf");
                   // $file_row['download_link']  = "http://localhost/git/eroc/front-end/storage/app/$directory/$file_name_key.pdf";
                 //   $file_row['download_link']  = " http://220.247.219.173/frontend/API/eRoc/storage/app/$directory/$file_name_key.pdf";
                 $file_row['download_link']  = str_replace('public','',url('/')).Storage::url("app/$directory/$file_name_key.pdf");
                   
                    $generated_files['other'][] =  $file_row;
                }


                }
            }
            
            return $generated_files;
        }


        function files_for_upload($doc_type,$companyId){

            $docs = $this->getDocs($doc_type );

            $uploaded = $docs['upload'];

            $generated_files = array(
                'other' => array(),
                'director' => array(),
                'sec'   => array()
            );
         

            if(count($uploaded)){
                $stakeholder_store = $this->stakeholder_store($companyId);
                foreach($uploaded as $file ){
                      
                    $name = $file['name'];
                

                if($file['specific']  == 'director'){

                    foreach( $stakeholder_store['directors'] as $director ){

                            $file_row = array();
                            $file_row['stakeholder_name'] = $director['firstname'].' '.$director['lastname'];
                            $file_row['stakeholder_id'] = $director['id'];
                            $file_row['is_required'] = $file['required'];
                            $file_row['file_name'] = $file['name'];
                            $file_row['file_type'] = $file['type'];
                            $file_row['dbid'] = $file['dbid'];

                            $generated_files['director'][] = $file_row;

                    }

                }else if($file['specific']  == 'sec'){

                    foreach( $stakeholder_store['secs'] as $sec ){

                        $file_row = array();
                        $file_row['stakeholder_name'] = $sec['firstname'].' '.$sec['lastname'];
                        $file_row['stakeholder_id'] = $sec['id'];
                        $file_row['is_required'] = $file['required'];
                        $file_row['file_name'] = $file['name'];
                        $file_row['file_type'] = $file['type'];
                        $file_row['dbid'] = $file['dbid'];
    
                        $generated_files['sec'][] = $file_row;
                    }

                }else{

                    $file_row = array();
                    $file_row['is_required'] = $file['required'];
                    $file_row['file_name'] = $file['name'];
                    $file_row['file_type'] = $file['type'];
                    $file_row['dbid'] = $file['dbid'];

                    $generated_files['other'][] = $file_row;

                }

                }
            }

            return $generated_files;
        
        }


       /**********debugging forms */
        function checkform01(){

            $companyId = 1808231344;

            $stakeholder_store = $this->stakeholder_store($companyId);
            $company_info = Company::where('id',$companyId)->first(); 
            $company_address = Address::where('id',$company_info->address_id)->first();

           // print_r($stakeholder_store['secs'] );

            $data = array(

                'public_path' => public_path(),
                'eroc_logo' => url('/').'/images/forms/eroc.png',
                'gov_logo' => url('/').'/images/forms/govlogo.jpg',
                'css_file' => url('/').'/images/forms/form1/form1.css',
                'directors' => $stakeholder_store['directors'],
                'secs' => $stakeholder_store['secs'],
                'shs' => $stakeholder_store['shs'],
                'company_info' => $company_info,
                'company_address' => $company_address
            );

            return view('forms/form44', $data);


        }

        function upload_file(){
            
            return view('forms/upload');
        } 

        function upload(Request $request){

            //print_r( $request->fileName);
           //die();

           $file_name =  uniqid().'.pdf';

           $file_type = $request->fileType;

           $file_user_id = $request->userId;

           if(isset($file_user_id)){

               $file_user_id = intval( $file_user_id );
           }

           $form_map = array(
             'FORM01'       => 18,
             'FORM18'       => 16,
             'FORM19'       => 17,
             'FORMAASSOC'   => 22,
             'FORM05'       => 100,
             'FORM44'       => 101,
             'FORM45'       => 102,
             'FORM46'       => 103,
             'RCCCIC'       => 104,
             'RCMAC'        => 105,
             'RPACL'        => 106
           );
           $file_type_id = (isset($form_map[$file_type])) ? $form_map[$file_type] : 0;

            $company_id = $request->companyId; // 1808231344;
           // $file_name = 'udara.pdf';
            $path = 'company/'.substr($company_id,0,2).'/'.substr($company_id,2,2).'/'.$company_id.'/IC';
          $path=  $request->file('uploadFile')->storeAs($path,$file_name,'sftp');

        if( $file_user_id){
            CompanyDocuments::where('document_id', $file_type_id)
            ->where('company_id', $company_id)
            ->where('company_member_id', $file_user_id)
            ->delete();
        }else{
            CompanyDocuments::where('document_id', $file_type_id)
            ->where('company_id', $company_id)
            ->delete();
        }
         
           
          $doc = new CompanyDocuments;
          $doc->document_id = $file_type_id;
          $doc->path = $path;
          $doc->company_id = $company_id;
          $doc->status =  $this->settings('DOCUMENT_PENDING','key')->id;;
          $doc->file_token = md5(uniqid());
          
          if($file_user_id){
              $doc->company_member_id = $file_user_id;
          }

          $doc->save();

          return response()->json([
            'message' => 'File uploaded successfully.',
            'status' =>true,
            'name' =>basename($path)
            
        ], 200);
        }


        function removeDoc(Request $request){

            $companyId = $request->companyId;
            $docTypeId = $request->docTypeId;
            $userId = $request->userId;

            if( $userId){
               $remove = CompanyDocuments::where('document_id', $docTypeId)
                ->where('company_id', $companyId)
                ->where('company_member_id', $userId)
                ->delete();
            }else{
               $remove = CompanyDocuments::where('document_id', $docTypeId)
                ->where('company_id', $companyId)
                ->delete();
            }

            return response()->json([
                'message' => 'File removed successfully.',
                'status' =>true,
                
            ], 200);




        }

        /*********** */

   
}
