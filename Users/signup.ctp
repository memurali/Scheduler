<?php  
	echo $this->Html->css('foundation.css');
	echo $this->Html->css('app.css');
	echo $this->Html->script('jquery.js');
	echo $this->Html->script('app.js');
	echo $this->Html->script('common.js');	
	echo $this->Html->script('foundation.js'); 
	echo $this->Html->script('what-input.js');	  
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundation for Sites</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
  </head>
  <body class="sign-in dark-bg-form">
    <div class="content">
      <div class="grid-container">
        <div class="grid-x grid-padding-x vertical-center">
          <div class="large-12 cell">
            <div class="callout">
              <form>
                <div class="grid-x grid-padding-x">
                  <div class="large-5 cell intro">
                    <div class="grid-x">
                      <div class="large-12 cell">
                        <h2 class="text-center">Let's Get Started!</h2>
                        <p>Donec id elit non mi porta gravida at eget metus. Sed posuere consectetur est at lobortis. Integer posuere erat a ante venenatis dapibus posuere velit aliquet.</p>
                      </div>
                    </div>  
                    <div class="grid-x">
                      <div class="large-12 cell text-center">
                        <div class="profile-image-upload" style="background-image: url(https://iconmonstr.com/wp-content/g/gd/makefg.php?i=../assets/preview/2012/png/iconmonstr-google-plus-4.png&r=0&g=0&b=0">
                        </div>
                        <label for="exampleFileUpload" class="button small">Upload Logo</label>
                        <input type="file" id="exampleFileUpload" class="show-for-sr">
                      </div>
                    </div>
                    <svg viewbox="0 0 100 25">
                      <path fill="#1B395A" opacity="0.5" d="M0 30 V15 Q30 3 60 15 V30z" />
                      <path fill="#1B395A" d="M0 30 V12 Q30 17 55 12 T100 11 V30z" />
                    </svg>
                  </div>
                  <div class="large-7 cell info">
                    <div class="">
                      <div class="grid-x grid-padding-x">
                        <div class="large-6 cell">
                          <label>First Name
                            <input type="text" placeholder="First Name">
                          </label>
                        </div>
                        <div class="large-6 cell">
                          <label>Last Name
                            <input type="text" placeholder="Last Name">
                          </label>
                        </div>
                      </div>
                      <div class="grid-x">
                        <div class="large-12 cell">
                          <label>Email
                            <input type="text" placeholder="Email">
                          </label>
                        </div>
                      </div>
                      <div class="grid-x grid-padding-x">
                        <div class="large-6 cell">
                          <label>Phone
                            <input type="text" placeholder="First Name">
                          </label>
                        </div>
                        <div class="large-6 cell">
                          <label>Email
                            <input type="text" placeholder="Last Name">
                          </label>
                        </div>
                      </div>
                      <div class="grid-x grid-padding-x">
                        <div class="large-6 cell">
                          <label>Business Name
                            <input type="text" placeholder="Address">
                          </label>
                        </div>
                         <div class="large-6 cell">
                          <label>Address
                            <input type="text" placeholder="Address">
                          </label>
                        </div>
                      </div>
                      <div class="grid-x grid-padding-x">
                        <div class="large-5 cell">
                          <label>City
                            <input type="text" placeholder="City">
                          </label>
                        </div>
                        <div class="large-3 cell">
                          <label>State
                            <select>
                              <option value="ca">CA</option>
                            </select>
                          </label>
                        </div>
                        <div class="large-4 cell">
                          <label>Zip
                            <input type="number" placeholder="Zip">
                          </label>
                        </div>
                      </div>
                      <div class="grid-x grid-padding-x">
                        <div class="large-6 cell">
                          <label>Username
                            <input type="text" placeholder="Username">
                          </label>
                        </div>
                        <div class="large-6 cell">
                          <label>Password
                            <input type="password" placeholder="Password">
                          </label>
                        </div>
                      </div>
                      <div class="grid-x ">
                        <div class="large-12 cell">
                          <small>By proceeding to create your account, you are agreeing to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>. If you do not agree, you cannot use -----.</small>
                          <br />
                          <br />
                        </div>
                        <div class="large-12 cell">
                          <a class="button" href="#">Submit</a>
                        </div>
                      </div>
                    </div>
                    <div class="confirmation hide">
                      <div class="grid-x">
                        <div class="large-12 cell">
                          <h2>Almost Done!</h2>
                          <p>We have sent an email with a confirmation link to your email address. In order to complete the sign-up process, please click the confirmation link.</p>
                          <p>If you do not receive a confirmation email, please check your spam folder. Also, please verify that you entered a valid email address in our sign-up form.</p>
                          <p>If you have any questins, please <a href="#">contact us.</a></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
