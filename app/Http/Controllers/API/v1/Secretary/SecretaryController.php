<?php

namespace App\Http\Controllers\API\v1\Secretary;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helper\_helper;
use App\Address;
use App\Secretary;
use App\SecretaryFirm;
use App\People;
use App\User;
use App\Setting;
use App\SecretaryWorkingHistory;
use App\SecretaryFirmPartner;
use App\SecretaryDocument;
use App\Documents;

use Storage;
use App;
use URL;
use PDF;



class SecretaryController extends Controller
{

    use _helper;
    

    //save individual secretary data to database...
    public function saveSecretaryData (Request $request){

        $secAddressResidential = new Address();
        $secAddressResidential->address1 = $request->input('residentialLocalAddress1');
        $secAddressResidential->address2 = $request->input('residentialLocalAddress2');
        $secAddressResidential->city = $request->input('residentialCity');
        $secAddressResidential->district = $request->input('residentialDistrict');
        $secAddressResidential->province = $request->input('residentialProvince');
        $secAddressResidential->country = 'Sri Lanka';
        $secAddressResidential->postcode = '00001';
        $secAddressResidential->save();

        $secAddressBusiness = new Address();
        $bAddress = $request->input('businessName');
        if(!empty($bAddress)){
        $secAddressBusiness->address1 = $request->input('businessLocalAddress1');
        $secAddressBusiness->address2 = $request->input('businessLocalAddress2');
        $secAddressBusiness->city = $request->input('businessCity');
        $secAddressBusiness->district = $request->input('businessDistrict');
        $secAddressBusiness->province = $request->input('businessProvince');
        $secAddressBusiness->country = 'Sri Lanka';
        $secAddressBusiness->postcode = '00002';
        $secAddressBusiness->save();
        }

        $regUser = $request->input('registeredUser');
        
        $people = new People();
        if($regUser==false){
        // if not a registered user, bellow details insert into people table...
        $people->title = $request->input('title');
        $people->first_name = $request->input('firstname');
        $people->last_name = $request->input('lastname');
        $people->other_name = $request->input('othername');
        $people->nic = $request->input('nic');
        $people->address_id = $secAddressResidential->id;
        $people->is_srilankan = 'yes';
        $people->save();
        }

        // if applicant already roc user... 
        $secNic = $request->input('nic');
        $peopleId = People::where('nic', $secNic)->value('id');


        $loggedUserEmail = $request->input('loggedInUser');
        $loggedUserId = User::where('email', $loggedUserEmail)->value('id');
        
        $secinfo = new Secretary();
        $secinfo->title = $request->input('title');
        $secinfo->first_name = $request->input('firstname');
        $secinfo->last_name = $request->input('lastname');
        $secinfo->other_name = $request->input('othername');
        $secinfo->business_name = $request->input('businessName');
        $secinfo->which_applicant_is_qualified = $request->input('subClauseQualified');
        $secinfo->professional_qualifications = $request->input('pQualification');
        $secinfo->educational_qualifications = $request->input('eQualification');
        $secinfo->work_experience = $request->input('wExperience');
        $secinfo->address_id = $secAddressResidential->id;
        $secinfo->business_address_id = $secAddressBusiness->id;
        $secinfo->is_unsound_mind = $request->input('isUnsoundMind');
        $secinfo->is_insolvent_or_bankrupt = $request->input('isInsolventOrBankrupt');
        $secinfo->reason = $request->input('reason1');
        $secinfo->is_competent_court = $request->input('isCompetentCourt');
        $secinfo->competent_court_type = $request->input('reason2');
        $secinfo->status = $this->settings('SECRETARY_PENDING','key')->id;
        $secinfo->created_by = $loggedUserId;
        if($regUser==false){
        $secinfo->people_id = $people->id;   // if applicant is not a roc user... 
        }else{
        $secinfo->people_id = $peopleId;  // if applicant already roc user... 
        }
        $secinfo->save();

        
        $workHis = $request->input('workHis');
        $his = array();
        foreach($workHis as $history){
            if(!empty($history)){
            
            $secWorkHistory = new SecretaryWorkingHistory();
            $secWorkHistory->secretary_id =  $secinfo->id;
            $secWorkHistory->company_name = $history['companyName'];
            $secWorkHistory->position = $history['position'];
            $secWorkHistory->from = $history['from'];
            $secWorkHistory->to = $history['to'];
            $secWorkHistory->save();

            }
            $his[] = $history['companyName'];
        }

       // for load data to form1 view to convert as a  pdf...
       $id = $secinfo->id;
       $fname = $request->input('firstname');
       $lanme = $request->input('lastname');
       $oname = $request->input('othername');
       $fullname = $fname .' '. $lanme .' '. $oname ;
       $businessName = $request->input('businessName');
       $bAddress1 = $request->input('businessLocalAddress1');
       $bAddress2 = $request->input('businessLocalAddress2');
       $bCity = $request->input('businessCity');
       $bDistrict = $request->input('businessDistrict');
       $bProvince = $request->input('businessProvince');
       $bAddress = $bAddress1 .' '. $bAddress2 .' '. $bProvince .' '. $bDistrict .' '. $bCity ;
       $rAddress1 = $request->input('residentialLocalAddress1');
       $rAddress2 = $request->input('residentialLocalAddress2');
       $rCity = $request->input('residentialCity');
       $rDistrict = $request->input('residentialDistrict');
       $rProvince = $request->input('residentialProvince');
       $rAddress = $rAddress1 .' '. $rAddress2 .' '. $rProvince .' '. $rDistrict .' '. $rCity ;
       $subClauseQualified = $request->input('subClauseQualified');
       $pQualification =  $request->input('pQualification');
       $eQualification = $request->input('eQualification');
       $wExperience = $request->input('wExperience');
       $isUnsoundMind = $request->input('isUnsoundMind');
       $isInsolventOrBankrupt = $request->input('isInsolventOrBankrupt');
       $reason1 = $request->input('reason1');
       $isCompetentCourt = $request->input('isCompetentCourt');
       $reason2 = $request->input('reason2');
       $workHistory = $request->input('workHis');


        $data = [
            'name' => $fullname,
            'bname' =>  $businessName,
            'baddress' => $bAddress,
            'raddress' => $rAddress,
            'sublauseualified' => $subClauseQualified,
            'pqualification' =>  $pQualification,
            'equalification' => $eQualification,
            'wexperience' => $wExperience,
            'isunsoundMind' => $isUnsoundMind,
            'isinsolventorbankrupt' => $isInsolventOrBankrupt,
            'reason1' => $reason1,
            'iscompetentcourt' => $isCompetentCourt,
            'reason2' => $reason2,
            'workhistory' => $workHistory,
        ];

        $pdf = PDF::loadView('secretary-forms/form1', $data);
        $pdf->save(storage_path("app/secretary").'/'.$fname.'-'.$id.'.pdf');
        $downloadlink = str_replace('public','',url('/')).Storage::url("app/secretary/$fname-$id.pdf");



        $data = array();
        $data[] = $secinfo->id;
        $secId = $secinfo->id;

        return response()->json([
            'message' => 'Sucess!!!',
            'status' =>true,
            'data'   => $downloadlink,
            'secId' => $secId,
        ], 200);



    }



