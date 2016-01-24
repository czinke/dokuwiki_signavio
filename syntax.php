<?php
/**
 * DokuWiki Plugin signavio (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Christian Zinke <zinke@informatik.uni-leipzig.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

require_once DOKU_PLUGIN . 'syntax.php';

class syntax_plugin_signavio extends DokuWiki_Syntax_Plugin
{

    public function getType()
    {
        return 'substition';
    }

    /**
    public function getPType() {
    return 'block';
    }
     **/
    public function getSort()
    {
        return 999;
    }

    function connectTo($mode)
    {
        $this->Lexer->addEntryPattern('<signavio[\s]*?.*?>(?=.*?</signavio>)', $mode, 'plugin_signavio');
    }

    function postConnect()
    {
        $this->Lexer->addExitPattern('</signavio>', 'plugin_signavio');
    }

    function handle($match, $state, $pos, Doku_Handler $handler){


            switch ($state) {
              case DOKU_LEXER_ENTER :
                break;
              case DOKU_LEXER_MATCHED :
                break;
              case DOKU_LEXER_UNMATCHED :


                $span = '<script type="text/javascript"
                                 src="http://academic.signavio.com/mashup/signavio.js"></script>
                                 <script type="text/plain">
                                 {';
                /**
                 * <h3 class="signavio-title"><a href="http://www.signavio.com">Tourbus besorgen (Kopie)</a></h3>
                 <script type="text/javascript"
                 src="http://academic.signavio.com/mashup/signavio.js"></script>
                 <script type="text/plain">
                 {
                      url:
                 "http://academic.signavio.com/p/model/9d803e2368f54263a1bd0df62038dc89",
                      authToken:
                 "ee2293c5d156849ae326a8ddcf704977d0c5673c1181ecabb16fd2af57f893_c3748750f5599852abd3a2aa63fb9bdbc66bd43d01e6616bb326ca01db1f2_195063cdda70e7d8e46c4c23c5496cb188a52acefd013e1395991eac58c329a",
                      overflowX: "fit",
                      overflowY: "fit",
                      zoomSlider: true,
                      linkSubProcesses: false
                 }

                 <a class="signavio-logo" href="http://www.signavio.com"
                 rel="external"><img
                 src="http://academic.signavio.com/mashup/img/signavio.png" alt="BPM Personalbedarf jBPM" /></a>
                </script>
                 */
    			if(trim($match) != ""){
    				$begin = $span;

    				$end = '
                    }
    				<a class="signavio-logo" href="http://www.signavio.com"
    				                 rel="external"><img
    				                 src="http://academic.signavio.com/mashup/img/signavio.png" alt="BPM Personalbedarf jBPM" /></a>
    				</script>';

                    $sigData = explode(':',$match);

                    if(empty($sigData[1])){
                        return array($state, '<span>Parameter do not match, expacted someURLId:SomeAuthTokenId</span>');
                    }


    				return array($state, array($begin, $sigData[0], $sigData[1], $end));
    			}
    			break;
              case DOKU_LEXER_EXIT :
                break;
              case DOKU_LEXER_SPECIAL :
                break;
            }
            return array();
        }

      /*
        * @public
        * @see handle()
        */
        function render($mode, Doku_Renderer $renderer, $data) {
            if($mode == 'xhtml'){
                if($data[0] == DOKU_LEXER_UNMATCHED){

                  if(!is_array($data[1])){
                      $renderer->doc .= $data[1];
                      return true;
                  }
                  $text = $data[1][0];
                   $text .= '
                    url:
                 "http://academic.signavio.com/p/model/'.$data[1][1].'",
                      authToken:
                 "'.$data[1][2].'",
                      overflowX: "fit",
                      overflowY: "fit",
                      zoomSlider: true,
                      linkSubProcesses: false
                      ';
                  $text .= $data[1][3];

                  $renderer->doc .= $text;   // ptype = 'normal'
                  return true;
                }
            }
            return false;
        }


}

// vim:ts=4:sw=4:et:
