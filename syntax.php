<?php
/**
 
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_cssperpage extends DokuWiki_Syntax_Plugin {


    function getType(){
        return 'substition';
    }
	

    function getPType(){
        return 'block';
    }

 
    function getSort(){
        return 199;
    }


    function connectTo($mode) {    
     $this->Lexer->addSpecialPattern('~~cssp_openDIV~~',$mode,'plugin_cssperpage');
     $this->Lexer->addSpecialPattern('~~cssp_closeDIV~~',$mode,'plugin_cssperpage');
      $this->Lexer->addSpecialPattern('~~cssp_\w+_openDIV~~',$mode,'plugin_cssperpage');
     
    }


    function handle($match, $state, $pos,  Doku_Handler $handler){
      
    //    if(strlen($match) > 16) {
          
           if(preg_match("/~~cssp_(.*?)_openDIV/",$match,$matches)) {  
            $class=$matches[1];
            $match = $class . ';' . 'openDIV';
        }
       else $match = substr($match,7,-2);       
        switch ($state) {   
          case DOKU_LEXER_SPECIAL :
           return array($state, $match);
        }
        return array($state,"");
    }


    function render($mode,  Doku_Renderer $renderer, $data) {
        if($mode == 'xhtml'){
           list($state,$match) = $data;
            switch ($state) {          
              case DOKU_LEXER_SPECIAL :
             
               list($class,$rest) = explode(';',$match);
                if($rest) {                    
                    $match = 'openDIV';                  
                }
                 else {
                     $class ='opencss';                  
                }       
                if($match == 'openDIV') {                  
                  $renderer->doc .= "\n<div id='css_per_page'  class = '" . $class ."'>\n";                    
                }                
                elseif($match == 'closeDIV') {
                   $renderer->doc .= "\n</div>\n";                    
                }  
                               
               return true;
            }
        }
        return false;
    }
}

?>