    // load individual secretary data using nic number...
    public function loadSecretaryData(Request $request){


        if(!$request->nic){

            return response()->json([
                'message' => 'We can \'t find a User.',
                'status' =>false
            ], 200);
        }


        $secretaryDetails = People::where('nic',$request->nic)->first();
        $secretaryAddressId = $secretaryDetails->address_id;
        $secretaryAddress = Address::where('id', $secretaryAddressId)->first();
        $secretaryTitleId = $secretaryDetails->title;
        $secretaryTitle = Setting::where('id', $secretaryTitleId)->value('value');




        return response()->json([
            'message' => 'Sucess!!!',
            'user' => true, // to check applicant already registered as roc user...  
            'status' =>true,
            'data'   => array(
                            
                            'secretary'     => $secretaryDetails,
                            'secretaryAddress'=> $secretaryAddress,
                            'secretaryTitle'     => $secretaryTitle,  

                        )
        ], 200);


    }


    //save secretary firm data(firm info and members info) to database...
    public function saveSecretaryFirmData(Request $request){

        $secAddressFirm = new Address();
        $secAddressFirm->address1 = $request->input('businessLocalAddress1');
        $secAddressFirm->address2 = $request->input('businessLocalAddress2');
        $secAddressFirm->city = $request->input('businessCity');
        $secAddressFirm->district = $request->input('businessDistrict');
        $secAddressFirm->province = $request->input('businessProvince');
        $secAddressFirm->country = 'Sri Lanka';
        $secAddressFirm->postcode = '00003';
        $secAddressFirm->save();


        $secFirminfo = new SecretaryFirm();
        $secFirminfo->registration_no = $request->input('registrationNumber');
        $secFirminfo->name = $request->input('name');
        $secFirminfo->address_id = $secAddressFirm->id;
        $secFirminfo->is_undertake_secretary_work = $request->input('isUndertakeSecWork');
        $secFirminfo->is_unsound_mind = $request->input('isUnsoundMind');
        $secFirminfo->is_insolvent_or_bankrupt = $request->input('isInsolventOrBankrupt');
        $secFirminfo->reason = $request->input('reason1');
        $secFirminfo->is_competent_court = $request->input('isCompetentCourt');
        $secFirminfo->competent_court_type = $request->input('reason2');
        $secFirminfo->status = $this->settings('SECRETARY_PENDING','key')->id;
        $secFirminfo->save();


      
      $partnerDetails = $request->input('firmPartners');
      foreach($partnerDetails as $partner){
          if(!empty($partner)){
          
          $secFirmPartners = new SecretaryFirmPartner();
          $secFirmPartners->firm_id =  $secFirminfo->id;
          $secFirmPartners->name = $partner['name'];
          $secFirmPartners->address = $partner['residentialAddress'];
          $secFirmPartners->citizenship = $partner['citizenship'];
          $secFirmPartners->which_qualified = $partner['whichQualified'];
          $secFirmPartners->professional_qualifications = $partner['pQualification'];
          $secFirmPartners->save();

          }
          
      }
     
      return response()->json([
        'message' => 'Sucess!!!',
        'status' =>true,
    ], 200);


    }

