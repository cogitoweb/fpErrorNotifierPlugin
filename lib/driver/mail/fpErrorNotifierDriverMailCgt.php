<?php

require_once 'fpBaseErrorNotifierDriverMail.php';

/** 
 *
 * @package    fpErrorNotifier
 * @subpackage driver 
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifierDriverMailCgt extends fpBaseErrorNotifierDriverMail
{
  /**
   * (non-PHPdoc)
   * @see plugins/fpErrorNotifierPlugin/lib/driver/fpBaseErrorNotifierDriver#notify($message)
   */
  public function notify(fpBaseErrorNotifierMessage $message)
  {
      if(sfConfig::get('sf_environment') !== "prod") {
          return;
      }
      
      try {
          dbSupport::getReadConnection();
      } catch (Exception $ex) {
          return;
      }
      

      $mail = mailTools::performCreateForDb(
              $this->getHost() . $message->subject(),
              $this->getOption('from'),
              $this->getOption('to'),
              $this->getOption('cc'),
              $this->getOption('bcc'),
              (string) $message,
              strip_tags((string) $message)
      );
  }
  
  /**
  * Restituisce host da app.yml o desunto dal server
  *
  * return string
  */
  protected function getHost() {
  
    $host = (sfConfig::get('app_base_url')) ? sfConfig::get('app_base_url') : $_SERVER['HTTP_HOST'];
      if(!$host) $host = 'UNKNOWN';
      if(strpos($host, '://') !== false)
      {
        $host = substr($host, strpos($host, '://') + 3); 
      }
    
    $host = "[" . $host . "] ";
  
    return $host;
  }
  
}