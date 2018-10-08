<?php

namespace App\Http\Controllers\API\v1\Search\Validation;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Cache;
use App\Rule;
use App\Company;
use App\Setting;

trait ValidationRules
{
	function checkRules($name="",$type="",$company_id=""){
		$data = array();
		$name = trim(strtoupper(preg_replace('/\s+/', ' ',$name)));

		$data[] = $this->_checkNameSuitabilityRule($name); //2
		$data[] = $this->_checkSpecialPermissionNeededWords($name); //3
		$data[] = $this->_checkPhoneticallySameName($name); //4
		$data[] = $this->_checkPronouncedWords($name); //7
		$data[] = $this->_checkCombinationWords($name,$company_id); //8
		$data[] = $this->_checkUnpronounceableWords($name); //9
		$data[] = $this->_checkDigitsTogetherWithWords($name); //10
		$data[] = $this->_checkChamberOfCommerce($name,$type); //11
		$data[] = $this->_checkRestrictedWords($name); //12
		$data[] = $this->_checkGovernmentWords($name); //14
		$data[] = $this->_checkNameWithoutOmitWords($name); //15
		$data[] = $this->_checkCompaniesFromGroup($name); //16
		$data[] = $this->_checkNameIsPartOfAnExistingCompanyName($name,$company_id); //18
		$data[] = $this->_checkPrivateImmediateBracket($name,$type); //19
		return array_values(array_filter($data));
	}

	//2.Name Suitability Rules
	function _checkNameSuitabilityRule($name){
		$data = array('status' => 'success', 'message' => 'Name Suitability Rules.');
		$sWords = $this->_getWords('RULE_TYPE_NAME_SUITABILITY');
		if(!empty($sWords)){
			foreach ($sWords as $key => $value) {
			$pos = strpos($name, $value);
				if ($pos !== false) {
						$data = array('status' => 'fail', 'message' => 'Name suitability rule is violated as the word '.$value.' cannot be used.');
				}
			}
		}
		return $data;
	}

	//3.Words that require special permission prior to use
	function _checkSpecialPermissionNeededWords($name){
		$data = array('status' => 'success', 'message' => 'Words that require special permission prior to use.');
		$sWords = $this->_getWordsNeedSpecialPermission();
		$special_permission_needed_words=$need_permission_from="";
		foreach ($sWords as $key => $value) {
			$pos = strpos($name, $value->word);
			if ($pos !== false) {
				$special_permission_needed_words .= $value->word.', ';
				$need_permission_from .= $value->permission_from.', ';
			}
		}

		$foundWords = rtrim($special_permission_needed_words,', ');

		 if(!empty($foundWords)){
		 	if(count(explode(", ",$foundWords)) > 1){
				 $data = array('status' => 'fail', 'message'=> "Contains words that require special permission prior to use: Words: ".$foundWords." needs permission from ".$need_permission_from);
				} else {
				$data = array('status' => 'fail', 'message'=> "Contains words that require special permission prior to use: Word: ".$foundWords." needs permission from ".$need_permission_from);
				}
		 }
		return $data;
	}

	//4.Phonetically name should be Tested
	function _checkPhoneticallySameName($name){
		$data = array('status' => 'success', 'message' => 'Phonetically similar names');
		$com = Company::whereRaw("soundex(name) = soundex('".$name."')")->count();
		if($com > 0){
			$data = array('status' => 'fail', 'message' => 'Phonetically similar name found');
		}
		// SELECT * FROM eroc_companies WHERE soundex(name) = soundex('LAKSHILAA');
		// select count(*) as aggregate from "eroc_companies" where soundex(name) = soundex("BANK")
		// echo $com;
		return $data;
	}

	//6.Words with similar meaning
	function _checkWordsWithSimilarMeaning($name,$type){
		$data = array();
		return $data;
	}