    //save secretary pvt limited data(company info and members info) to database...
    public function saveSecretaryPvtData(Request $request){


        $secAddressFirm = new Address();
        $secAddressFirm->address1 = $request->input('businessLocalAddress1');
        $secAddressFirm->address2 = $request->input('businessLocalAddress2');
        $secAddressFirm->city = $request->input('businessCity');
        $secAddressFirm->district = $request->input('businessDistrict');
        $secAddressFirm->province = $request->input('businessProvince');
        $secAddressFirm->country = 'Sri Lanka';
        $secAddressFirm->postcode = '00004';
        $secAddressFirm->save();


        $secFirminfo = new SecretaryFirm();
        $secFirminfo->registration_no = $request->input('registrationNumber');
        $secFirminfo->name = $request->input('name');
        $secFirminfo->address_id = $secAddressFirm->id;
        $secFirminfo->is_undertake_secretary_work = $request->input('isUndertakeSecWork');
        $secFirminfo->is_unsound_mind = $request->input('isUnsoundMind');
        $secFirminfo->is_insolvent_or_bankrupt = $request->input('isInsolventOrBankrupt');
        $secFirminfo->reason = $request->input('reason1');
        $secFirminfo->is_competent_court = $request->input('isCompetentCourt');
        $secFirminfo->competent_court_type = $request->input('reason2');
        $secFirminfo->status = $this->settings('SECRETARY_PENDING','key')->id;
        $secFirminfo->save();


      
      $partnerDetails = $request->input('firmPartners');
      foreach($partnerDetails as $partner){
          if(!empty($partner)){
          
          $secFirmPartners = new SecretaryFirmPartner();
          $secFirmPartners->firm_id =  $secFirminfo->id;
          $secFirmPartners->name = $partner['name'];
          $secFirmPartners->address = $partner['residentialAddress'];
          $secFirmPartners->citizenship = $partner['citizenship'];
          $secFirmPartners->which_qualified = $partner['whichQualified'];
          $secFirmPartners->professional_qualifications = $partner['pQualification'];
          $secFirmPartners->save();

          }
          
      }

      return response()->json([
        'message' => 'Sucess!!!',
        'status' =>true,
    ], 200);


    }


