<?

class messages {
  public function text($mid) {
    global $language,$logger;
    $mid=htmlspecialchars($mid);
    if(preg_match('/^[a-zA-Z_0-9]*$/',$mid)) {
      $id=$this->getTranslation("select id from translations where identifier='$mid'");
      if($id) {
        $message=$this->getTranslation("select message_$language from translations where identifier='$mid'");
        return $message;
      } else {
        echo "unknown_msg $mid";
        //$logger->log("Asking unknown message $mid",'hacks') or die('unknown msg');
      }
    } else {
      echo "unallowed_msg";
      //$logger->log("Asking unallowed message $mid",'hacks') or die('unallowed msg');
      return false;
    }
  }
  
  private function getTranslation($query) {
  	global $sqlz;
  	$connection=$sqlz;
  	$connection->query($query);
  	$connection->get_row();
  	Return $connection->get_col();
  }
}

$message = new messages;


class mailer {
  public $message,$result=true,$respond,$vars,$test,$sender;
  public $ContentType="text/plain";
  private $data,$continue=true,$mid;
  public function send($message_id=false) {
    global $sql;
    $this->mid=$message_id;
    $this->data['headers']="From: ".$this->sender."\r\n";
    $this->data['headers'].="Content-Type: ".$this->ContentType."; charset=utf-8\r\n";
    $this->data['headers'].="Content-Transfer-Encoding: base64\n";
    if(!is_numeric($message_id)) {
      $this->makeMessage($message_id) or $this->createError('no_message','error');
    } else {
      $this->makeInfo($message_id) or $this->createError('no_message','error');
    }
    if($this->continue && !$this->test) {
      $this->doSend($this->vars['email']) or $this->createError('send','error');
    } elseif($this->test) {
      $this->result=$this->message=base64_decode($this->data['message']);
    }
  }
  
  private function createError($type,$table,$log=false) {
    global $errors,$logger;
    $this->result=false;
    $this->message=$type;
    //$logger->log("There was an error sending mail: $type",$table,"Message id: ".$this->mid);
    $this->continue=false; 
  }
  
  private function doSend($email) {
    global $message;
    $fn="mail";
    $fn($this->receiver,$this->data['subject'],$this->data['message'],$this->data['headers']."\n") or $err=1;
    //$logger->log("Email sent to $email ($message_id)",'notice');
    if(!$err) {
      $this->result=true;
      if($this->respond) {
        $this->message=$message->text($this->respond);
      } 
      return true; 
    } else { 
      return false; 
    }
  }
  
  private function makeMessage($message_id) {
    global $sql,$language;
    $sql->query("select id,headers,subject_$language,message_$language,modifiers from translation_emails where identify='$message_id'");
    $sql->get_row();
    $id=$sql->get_col();
    if(!$id) {
      return false;
    }
    $this->data['headers'].=$sql->get_col();
    $this->data['subject']='=?UTF-8?B?'.base64_encode($sql->get_col()).'?=';
    $this->data['message']=base64_encode($this->compose($sql->get_col(),$sql->get_col())) or $this->createError('modifier','error');
    return true;
        
  }

  private function makeInfo($message_id) {
    global $sql;
    $language=$this->vars['language'];
    $sql->query("select id,subject_$language,message_$language,modifiers from mail_send where id='$message_id'");
    $sql->get_row();
    $id=$sql->get_col();
    if(!$id) {
      return false;
    }
    $this->data['headers'].="GamePro-Action: Preorder-pending\n\r";
    $this->data['subject']='=?UTF-8?B?'.base64_encode($sql->get_col()).'?=';
    $this->data['message']=base64_encode($this->compose($sql->get_col(),$sql->get_col())) or $this->createError('modifier','error');
    return true;
        
  }
 
  private function compose($text,$modifiers) {
    global $me;
    if($modifiers) {
      $modifiers=json_decode($modifiers,true);
      if(is_array($modifiers)) {
        foreach($modifiers as $k => $v) {
          if($v['datatype']=='sql') {
            $replacer=get_reply(buildSQL($v['data'],$this->vars)) or $this->createError("db_error",'error');
          } elseif($v['datatype']=='vars') {
            $replacer=$this->vars[$v['data']];
          } elseif($v['datatype']=='session') {
            $replacer=$_SESSION[$v['data']];
          } elseif($v['datatype']=='server') {
            $replacer=$_SERVER[$v['data']];
          }
          if($v['fn']) {
            if(is_array($v['fn'])) {
              $replacer=$v['fn']['fn'](($v['fn'][0] ? $v['fn'][0] : $replacer),($v['fn'][1] ? $v['fn'][1] : $replacer));
            } else {
              $replacer=$v['fn']($replacer);
            }
          }
          $text=preg_replace("/\[$k\]/",$replacer,$text);
        }
      } else {
        return false;
      }
    }
    return $text;
  }
}

$mailer = new mailer;

class logger {
  public function log($line,$table,$additional_info=false) {
    global $sql;
    $user_info=$this->createInfo();
    $sql->query("insert into log values('','".time()."','$user_info','$line','$additional_info','$table')");
  }
}

$logger=new logger;