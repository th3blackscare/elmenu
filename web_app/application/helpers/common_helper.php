<?php
// create slug based on title
function slugify($text,$tablename,$fieldname)
{
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);
  // trim
  $text = trim($text, '-');
  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);
  // lowercase
  $text = strtolower($text);
  if (empty($text)) {
    return 'n-a';
  }
  $i = 1; 
  $baseSlug = $text;
  while(slug_exist($text,$tablename,$fieldname))
  {
		$text = $baseSlug . "-" . $i++;        
  }
  return $text;
}
function slug_exist($text,$tablename,$fieldname)
{
  //check slug is uniquee or not.
  $CI =& get_instance();
  $checkSlug = $CI->db->get_where($tablename,array($fieldname=>$text))->num_rows();  
  if($checkSlug > 0)
  {
    return true;
  }
}
function getUserTypeList(){
  $usertype = array(
    'Admin' =>'Admin', 
    'User' =>'User', 
  );
  return $usertype;
}
function generateEmailBody($body,$arrayVal){
  // replace # email body variable's
  if($arrayVal['FirstName']==""){
    $arrayVal['FirstName'] = 'Unknown';
  }  
  $CI =& get_instance();
  if($CI->session->userdata('CompanyName'))
  {
    $body = str_replace("#Company_Name#",$CI->session->userdata('CompanyName'),$body);  
  }
  else
  {
    $body = str_replace("#Company_Name#",$CI->session->userdata('site_title'),$body);  
  }

  $body = str_replace("#firstname#",$arrayVal['FirstName'],$body);  
  $body = str_replace("#lastname#",$arrayVal['LastName'],$body);
  $body = str_replace("#s_firstname#",$arrayVal['SFirstName'],$body);  
  $body = str_replace("#s_lastname#",$arrayVal['SLastName'],$body);
  $body = str_replace("#forgotlink#",$arrayVal['ForgotPasswordLink'],$body);  
  $body = str_replace("#email#",$arrayVal['Email'],$body);  
  $body = str_replace("#password#",$arrayVal['Password'],$body);  
  $body = str_replace("#s_email#",$arrayVal['Sender_Email'],$body);
  $body = str_replace("#s_utype#",$arrayVal['Sender_Utype'],$body);
  $body = str_replace("#Site_Name#",$arrayVal['Site_Name'],$body);
  $body = str_replace("#ip#",$arrayVal['IPAddress'],$body);
  $body = str_replace("#BankofMonths#",$arrayVal['BankOfMonths'],$body);
  $body = str_replace("#loginlink#",$arrayVal['LoginLink'],$body);

  $body = str_replace("#question_name#",$arrayVal['QuestionName'],$body);
  $body = str_replace("#question_link#",$arrayVal['QuestionLink'],$body);
  $body = str_replace("#reviewer_status#",$arrayVal['ReviewerStatus'],$body);
  $body = str_replace("#reviewer_comment#",$arrayVal['ReviewerComment'],$body);
  $body = str_replace("#expiry#",$arrayVal['Expiry'],$body);
  $body = str_replace("#renew_account_link#",$arrayVal['renewAccountLink'],$body);
  return $body;
}
?>