    //load name, address, citizenship, data of firm and pvt limited members... using nic
    public function loadSecretaryFirmPartnerData(Request $request){


        if(!$request->nic){

            return response()->json([
                'message' => 'We can \'t find a User.',
                'status' =>false
            ], 200);
        }


        $partnerDetails = People::where('nic',$request->nic)->first();
        // $partnerFname = $partnerDetails->first_name;
        // $partnerLname = $partnerDetails->last_name;
        // $partnerOname = $partnerDetails->otehr_name;
        $partnerIsSrilankan = $partnerDetails->is_srilankan;
        $partnerAddressId = $partnerDetails->address_id;
        $partnerAddress = Address::where('id', $partnerAddressId)->first();
        $partnerTitleId = $partnerDetails->title;
        $partnerTitle = Setting::where('id', $partnerTitleId)->first();




        return response()->json([
            'message' => 'Sucess!!!',
            'status' =>true,
            'data'   => array(
                            
                            'partner'     => $partnerDetails,
                            'partnerAddress'=> $partnerAddress,
                            'partnerTitle'     => $partnerTitle,  

                        )
        ], 200);


    }


    //for upload secretary individual pdf...
    public function secretaryIndividualUploadPdf(Request $request){


        $fileName =  uniqid().'.pdf';
        $token = md5(uniqid());

        $secId = $request->secId;
        $docType = $request->docType;

        $path = 'secretary/'.$secId;
        $path=  $request->file('uploadFile')->storeAs($path,$fileName,'sftp');


        $docname;
        if($docType=='applicationUpload'){
            $docname = 'Application';
        }elseif($docType=='eCertificateUpload'){
            $docname = 'Education Certification';
        }elseif($docType=='pCertificateUpload'){
            $docname = 'Professional Certificate';
        }elseif($docType=='experienceUpload'){
            $docname = 'Experience Certificate';
        }

        
        $secDoc = new SecretaryDocument;


        $secDoc->document_id = Documents::where('name', $docname)->value('id');
        $secDoc->secretary_id = $secId;
        $secDoc->name = $request->description;
        $secDoc->file_token = $token;
        $secDoc->path = $path;
        $secDoc->status =  $this->settings('DOCUMENT_PENDING','key')->id;

        $secDoc->save();
        
        $secdocId = $secDoc->id;



          return response()->json([
            'message' => 'File uploaded successfully.',
            'status' =>true,
            'name' =>basename($path),
            'doctype' =>$docType,
            'docid' =>$secdocId, // for delete pdf...
            'token' =>$token,

            
        ], 200);

    }

    //for delete secretary individual pdf...
    function deleteSecretaryIndividualPdf(Request $request){

        $docId = $request->documentId;

        if($docId){
           $remove = SecretaryDocument::where('id', $docId)->delete();
        }

        return response()->json([
            'message' => 'File removed successfully.',
            'status' =>true,
            
        ], 200);

    }


    //for view secretary pdf...
    public function getDocumentSecretary(Request $request)
    {
        $document = SecretaryDocument::where('file_token', $request->token)->first();
        
        if (!empty($document)) {
            // return response()->download(Storage::disk('sftp')->get($document->path));
            return Storage::disk('sftp')->get($document->path);
        } else {
            return response()->json(['error' => 'Error decoding authentication request.'], 401);
        }
    }


    

}
