<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParseController extends Controller
{
	protected $removeArr = ['/= div>/','/div>/','/=/','/iv>/','/v>/','/v>/','/<br>/'];
	
	/**
     * Parse the file.
    */
    public function index() 
	{
		$exists = \Storage::disk('public')->exists('sample.txt');
		$tdArr = [];
		if($exists){
			$doc = new \DOMDocument();
			$url = storage_path('app\public\sample.txt');
			@$doc->loadHTMLFile($url);
			$tds = $doc->getElementsByTagName('td');
			$index = 0;
			foreach ($tds as $td) {
				$class = $td->getAttribute('class');
				if(preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($class, ENT_QUOTES)) == "3Dinfo") {
					$index++;
					$childeren = $td->childNodes;
					for($i=0;$i<($childeren->length);$i++){
						$tempArr = [];
						if($childeren->item($i)){
							$content = $childeren->item($i)->textContent."\n <br>"; 
							$col1 = strpos($content,")");
							$col2 = strpos($content,",");
							$col3 = strpos($content,"]");
							$col4 = strpos($content,"$");
							$col5 = strpos($content,",");
						 
							if($col1){
								$tdArr[$index][1] = substr($content,0,++$col1);
							}
							if($col1 && $col3){
								$tdArr[$index][2] = $this->scrubWord(substr($content,$col1,($col3 - $col1)+1));
							}else if($col3){
								$tdArr[$index][2] = $this->scrubWord(substr($content,$col1,($col2 - $col1)));
							}
							
							if($col1 && $col3 && $col4 && $col5){
								$tdArr[$index][3] = $this->scrubWord((trim(substr($content,($col3+1),($col4 - $col3)-1))));
								$tdArr[$index][4] = $this->scrubWord(trim(substr($content,$col4,(strlen($content) - $col4))));
								continue;
							}else if($col4 && $col5 && !isset($tdArr[$index][3])){
								$tdArr[$index][3] = $this->scrubWord(trim(substr($content,0,(strlen($content) - $col4)))); 
							}else if(!$col3 && $col4 === false && $col5){
								$tdArr[$index][3] = $this->scrubWord(trim($content));
								continue;
							}
							
							if($col4 || $col4 !== false){
								$tdArr[$index][4] = $this->scrubWord(substr($content,$col4));
							}
						}
					}
				}
				   
			}			
		}
		return view('index', ['data' => $tdArr]);
	}
	/**
     * To remove the unformatted html tags
	 * return formatted string
     */
	protected function scrubWord($input = ''){
		return trim(preg_replace ($this->removeArr, ' ', $input));
	}
}
