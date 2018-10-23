<?php

namespace App\Http\Controllers\API\v1\Society;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Society;
use App\User;
use App\Address;
use App\SocietyMember;
use App\Setting;

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
                    ->get(['societies.id','societies.name','societies.type_id','societies.created_at','settings.key as status','settings.value as value']);
      
        
       
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


}