	//7.Using letters for names if it can be pronounced should use full stops to separate letters
	function _checkPronouncedWords($name){
		$data = array('status' => 'success', 'message' => 'Using letters for names if it can be pronounced should use full stops to separate letters');
		$is_char_array = explode(" ",$name);
		foreach($is_char_array as $is_char){
			if(strlen($is_char) != 1){
				return $data;
			}
		}
		$wordWithoutSpace = str_replace(' ','',$name);
		$totalSpace = substr_count($name, ' ');
		$totalCharectersWithoutSpace = strlen($wordWithoutSpace);
	//	echo $wordWithoutSpace;
	//	echo strtolower($wordWithoutSpace);
		$is_dic = $this->isDictionaryWord($wordWithoutSpace);
		
		if(($totalCharectersWithoutSpace - $totalSpace) == 1){
			$is_pronounceable = $this->_checkPronounceability($wordWithoutSpace);
			// echo $is_pronounceable;
			if($is_dic == true || $is_pronounceable > 0.5){
				$data = array('status' => 'fail', 'message' => 'Pronounceable word '.$wordWithoutSpace.' found. Use full stops to separate the letters');
			}
		}
		return $data;
	}

	//8. If name Asa Enterprises (Pvt) Ltd is incorporated the name A S A Enterprises (Pvt) Ltd ot A.S.A. Enterprises Not allowed
	function _checkCombinationWords($name,$company_id){
		$data = array('status' => 'success', 'message' => 'Example: Asa Enterprises (Pvt) Ltd is incorporated the name A S A Enterprises (Pvt) Ltd ot A.S.A. Enterprises Not allowed');
		$wordArray = explode(" ", $name);
		$wordCombos = array_unique($this->_wordcombos($wordArray));
		//print_r($wordCombos);exit;
		if(count($wordCombos) > 1){
			$query = Company::whereIn('name', $wordCombos);
			if(!empty($company_id)){
				$query = $query->where('id','!=',$company_id);
			}
			$is_exist = $query->count();

			if($is_exist > 0){
				$data = array('status' => 'fail', 'message' => 'Example: Asa Enterprises (Pvt) Ltd is incorporated the name A S A Enterprises (Pvt) Ltd ot A.S.A. Enterprises Not allowed');
			}
		}
		return $data;
	}
	//9.If a name contain unpronounceable word such as PQR it should be either use as P Q R or P.Q.R. 
	function _checkUnpronounceableWords($name){

		$data = array('status' => 'success', 'message' => 'If a name contain unpronounceable word such as PQR it should be either use as P Q R or P.Q.R.');
		$is_char_array = explode(" ",$name);
		if(!empty($is_char_array)){
		foreach($is_char_array as $is_char){
			if(strlen($is_char) == 1){
				return $data;
			}
		}
		}
		$is_char_array_dots = explode(".",$name);
		if(!empty($is_char_array_dots)){
		foreach($is_char_array_dots as $is_char){
			if(strlen($is_char) == 1){
				return $data;
			}
		}
		}
		$wordWithoutSpace = str_replace(' ','',$name);
		$wordWithoutDots = str_replace('.','',$name);
		//$totalCharecters = strlen($name);
		$totalSpace = substr_count($name, ' ');
		$totalDots = substr_count($name, '.');
		$totalCharectersWithoutSpace = strlen($wordWithoutSpace);
		$totalCharectersWithoutDots = strlen($wordWithoutDots);
		// echo $totalCharectersWithoutSpace;echo "<br>";
		// echo $totalDots;
		//$totalCharectersWithoutSpace = strlen($wordWithoutSpace);
		$is_readable_array = explode(" ",$name);
		
		foreach($is_readable_array as $is_readable){
			$is_dic = $this->isDictionaryWord($is_readable);
			if($is_dic){
				return $data;
			}
		}
		//echo $name;
	//	print_r("".$data);exit;
		if((($totalCharectersWithoutSpace - $totalSpace) != 1) && (($totalCharectersWithoutDots - $totalDots) != 1) && $is_dic == false){
			$is_pronounceable = $this->_checkPronounceability($wordWithoutSpace);
			//echo $is_pronounceable;
			if($is_pronounceable < 0.6){
				$data = array('status' => 'fail', 'message' => 'If a name contain unpronounceable word such as PQR it should be either use as P Q R or P.Q.R.');
			}
		}
		return $data;
	}

