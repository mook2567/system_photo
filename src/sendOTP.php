<?php
session_start();
require 'popup.php';
require_once './config/db.php';
// ตรวจสอบว่ามีอีเมลนี้อยู่ในระบบหรือไม่
$email = $_POST['email'];
$sql1 = "SELECT * FROM 'admin' WHERE admin_email = '$email'";
$result = $conn->query($sql1);
$num = $result->rowCount();

if ($num > 0) {

  // ฟังก์ชันสร้างรหัส OTP แบบสุ่ม
  function generateRandomPassword($length = 6)
  {
    $characters = '0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $password .= $characters[$index];
    }
    return $password;
  }
  // สุ่มรหัสผ่านใหม่
  $newPassword = generateRandomPassword(); // สร้างฟังก์ชันหรือใช้วิธีการสุ่มรหัสผ่านของคุณเอง


  // อัปเดตรหัสผ่านใหม่ในฐานข้อมูล
  $sql2 = "UPDATE account SET otp = '$newPassword' WHERE email = '$email'";
  $result2 = $conn->query($sql2);
  $num2 = $result2->rowCount();
?>
  <div class="center_screen"><?php echo $email; ?></div>
  <?php
  ?>

  <script>
    setTimeout(function() {
      Swal.fire({
        title: 'ระบบได้ทำการส่งรหัสOTPไปยัง Gmail ของคุณแล้ว',
        text: "นำรหัส OTP มากรอกในฟอร์มหน้าถัดไป",
        icon: 'success',
        confirmButtonText: 'ถัดไป',
        allowOutsideClick: false, // ไม่อนุญาตให้คลิกนอก popup ปิด
        allowEscapeKey: false, // ไม่อนุญาตให้กดปุ่ม ESC เพื่อปิด
        allowEnterKey: false // ไม่อนุญาตให้กดปุ่ม Enter เพื่อปิด
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "OTP.php?email=<?php echo $email ?>";
        }
      });
    });
  </script>
  <?php
  $mess = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
                <title>
                </title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <meta name="viewport" content="width=device-width">
                <style type="text/css">body, html {
                  margin: 0px;
                  padding: 0px;
                  -webkit-font-smoothing: antialiased;
                  text-size-adjust: none;
                  width: 100% !important;
                }
                  table td, table {
                  }
                  #outlook a {
                    padding: 0px;
                  }
                  .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
                    line-height: 100%;
                  }
                  .ExternalClass {
                    width: 100%;
                  }
                  @media only screen and (max-width: 480px) {
                     table tr td table.edsocialfollowcontainer {width: auto !important;} table, table tr td, table td {
                      width: 100% !important;
                    }
                    img {
                      width: inherit;
                    }
                    .layer_2 {
                      max-width: 100% !important;
                    }
                    .edsocialfollowcontainer table {
                      max-width: 25% !important;
                    }
                    .edsocialfollowcontainer table td {
                      padding: 10px !important;
                    }
                  }
                </style>
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
                <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
              </head><body style="padding:0; margin: 0;background: #efefef">
                <table style="height: 100%; width: 100%; background-color: #efefef;" align="center">
                  <tbody>
                    <tr>
                      <td valign="top" id="dbody" data-version="2.31" style="width: 100%; height: 100%; padding-top: 30px; padding-bottom: 30px; background-color: #efefef;">
                        <!--[if (gte mso 9)|(IE)]><table align="center" style="max-width:600px" width="600" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                        <table class="layer_1" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width: 600px; box-sizing: border-box; width: 100%; margin: 0px auto;">
                          <tbody>
                            <tr>
                              <td class="drow" valign="top" align="center" style="background-color: #efefef; box-sizing: border-box; font-size: 0px; text-align: center;">
                                <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                                <div class="layer_2" style="max-width: 300px; display: inline-block; vertical-align: top; width: 100%;">
                                  <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                                    <tbody>
                                      <tr>
                                        <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                                          <p style="text-align: left; font-size: 9px; margin: 0px; padding: 0px;">ระบบบันทึกเข้าเวรบุคลากร โรงเรียนม่วงมิตรวิทยาคม</p>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <!--[if (gte mso 9)|(IE)]></td><td valign="top"><![endif]-->
                                <div class="layer_2" style="max-width: 300px; display: inline-block; vertical-align: top; width: 100%;">
                                  <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse; width: 100%; background-color: #efefef;">
                                    <tbody>
                                      <tr>
                                        <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                                          <p style="text-align: right; font-size: 9px; margin: 0px; padding: 0px;"><a href="https://pars.pcnone.com" target="_blank" style="color: #3498db; text-decoration: none;">pars.pcnone.com</a></p>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                            <tr><td class="drow" valign="top" align="center" style="background-color: #efefef; box-sizing: border-box; font-size: 0px; text-align: center;"><!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]--><div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;"><table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%"><tbody><tr><td valign="top" class="edimg" style="padding: 20px; box-sizing: border-box; text-align: center;"><img src="https://api.smtprelay.co/userfile/dacf94aa-67b4-4e02-888f-39992a4d645d/favicon.ico" alt="Obraz" style="border-width: 0px; border-style: none; max-width: 102px; width: 100%;" width="102"></td></tr></tbody></table></div><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr>
                            <tr>
                              <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                                <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                                <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                                  <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                                    <tbody>
                                      <tr>
                                        <td valign="top" class="emptycell" style="padding: 10px;">
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                            <tr>
                              <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                                <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                                <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                                  <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                                    <tbody>
                                      <tr>
                                        <td valign="top" class="edimg" style="padding: 0px; box-sizing: border-box; text-align: center;">
                                          <img src="https://api.elasticemail.com/userfile/a18de9fc-4724-42f2-b203-4992ceddc1de/geometric_divider1.png" alt="Image" width="576" style="border-width: 0px; border-style: none; max-width: 576px; width: 100%;">
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                            <tr>
                              <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                                <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                                <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                                  <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                                    <tbody>
                                      <tr>
                                        <td valign="top" class="emptycell" style="padding: 20px;">
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                              </td>
                            </tr><tr><td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;"><!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]--><div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                                  <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                                    <tbody>
                                      <tr>
                                        <td valign="top" class="edtext" style="padding: 14px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;"><p class="text-center" style="text-align: center; margin: 0px; padding: 0px;"><span style="font-size: 24px;">สวัสดี </span></p></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><tr><td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;"><!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]--><div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;"><table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%"><tbody><tr><td valign="top" class="edtext" style="padding: 18px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;"><p class="text-center" style="text-align: center; margin: 0px; padding: 0px;"><span style="font-size: 18px;">รหัส OTP ขอตั้งรหัสผ่านใหม่ คือ</span></p></td></tr></tbody></table></div><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr>
                            <tr>
                              <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                                <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                                <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                                  <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                                    <tbody>
                                      <tr>
                                        <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                                          <p class="style1 text-center" style="text-align: center; margin: 0px; padding: 0px; color: #f24656; font-size: 36px; font-family: Helvetica, Arial, sans-serif;"><?php echo $newPassword; ?></p>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                              </td>
                            </tr><tr><td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;"><!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]--><div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;"><table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%"><tbody><tr><td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;"><p class="text-center" style="text-align: center; margin: 0px; padding: 0px;">โปรดนำรหัส OTP ของท่านไปกรอกเพื่อตั้งรหัสผ่านใหม่ต่อไป ...</p></td></tr></tbody></table></div><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr>
                            
                            
                            
                            <tr>
                              <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                                <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                                <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                                  <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                                    <tbody>
                                      <tr>
                                        <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                                          <p style="margin: 0px; padding: 0px;"><font><font>มีคำถามหรือข้อสงสัยติดต่อเรา: <a href="mailto://pratchayapol2543@gmail.com" style="color: #3498db; text-decoration: none;">pratchayapol2543@gmail.com</a></font></font></p>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                            <tr>
                            <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                            <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                            <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                              <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                                <tbody>
                                  <tr>
                                    <td valign="top" class="emptycell" style="padding: 20px;">
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                          </td>
                        </tr>
                        
                        <tr>
                          <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                            <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                            <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                              <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                                <tbody>
                                  <tr>
                                    <td valign="top" class="edimg" style="padding: 0px; box-sizing: border-box; text-align: center;">
                                      <img src="https://api.elasticemail.com/userfile/a18de9fc-4724-42f2-b203-4992ceddc1de/geometric_footer1.png" alt="Image" width="587" style="border-width: 0px; border-style: none; max-width: 587px; width: 100%;">
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                          </td>
                        </tr>
                        
                      </tbody>
                    </table>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>
          </body></html>';

  $mess = str_replace('<?php echo $newPassword; ?>', $newPassword, $mess);


  $url = 'https://api.elasticemail.com/v2/email/send';

  try {
    $post = array(
      'from' => 'pars@pcnone.com',
      'fromName' => 'PARS',
      'apikey' => '6B4503919008BFCD6A9CFFF5169A1114196B0C7BC2F85079843C270CA1E895918316BCD8C894528CD315F2841C320216',
      'subject' => 'OTP PARS',
      'to' => "$email",
      'bodyHtml' => $mess,
      'isTransactional' => false
    );

    $ch = curl_init();
    curl_setopt_array($ch, array(
      CURLOPT_URL => $url,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => $post,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER => false,
      CURLOPT_SSL_VERIFYPEER => false
    ));

    $result = curl_exec($ch);
    curl_close($ch);

    //echo $result;	
  } catch (Exception $ex) {
    echo $ex->getMessage();
  }
} else {

  ?>
  <div class="center_screen"><?php echo $email; ?></div>
  <?php
  ?>
  <script>
    setTimeout(function() {
      Swal.fire({
        title: 'ไม่มี Email นี้ในระบบ',
        icon: 'error',
        confirmButtonText: 'ย้อนกลับ',
        allowOutsideClick: false, // ไม่อนุญาตให้คลิกนอก popup ปิด
        allowEscapeKey: false, // ไม่อนุญาตให้กดปุ่ม ESC เพื่อปิด
        allowEnterKey: false // ไม่อนุญาตให้กดปุ่ม Enter เพื่อปิด
      }).then((result) => {
        if (result.isConfirmed) {
          window.history.back(-1);
        }
      });
    });
  </script>
<?php
}


?>