<?php

namespace App\Http\Controllers\API\v1\Society;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Society;
use App\User;
use App\Address;
use App\SocietyMember;
use App\Setting;
use App\SocietyDocument;
use App\Documents;
use App\DocumentsGroup;
use App\Http\Helper\_helper;
use PDF;


class SocietyController extends Controller
{
    use _helper;
    public function saveSocietyData (Request $request){
        if($request->input('approval_need')){
            $typeId = 1;
        }
        else{
            $typeId = 0;
        }

        $peopleId = User::where('email', $request->input('email'))->value('id');

        $society = new Society();
        $society->name_of_society = $request->input('name_of_society');
        $society->place_of_office = $request->input('place_of_office');
        $society->whole_of_the_objects = $request->input('whole_of_the_objects');
        $society->funds = $request->input('funds');
        $society->terms_of_admission = $request->input('terms_of_admission');
        $society->condition_under_which_any = $request->input('condition_under_which_any');
        $society->fines_and_foreitures = $request->input('fines_and_foreitures');
        $society->mode_of_holding_meetings = $request->input('mode_of_holding_meetings');
        $society->manner_of_rules = $request->input('manner_of_rules');
        $society->investment_of_funds = $request->input('investment_of_funds');
        $society->keeping_accounts = $request->input('keeping_accounts');
        $society->audit_of_the_accounts = $request->input('audit_of_the_accounts');
        $society->annual_returns = $request->input('annual_returns');
        $society->number_of_members = $request->input('number_of_members');
        $society->inspection_of_the_books = $request->input('inspection_of_the_books');
        $society->appointment_and_removal_committee = $request->input('appointment_and_removal_committee');
        $society->disputes_manner = $request->input('disputes_manner');
        $society->case_of_society = $request->input('case_of_society');
        $society->case_of_society = $request->input('case_of_society');
        $society->created_by = $peopleId;
        $society->name = $request->input('name');
        $society->name_si = $request->input('sinhalaName');
        $society->name_ta = $request->input('tamilname');
        $society->type_id = $typeId;
        $society->abbreviation_desc = $request->input('abreviations');
        $society->status = $this->settings('SOCIETY_PROCESSING','key')->id;
        $society->save();
        $societyid = $society->id;


        $presidents = $request->input('presidentsArr');
        foreach($presidents as $president){
            if(!empty($president)){

                $address = new Address();
                $address->address1 = $president['localAddress1'];
                $address->address2 = $president['localAddress2'];
                $address->province = $president['province'];
                $address->district = $president['district'];
                $address->city = $president['city'];
                $address->postcode = $president['postcode'];
                $address->country = 'Sri Lanka';
                $address->save(); 
                
                $memb = new SocietyMember();
                $memb->address_id = $address->id;
                $memb->first_name = $president['firstname'];
                $memb->last_name = $president['lastname'];
                $memb->society_id = $societyid;
                $memb->designation_type = 1;
                $memb->type = 1;
                $memb->nic = $president['nic'];
                $memb->contact_no = $president['contact_number'];
                
                $memb->save(); 


            }
            
        }

        $secretaries = $request->input('secretariesArr');
        foreach($secretaries as $secretary){
            if(!empty($secretary)){

                $address = new Address();
                $address->address1 = $secretary['localAddress1'];
                $address->address2 = $secretary['localAddress2'];
                $address->province = $secretary['province'];
                $address->district = $secretary['district'];
                $address->city = $secretary['city'];
                $address->postcode = $secretary['postcode'];
                $address->country = 'Sri Lanka';
                $address->save(); 
                
                $memb = new SocietyMember();
                $memb->address_id = $address->id;
                $memb->first_name = $secretary['firstname'];
                $memb->last_name = $secretary['lastname'];
                $memb->society_id = $societyid;
                $memb->designation_type = 2;
                $memb->type = 1;
                $memb->nic = $secretary['nic'];
                $memb->contact_no = $secretary['contact_number'];
                
                $memb->save(); 


            }
            
        }

        $treasurers = $request->input('treasurersArr');
        foreach($treasurers as $treasurer){
            if(!empty($treasurer)){

                $address = new Address();
                $address->address1 = $treasurer['localAddress1'];
                $address->address2 = $treasurer['localAddress2'];
                $address->province = $treasurer['province'];
                $address->district = $treasurer['district'];
                $address->city = $treasurer['city'];
                $address->postcode = $treasurer['postcode'];
                $address->country = 'Sri Lanka';
                $address->save(); 
                
                $memb = new SocietyMember();
                $memb->address_id = $address->id;
                $memb->first_name = $treasurer['firstname'];
                $memb->last_name = $treasurer['lastname'];
                $memb->society_id = $societyid;
                $memb->designation_type = 3;
                $memb->type = 1;
                $memb->nic = $treasurer['nic'];
                $memb->contact_no = $treasurer['contact_number'];
                
                $memb->save(); 


            }
            
        }

        $addits = $request->input('additsArr');
        foreach($addits as $addit){
            if(!empty($addit)){

                $address = new Address();
                $address->address1 = $addit['localAddress1'];
                $address->address2 = $addit['localAddress2'];
                $address->province = $addit['province'];
                $address->district = $addit['district'];
                $address->city = $addit['city'];
                $address->postcode = $addit['postcode'];
                $address->country = 'Sri Lanka';
                $address->save(); 
                
                $memb = new SocietyMember();
                $memb->address_id = $address->id;
                $memb->first_name = $addit['firstname'];
                $memb->last_name = $addit['lastname'];
                $memb->society_id = $societyid;
                $memb->designation_type = 4;
                $memb->type = 1;
                $memb->nic = $addit['nic'];
                $memb->contact_no = $addit['contact_number'];
                
                $memb->save(); 


            }
            
        }

        $membs = $request->input('membsArr');
        foreach($membs as $member){
            if(!empty($member)){
                if($member['type']==1){

                $address = new Address();
                $address->address1 = $member['localAddress1'];
                $address->address2 = $member['localAddress2'];
                $address->province = $member['province'];
                $address->district = $member['district'];
                $address->city = $member['city'];
                $address->postcode = $member['postcode'];
                $address->country = 'Sri Lanka';
                $address->save(); 
                
                $memb = new SocietyMember();
                $memb->address_id = $address->id;
                $memb->first_name = $member['firstname'];
                $memb->last_name = $member['lastname'];
                $memb->society_id = $societyid;
                $memb->designation_type = 5;
                $memb->type = 1;
                $memb->nic = $member['nic'];
                $memb->contact_no = $member['contact_number'];
                
                $memb->save();

                }
                elseif($member['type']==2){

                    $address = new Address();
                $address->address1 = $member['localAddress1'];
                $address->address2 = $member['localAddress2'];
                $address->province = $member['province'];
                $address->district = $member['district'];
                $address->city = $member['city'];
                $address->postcode = $member['postcode'];
                $address->country = $member['country'];
                $address->save(); 
                
                $memb = new SocietyMember();
                $memb->address_id = $address->id;
                $memb->first_name = $member['firstname'];
                $memb->last_name = $member['lastname'];
                $memb->society_id = $societyid;
                $memb->designation_type = 5;
                $memb->type = 2;
                $memb->contact_no = $member['contact_number'];
                $memb->passport_no = $member['passport'];
                $memb->save();

                }
               
            }
            
        }
        
    //     $data = [
    //         'name' => $fullname,
    //         'bname' =>  $businessName,
    //         'baddress' => $bAddress,
    //         'raddress' => $rAddress,
    //         'sublauseualified' => $subClauseQualified,
    //         'pqualification' =>  $pQualification,
    //         'equalification' => $eQualification,
    //         'wexperience' => $wExperience,
    //         'isunsoundMind' => $isUnsoundMind,
    //         'isinsolventorbankrupt' => $isInsolventOrBankrupt,
    //         'reason1' => $reason1,
    //         'iscompetentcourt' => $isCompetentCourt,
    //         'reason2' => $reason2,
    //         'workhistory' => $workHistory,
    //     ];

    //     $pdf = PDF::loadView('secretary-forms/form1', $data);
    //     $pdf->save(storage_path("app/secretary").'/'.$fname.'-'.$id.'.pdf');
    //     $downloadlink = str_replace('public','',url('/')).Storage::url("app/secretary/$fname-$id.pdf");



    //     $data = array();
    //     $data[] = $secinfo->id;
    //     $secId = $secinfo->id;

        return response()->json([
            'message' => 'Sucess!!!',
            'status' =>true,
            'socID' => $societyid,
        ], 200);



    }


