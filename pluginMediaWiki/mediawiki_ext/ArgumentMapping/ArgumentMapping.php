<?php


$nombreExtension="ArgumentMapping";

$wgExtensionCredits['validextensionclass'][] = array(
	'name' => $nombreExtension,
	'author' =>'Jorge Gonzalez', 
	'url' => 'http://v.joorge.com/mapaArgumentativo/', 
	'description' => 'Mapas argumentativos para mediawiki'
	);


$wgHooks['ParserFirstCallInit'][] = 'efArgumentMappingParserInit';
$wgHooks['EditPage::showEditForm:initial'][] = 'efPreprocesarEditor';
$wgHooks['AlternateEdit'][] = 'efArgumentMappingEditor';
 
// Hook our callback function into the parser
function efArgumentMappingParserInit( &$parser ) {
        // When the parser sees the <sample> tag, it executes 
        // the efSampleRender function (see below)
        $parser->setHook( 'armap', 'efArgumentMappingRender' );
        return true;
}
 
// Execute 
#function efArgumentMappingRender( $input, array $args, Parser $parser, PPFrame $frame ) {
function efArgumentMappingRender( $input, $args, $parser/*, $frame*/ ) {

        // Nothing exciting here, just escape the user-provided
        // input and throw it back out again
        return "<pre>".htmlspecialchars( $input )."</pre><br/>*Nota: Toca mejorar esta forma de ver la información";
}

function efPreprocesarEditor($ep){

	global $wgRequest;
	
	$html=str_replace("\n", " ", $ep->mArticle->mContent);

	//var_dump($html);
	//$html="<b>bold text</b><a href=howdy.html>click me</a>";
	$t="";
	//$t= var_export($html, true);
	
	$html=str_replace("\n", " ", $html);
	
	preg_match_all("/(<([\w]+)[^>]*>)(.*?)(<\/\\2>)/", $html, $matches, PREG_SET_ORDER);
	//echo "---------------------------------------\n\n";
	
	if (strcmp($wgRequest->getVal("earmap"), "")!=0){
	//if (isset($wgRequest->data["earmap"])){
		$anterior=$matches[$wgRequest->getVal("id")][0];
		$nuevo="<armap>".utf8_decode($wgRequest->getVal("earmap"))."</armap>";
		//$t.= "<pre>\nOriginal: \n\n".$html."\n--------------------------";
		//$t.= "\nAnterior:\n\n".$anterior."\n--------------------------";
		//$t.= "\nNuevo:\n\n".$nuevo."\n--------------------------</pre>";
		$t.="<b>Por favor guarde la pagina</b><br/>";
		$ep->editFormPageTop.=$t;
		
		$html = str_replace($anterior, $nuevo, $html);
		$ep->textbox1 = $html;
		
	}
	
	
	
	//$t.= $ep->mArticle->mContent;
	//$t.= "\n-------------------\n";
	$c=0;
	foreach ($matches as $val) {
	/*
		$t.= "matched: " . $val[0] . "\n";
		$t.= "part 1: " . $val[1] . "\n";
		$t.= "part 2: " . $val[2] . "\n";
		$t.= "part 3: " . $val[3] . "\n";
		$t.= "part 4: " . $val[4] . "\n\n";
	*/
		if (strcmp("armap", $val[2])==0){
			$ep->editFormPageTop .= 
						"<a href='?title=".$ep->mArticle->mTitle->mUrlform.
						"&action=edit".
						"&armap=".$c."".
						"'>".
						"Editar Mapa argumentativo ".($c+1)."</a> :<pre>".htmlspecialchars($val[0])."</pre>";
			$c++;
		}
	}
	
	
	
	
	
	
	return false;
}

function codificar($a){
	//return str_replace("=", "_", 
	return utf8_encode($a);
	//);
}

function efArgumentMappingEditor($ep){
	global $wgRequest;
	global $wgArticle;
	global $wgOut;
	global $wgScriptPath;
	global $nombreExtension;

	$t  = "";


	if (strcmp($wgRequest->getVal("armap"), "")!=0){
		
			$html = $wgArticle->getContent();
			$html=str_replace("\n", " ", $html);
			
			preg_match_all("/(<([\w]+)[^>]*>)(.*?)(<\/\\2>)/", $html, $matches, PREG_SET_ORDER);
			$val = $matches[$wgRequest->getVal("armap")];
			$t.="<iframe style='width: 100%; height: 1000px;' ".
				"src='".$wgScriptPath."/extensions/".$nombreExtension."/editor.php?".
				"s=".codificar($val[0])."&".
				"d=".base64_encode($ep->mTitle->escapeLocalURL("action=edit&id=".$wgRequest->getVal("armap")."&earmap"))."'>".
				"</iframe>";
			$wgOut->addHTML($t);
			return false;
	}
	
	
	return true;
}