	//10.When Using digits together with words or letters space should be kept around the digit
	function _checkDigitsTogetherWithWords($name){
		$data = array('status' => 'success', 'message' => 'Digit with name validation');
		$error = array();
		$array = str_split($name);
		foreach ($array as $key => $value) {
			if (is_numeric($value)) {
				if(!empty($array[$key-1])){
			
				if($array[$key-1] != " "){
						$error[] += 1;
					}
				}
				if(!empty($array[$key+1])){
					if($array[$key+1] != " "){
						$error[] += 1;
					}
				}
			}
		}

		if(count($error) > 0){
			$data = array('status' => 'fail', 'message' => 'Using the digits together with words or letter space should be kept around the digit');
		}
		return $data;
	}

	//11."Chamber of Commerce" is allowed only for association (Section  34)
	function _checkChamberOfCommerce($name,$type){
		$data = array('status' => 'success', 'message' => '"Chamber of Commerce" is allowed only for association (Section  34)');
		//to be change	
			$pos = strpos($name, strtoupper('Chamber of Commerce'));
			if ($pos !== false && !empty($this->settings($type,'id')->key) && $this->settings($type,'id')->key != 'COMPANY_TYPE_GUARANTEE_34') {
				$data = array('status' => 'fail', 'message' => '"Chamber of Commerce" is allowed only for association (Section  34)');
			}
		return $data;
	}

	//12.Restricted Word
	function _checkRestrictedWords($name){
		$rwords = $this->_getWords('RULE_TYPE_RESTRICTED');
		$data = array('status' => 'success', 'message' => 'Restricted Word');
		$restricted_words="";

		foreach ($rwords as $key => $value) {
			$pos = strpos($name, $value);
			if ($pos !== false) {
				$restricted_words .= $value.', ';
			}
		}

		$foundWords = rtrim($restricted_words,', ');

		 if(!empty($foundWords)){
		 	if(count(explode(", ",$foundWords)) > 1){
					$data = array('status' => 'fail', 'message' => "Restricted words found: Words: ".$foundWords." is restricted because Words are not allowed to use");
				} else {
					$data = array('status' => 'fail', 'message' => "Restricted word found: Word: ".$foundWords." is restricted because Word is not allowed to use");
				}
		 }
				
		return $data;
	}

	//13.Name’s of  Government Institution or part of the name
	function _checkPronouncedWords5($name,$type){
		$data = array();
		return $data;
	}

	//14.Government own business or words that use by Government to represent programs such as
	function _checkGovernmentWords($name){
		$data = array('status' => 'success', 'message' => 'Government own business or words  that use by Government to represent programs.');
		$gov_words = $this->_getWords('RULE_TYPE_GOV');
		foreach ($gov_words as $key => $value) {
			$pos = strpos($name, $value);
			if ($pos !== false) {
				$data = array('status' => 'fail', 'message' => 'Government own business or words  that use by Government to represent programs.');
			}
		}
		return $data;
	}

	//15.In determining for the company names the following words are not considered whether one name is differs from other
	function _checkNameWithoutOmitWords($name){
		$data = array('status' => 'success', 'message' => 'In determining for the company names the following words are not considered whether one name is differs from other');
		$name = $this->_removeSpecialCharector($name);

		$omit_words = $this->_getWords('RULE_TYPE_OMIT_WORDS');
		$ow =array();
		$omit =array();
		foreach($omit_words as $omit_word){
			$ow = explode(" ", $omit_word);
			foreach ($ow as $value) {
				$omit[] = $value;
			}
		}

		$omit_words = $omit;

	$currentName = preg_replace('/\b('.implode('|',$omit_words).')\b/','',$name);

	if($name != $currentName){
		$currentName = preg_replace('/\s+/', ' ',trim($currentName));
		$statusIds = $this->_exceptStatus();
		$is_exist = Company::where('name','ILIKE','%'.$currentName.'%')->whereNotIn('status', $statusIds)->count();
		if($is_exist > 0){
			$data = array('status' => 'fail', 'message' => 'In determining for the company names the following words ('.implode(", ",$omit_words).') are not considered whether one name is differs from other');
		}
	}
		return $data;
	}