    // for load individual and firm registered secretary data to profile card...
public function loadRegisteredSocietyData(Request $request){
    if($request){
        $loggedUserEmail = $request->input('loggedInUser');
        $loggedUserId = User::where('email', $loggedUserEmail)->value('id');

        $society = Society::leftJoin('settings','societies.status','=','settings.id')
                    ->where('societies.created_by',$loggedUserId)
                    ->get(['societies.id','societies.name','societies.name_si','societies.name_ta','societies.abbreviation_desc','societies.type_id','societies.created_at','settings.key as status','settings.value as value']);
      
        
       
        if($society){
            return response()->json([
                'message' => 'Sucess!!!',
                'status' =>true,
                'data'   => array(
                                'society'     => $society                      
                            )
            ], 200);
        }
    }
}

// for individual society payments...
public function societyPay(Request $request){
    if(isset($request)){
        $socId = $request->socId;
        $societyPay =  array(
            'status'    => $this->settings('SOCIETY_PENDING','key')->id
        );
        Society::where('id',  $socId)->update($societyPay);
        
        return response()->json([
            'message' => 'Payment Successful.',
            'status' =>true,
        ], 200);
    }
}

//for view society pdf...
public function generate_pdf() {
    $pdf = PDF::loadView('society-forms/affidavit');
    return $pdf->stream('affidavit.pdf');
}


public function generate_App_pdf(Request $request) {

       $societyid = $request->societyid;
       $societyRecord = Society::where('id',$societyid)->first();

       $president = SocietyMember::where('society_id',$societyid)->where('designation_type',1)->first();
       $presidentAddRecord = Address::where('id',$president->address_id)->first();

       $secretary = SocietyMember::where('society_id',$societyid)->where('designation_type',2)->first();
       $secretaryAddRecord = Address::where('id',$secretary->address_id)->first();

       $treasurer = SocietyMember::where('society_id',$societyid)->where('designation_type',3)->first();
       $treasurerAddRecord = Address::where('id',$treasurer->address_id)->first();

       $memberlist=array();
    
       $members = SocietyMember::where('society_id',$societyid)->where('designation_type',4)->limit(5)->get();
       foreach($members as $member)
       {   
           $imember = array();

           $memberAddRecord = Address::where('id',$member->address_id)->first();

           $imember['m_full_name'] = $member->first_name." ".$member->last_name;
           $imember['m_nic'] = $member->nic;
           $imember['m_personal_address'] = $memberAddRecord->address1." ".$memberAddRecord->address2." ".$memberAddRecord->city;
           $imember['m_contact_number'] = $member->contact_no;
           array_push($memberlist,$imember);
       }
  
       
       $fieldset = array(
            'english_name_of_society' =>$societyRecord->name,
            'name_of_society' => $societyRecord->name_of_society, 
            'place_of_office' => $societyRecord->place_of_office, 
            'whole_of_the_objects' => $societyRecord->whole_of_the_objects,
            'funds' => $societyRecord->funds, 
            'terms_of_admission' => $societyRecord->terms_of_admission, 
            'condition_under_which_any' => $societyRecord->condition_under_which_any,
            'fines_and_foreitures' => $societyRecord->fines_and_foreitures, 
            'mode_of_holding_meetings' => $societyRecord->mode_of_holding_meetings, 
            'manner_of_rules' => $societyRecord->manner_of_rules, 
            'investment_of_funds' => $societyRecord->investment_of_funds, 
            'keeping_accounts' => $societyRecord->keeping_accounts, 
            'audit_of_the_accounts' => $societyRecord->audit_of_the_accounts,
            'annual_returns' => $societyRecord->annual_returns,
            'number_of_members' => $societyRecord->number_of_members,
            'inspection_of_the_books' => $societyRecord->inspection_of_the_books,
            'appointment_and_removal_committee' => $societyRecord->appointment_and_removal_committee,
            'disputes_manner' => $societyRecord->disputes_manner, 
            'case_of_society' => $societyRecord->case_of_society,
            'p_full_name' => $president->first_name." ".$president->last_name,
            'p_nic' => $president->nic,
            'p_personal_address' =>$presidentAddRecord->address1." ".$presidentAddRecord->address2." ".$presidentAddRecord->city,
            'p_contact_number' => $president->contact_no,
            's_full_name' => $secretary->first_name." ".$secretary->last_name,
            's_nic' => $secretary->nic,
            's_personal_address' =>$secretaryAddRecord->address1." ".$secretaryAddRecord->address2." ".$secretaryAddRecord->city,
            's_contact_number' => $secretary->contact_no,
            't_full_name' => $treasurer->first_name." ".$treasurer->last_name,
            't_nic' => $treasurer->nic,
            't_personal_address' =>$treasurerAddRecord->address1." ".$treasurerAddRecord->address2." ".$treasurerAddRecord->city,
            't_contact_number' => $treasurer->contact_no,
            'member'=>$memberlist,
            

        );
    
    
    
    
     $pdf = PDF::loadView('society-forms/application',$fieldset);

    //  return response()->json([
    //     'message' => 'Sucess!!!',
    //     'user' => true, // to check applicant already registered as roc user...  
    //     'status' =>true,
    //     'data'   => $fieldset,
    // ], 200);

    $pdf->stream('application.pdf');

    // $pdf = PDF::loadView('society-forms/society-application');
    // return $pdf->stream('society-application.pdf');
    //,$presidentdetails
}

// main 8 members load societyMemberLoad
public function societyMemberLoad(Request $request){

    if(isset($request)){
        $socID = $request->input('societyid');
        $members = SocietyMember::where('society_id',$socID)->limit(8)->get();


        if($members){
            return response()->json([
                'message' => 'Sucess!!!',
                'status' =>true,
                'data'   => array(
                                'member'     => $members                      
                            )
            ], 200);
        }
    }

}


//for upload secretary individual pdf...
public function societyUploadPdf(Request $request){

    if(isset($request)){

    $fileName =  uniqid().'.pdf';
    $token = md5(uniqid());

    $socId = $request->socId;
    $docType = $request->docType;
    $pdfName = $request->filename;

    $path = 'society/'.$socId;
    $path=  $request->file('uploadFile')->storeAs($path,$fileName,'sftp');


    $docId;
    if($docType=='applicationUpload'){
        $docIdArray = DocumentsGroup::leftJoin('documents','document_groups.id','=','documents.document_group_id')
                                       ->where('document_groups.request_type','SOCIETY')		
                                       ->where('documents.name','Application')
                                       ->get(['documents.id']);
    $docId = $docIdArray[0]['id'];
    }elseif($docType=='affidavitUpload'){
        $docIdArray = DocumentsGroup::leftJoin('documents','document_groups.id','=','documents.document_group_id')
                                       ->where('document_groups.request_type','SOCIETY')		
                                       ->where('documents.name','Affidavit')
                                       ->get(['documents.id']);
    $docId = $docIdArray[0]['id'];   
    }elseif($docType=='approvalUpload'){
        $docIdArray = DocumentsGroup::leftJoin('documents','document_groups.id','=','documents.document_group_id')
                                       ->where('document_groups.request_type','SOCIETY')		
                                       ->where('documents.name','Approval Letter')
                                       ->get(['documents.id']);
    $docId = $docIdArray[0]['id'];  
    }
   
    $socDoc = new SocietyDocument;
    $socDoc->document_id = $docId;
    $socDoc->society_id = $socId;
    $socDoc->name = $pdfName;
    $socDoc->description = $request->description;
    $socDoc->file_token = $token;
    $socDoc->path = $path;
    $socDoc->status =  $this->settings('DOCUMENT_PENDING','key')->id;
    $socDoc->save();
    
    $socdocId = $socDoc->id;

      return response()->json([
        'message' => 'File uploaded successfully.',
        'status' =>true,
        'name' =>basename($path),
        'doctype' =>$docType,
        'docid' =>$socdocId, // for delete pdf...
        'token' =>$token,
        'pdfname' =>$pdfName,
        ], 200);

    }

}


}


