<?php

namespace App\Http\Controllers\API\v1\Society;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Society;
use App\User;

class SocietyController extends Controller
{
    public function saveSocietyData (Request $request){

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
        $society->type_id = 1;
        $society->abbreviation_desc = $request->input('abreviations');
        $society->save();

    //     $secAddressBusiness = new Address();
    //     $bAddress = $request->input('businessName');
    //     if(!empty($bAddress)){
    //     $secAddressBusiness->address1 = $request->input('businessLocalAddress1');
    //     $secAddressBusiness->address2 = $request->input('businessLocalAddress2');
    //     $secAddressBusiness->city = $request->input('businessCity');
    //     $secAddressBusiness->district = $request->input('businessDistrict');
    //     $secAddressBusiness->province = $request->input('businessProvince');
    //     $secAddressBusiness->country = 'Sri Lanka';
    //     $secAddressBusiness->postcode = '00002';
    //     $secAddressBusiness->save();
    //     }

    //     $regUser = $request->input('registeredUser');
        
    //     $people = new People();
    //     if($regUser==false){
    //     // if not a registered user, bellow details insert into people table...
    //     $people->title = $request->input('title');
    //     $people->first_name = $request->input('firstname');
    //     $people->last_name = $request->input('lastname');
    //     $people->other_name = $request->input('othername');
    //     $people->nic = $request->input('nic');
    //     $people->address_id = $secAddressResidential->id;
    //     $people->is_srilankan = 'yes';
    //     $people->save();
    //     }

    //     // if applicant already roc user... 
    //     $secNic = $request->input('nic');
    //     $peopleId = People::where('nic', $secNic)->value('id');


    //     $loggedUserEmail = $request->input('loggedInUser');
    //     $loggedUserId = User::where('email', $loggedUserEmail)->value('id');
        
    //     $secinfo = new Secretary();
    //     $secinfo->title = $request->input('title');
    //     $secinfo->first_name = $request->input('firstname');
    //     $secinfo->last_name = $request->input('lastname');
    //     $secinfo->other_name = $request->input('othername');
    //     $secinfo->business_name = $request->input('businessName');
    //     $secinfo->which_applicant_is_qualified = $request->input('subClauseQualified');
    //     $secinfo->professional_qualifications = $request->input('pQualification');
    //     $secinfo->educational_qualifications = $request->input('eQualification');
    //     $secinfo->work_experience = $request->input('wExperience');
    //     $secinfo->address_id = $secAddressResidential->id;
    //     $secinfo->business_address_id = $secAddressBusiness->id;
    //     $secinfo->is_unsound_mind = $request->input('isUnsoundMind');
    //     $secinfo->is_insolvent_or_bankrupt = $request->input('isInsolventOrBankrupt');
    //     $secinfo->reason = $request->input('reason1');
    //     $secinfo->is_competent_court = $request->input('isCompetentCourt');
    //     $secinfo->competent_court_type = $request->input('reason2');
    //     $secinfo->status = $this->settings('SECRETARY_PENDING','key')->id;
    //     $secinfo->created_by = $loggedUserId;
    //     if($regUser==false){
    //     $secinfo->people_id = $people->id;   // if applicant is not a roc user... 
    //     }else{
    //     $secinfo->people_id = $peopleId;  // if applicant already roc user... 
    //     }
    //     $secinfo->save();

        
    //     $workHis = $request->input('workHis');
    //     $his = array();
    //     foreach($workHis as $history){
    //         if(!empty($history)){
            
    //         $secWorkHistory = new SecretaryWorkingHistory();
    //         $secWorkHistory->secretary_id =  $secinfo->id;
    //         $secWorkHistory->company_name = $history['companyName'];
    //         $secWorkHistory->position = $history['position'];
    //         $secWorkHistory->from = $history['from'];
    //         $secWorkHistory->to = $history['to'];
    //         $secWorkHistory->save();

    //         }
    //         $his[] = $history['companyName'];
    //     }

    //    // for load data to form1 view to convert as a  pdf...
    //    $id = $secinfo->id;
    //    $fname = $request->input('firstname');
    //    $lanme = $request->input('lastname');
    //    $oname = $request->input('othername');
    //    $fullname = $fname .' '. $lanme .' '. $oname ;
    //    $businessName = $request->input('businessName');
    //    $bAddress1 = $request->input('businessLocalAddress1');
    //    $bAddress2 = $request->input('businessLocalAddress2');
    //    $bCity = $request->input('businessCity');
    //    $bDistrict = $request->input('businessDistrict');
    //    $bProvince = $request->input('businessProvince');
    //    $bAddress = $bAddress1 .' '. $bAddress2 .' '. $bProvince .' '. $bDistrict .' '. $bCity ;
    //    $rAddress1 = $request->input('residentialLocalAddress1');
    //    $rAddress2 = $request->input('residentialLocalAddress2');
    //    $rCity = $request->input('residentialCity');
    //    $rDistrict = $request->input('residentialDistrict');
    //    $rProvince = $request->input('residentialProvince');
    //    $rAddress = $rAddress1 .' '. $rAddress2 .' '. $rProvince .' '. $rDistrict .' '. $rCity ;
    //    $subClauseQualified = $request->input('subClauseQualified');
    //    $pQualification =  $request->input('pQualification');
    //    $eQualification = $request->input('eQualification');
    //    $wExperience = $request->input('wExperience');
    //    $isUnsoundMind = $request->input('isUnsoundMind');
    //    $isInsolventOrBankrupt = $request->input('isInsolventOrBankrupt');
    //    $reason1 = $request->input('reason1');
    //    $isCompetentCourt = $request->input('isCompetentCourt');
    //    $reason2 = $request->input('reason2');
    //    $workHistory = $request->input('workHis');


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
            'status' =>true
        ], 200);



    }
}