	//16.Names of Companies that from a Group
	function _checkCompaniesFromGroup($name){
		$data = array('status' => 'success', 'message' => 'Names of Companies that from a Group');
		$groups = $this->_getWords('RULE_TYPE_GROUPS');
		foreach ($groups as $key => $value) {
			$pos = strpos($name, $value);
			if ($pos !== false) {
				$data = array('status' => 'fail', 'message' => 'Names of Companies that from a Group');
			}
		}
		return $data;
	}

	//17.If name contain the word group check for the company names that start with the first part of the name
	function _checkPronouncedWords9($name,$type){
		$data = array();
		return $data;
	}

	//18. If proposed name have a part of an existing Company name, another word should be added
	function _checkNameIsPartOfAnExistingCompanyName($name,$company_id){
		$data = array('status' => 'success', 'message' => 'If proposed name have a part of an existing Company name, another word should be added');
		
		$statusIds = $this->_exceptStatus();
		
		$query = Company::where('name','ILIKE','%'.$name.'%')
		->whereNotIn('status', $statusIds);
		if(!empty($company_id)){
				$query = $query->where('id','!=',$company_id);
		}
		$is_exist = $query->count();
		if($is_exist > 0){
			$data = array('status' => 'fail', 'message' => 'If proposed name have a part of an existing Company name, another word should be added');
		}
		return $data;
	}

	//19.For Private companies the word Private should be bracketed and immediately before that bracket another bracket cannot be used
	function _checkPrivateImmediateBracket($name,$type){
		$data = array('status' => 'success', 'message' => 'For Private companies the word Private should be bracketed and immediately before that bracket another bracket cannot be used.');
		if(!empty($type) && !empty($this->settings($type,'id')->key) && $this->settings($type,'id')->key == 'COMPANY_TYPE_PRIVATE'){
			$nameArray = explode(" ", $name);
			$lastWord = end($nameArray);
			if (preg_match('/[\[\]()]/', $lastWord))
			{
				$data = array('status' => 'fail', 'message' => 'For Private companies the word Private should be bracketed and immediately before that bracket another bracket cannot be used.');
			}
		}
		return $data;
	}


//Sub functions

	function _getWords($type){
		//WANT TO REMOVE
		Cache::forget($type);

		$words = Cache::remember($type, 24*60, function() use ($type) {
			$data = array();
			$rules = Rule::leftJoin('settings','rules.type_id','=','settings.id')
			->leftJoin('setting_types','settings.setting_type_id','=','setting_types.id')
			->where('setting_types.key','RULES_TYPES')
			->where('settings.key', $type)
			->select('rules.id','rules.word')
			->get();

			if(count($rules) > 1){
				foreach ($rules as $rule) {
					$data[$rule->id] = strtoupper($rule->word);
				}
			}
		//		print_r($data);
			return $data;
		});

		return $words;
	}


	function _getWordsNeedSpecialPermission(){

		$restricted_words = Cache::remember('special_permission_needed_words', 24*60, function() {
			$data = array();
			$rules = Rule::leftJoin('settings','rules.type_id','=','settings.id')
			->leftJoin('setting_types','settings.setting_type_id','=','setting_types.id')
			->where('setting_types.key','RULES_TYPES')
			->where('settings.key','RULE_TYPE_SPECIAL_PERMISION')
			->select('rules.id','rules.word','rules.permission_from')
			->get();

			if(count($rules) > 1){
				foreach ($rules as $rule) {
					$data[$rule->id] = (object) array('word' => strtoupper($rule->word),'permission_from' => $rule->permission_from);
				}
			}
			return $data;
		});

		return $restricted_words;
	}

