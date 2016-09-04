<?php

namespace splattner\framework;
use Aspsms\Aspsms;


class Helper {



	public static function to_utf8($in)
	{
	        if (is_array($in)) {
	            foreach ($in as $key => $value) {
	                $out[Helper::to_utf8($key)] = Helper::to_utf8($value);
	            }
	        } elseif(is_string($in)) {
	            if(mb_detect_encoding($in) != "UTF-8")
	                return utf8_encode($in);
	            else
	                return $in;
	        } else {
	            return $in;
	        }
	        return $out;
	}
	
	public static function generatePW($length=8) {
		  
		$dummy = array_merge(range('0', '9'), range('a', 'z'), range('A', 'Z'));
		  
		// shuffle array
		  
		mt_srand((double)microtime()*1000000);
		
		for ($i = 1; $i <= (count($dummy)*2); $i++)
		{
			$swap = mt_rand(0,count($dummy)-1);
			$tmp = $dummy[$swap];
			$dummy[$swap] = $dummy[0];
			$dummy[0] = $tmp;
		}
		  
		// get password
		  
		return substr(implode('',$dummy),0,$length);  
	}
	
	public static function sendSMS($originator, $recipient, $content) {

        $config = Application::getConfig();

		try {
			$aspsms = new Aspsms($config["aspsms"]["username"], $config["aspsms"]["password"], array(
				'Originator' => 'myVBC'
			));

			$aspsms->sendTextSms($content, array(
				'0' => $recipient
			));
		} catch (Exception $e) {
			//print_r($e);
		}

		

		
		unset($aspsms);
	}
	
	public static function sendEMail($originator_email, $originator_name, $recipient_email, $recipient_name, $subject, $content) {
		$mail = new PHPMailer();
				
		$mail->IsSMTP();
		$mail->Host       = "localhost"; // sets the SMTP server
		$mail->Port       = 25;                    // set the SMTP port for the GMAIL server

		
		$mail->SetFrom($originator_email, $originator_name);
		$mail->AddAddress($recipient_email,$recipient_name);
		$mail->Subject = $subject;
		$mail->IsHTML(false);
		$mail->Body = $content;
		$mail->Send();

	}
}
?>