	function _checkPronounceability($word) {
    static $vowels = array
        (
        'a',
        'e',
        'i',
        'o',
        'u',
        'y'
        );

    static $composites = array
        (
        'mm',
        'll',
        'th',
        'ing'
        );

    if (!is_string($word)) return false;

    // Remove non letters and put in lowercase
    $word = preg_replace('/[^a-z]/i', '', $word);
    $word = strtoupper($word);

    // Special case
    if ($word == 'a') return 1;

    $len = strlen($word);

    // Let's not parse an empty string
    if ($len == 0) return 0;

    $score = 0;
    $pos = 0;

    while ($pos < $len) {
        // Check if is allowed composites
        foreach ($composites as $comp) {
            $complen = strlen($comp);

            if (($pos + $complen) < $len) {
                $check = substr($word, $pos, $complen);

                if ($check == $comp) {
                    $score += $complen;
                    $pos += $complen;
                    continue 2;
                }
            }
        }

        // Is it a vowel? If so, check if previous wasn't a vowel too.
        if (in_array($word[$pos], $vowels)) {
            if (($pos - 1) >= 0 && !in_array($word[$pos - 1], $vowels)) {
                $score += 1;
                $pos += 1;
                continue;
            }
        } else { // Not a vowel, check if next one is, or if is end of word
            if (($pos + 1) < $len && in_array($word[$pos + 1], $vowels)) {
                $score += 2;
                $pos += 2;
                continue;
            } elseif (($pos + 1) == $len) {
                $score += 1;
                break;
            }
        }

        $pos += 1;
    }

    return $score / $len;
}

	function isDictionaryWord($searchthis){
		$matches = array();
		$handle = fopen(asset('words.txt'), "r");

		if ($handle)
		{
			while (!feof($handle))
			{
				$buffer = fgets($handle);
				if(strpos(strtolower($buffer), strtolower($searchthis)) !== FALSE)
					$matches[] = $buffer;
					//exit;
			}
			fclose($handle);
			//print_r($matches);
		}
		return !empty($matches) ? true : false; 
	}

function _wordcombos ($words) {
        if ( count($words) <= 1 ) {
            $result = $words;
        } else {
            $result = array();
            for ( $i = 0; $i < count($words); ++$i ) {
                $firstword = $words[$i];
                $remainingwords = array();
                for ( $j = 0; $j < count($words); ++$j ) {
                    if ( $i <> $j ) $remainingwords[] = $words[$j];
                }
                $combos = $this->_wordcombos($remainingwords);
                for ( $j = 0; $j < count($combos); ++$j ) {
                    $result[] = $firstword . ' ' . $combos[$j];
                }
            }
        }
        return $result;
	}
	
	function _removeSpecialCharector($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	return str_replace('-', ' ', $string);
}

	function _exceptStatus(){
		$statusId[] = $this->settings('COMPANY_STATUS_REJECTED','key')->id;
		$statusId[] = $this->settings('COMPANY_NAME_REJECTED','key')->id;
	    $statusId[] = $this->settings('COMPANY_NAME_EXPIRED','key')->id;
	//	$statusId[] = $this->settings('COMPANY_NAME_PENDING','key')->id;
		//$statusId[] = $this->settings('COMPANY_NAME_RULES_VERIFICATION','key')->id;
		//$statusId[] = $this->settings('COMPANY_NAME_DOC_VERIFICATION','key')->id;
		return $statusId;
	}

	function statusColorCode($status){
		switch($status){
			case 'COMPANY_NAME_PENDING':
				$color = '#FF5722';
			break;
			case 'COMPANY_NAME_RECOMMEND_FOR_APPROVAL':
			$color = '#03A9F4';
			break;
			case 'COMPANY_NAME_NOT_RECOMMEND_FOR_APPROVAL':
			$color = '#E91E63';
			break;
			case 'COMPANY_NAME_REQUEST_TO_RESUBMIT':
			$color = '#3F51B5';
			break;
			case 'COMPANY_NAME_RESUBMITTED':
			$color = '#FF5722';
			break;
			case 'COMPANY_NAME_APPROVED':
			$color = '#4CAF50';
			break;
			case 'COMPANY_NAME_REJECTED':
			$color = '#F44336';
			break;

			case 'DOCUMENT_PENDING':
			$color = '#FF5722';
			break;
			case 'DOCUMENT_APPROVED':
			$color = '#4CAF50';
			break;
			case 'DOCUMENT_REQUEST_TO_RESUBMIT':
			$color = '#F44336';
			break;
			case 'DOCUMENT_REQUESTED':
			$color = '#FF5722';
			break;
			default:
			$color = '#9E9E9E';
			break;
		}

		return $color;
	}


}
