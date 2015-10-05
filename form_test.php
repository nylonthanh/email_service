<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ACME email form</title>
<link rel="stylesheet" type="text/css" href="form_test/view.css" media="all">
<script type="text/javascript" src="form_test/view.js"></script>

</head>
<body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<div id="form_container">
	
		<h1><a>ACME email form</a></h1>
		<form id="form_867176" class="appnitro"  method="post" action="controller/mailer?entry=PASSWORD">
					<div class="form_description">
			<h2>ACME email form</h2>
			<p>Email test form</p>
		</div>						
			<ul >
			
					<li id="li_3" >
		<label class="description" for="element_3">Who is this going to? </label>
		<span>
			<input id="to" name= "to" class="element text" maxlength="255" size="8" value=""/>
			<label>Name</label>
		</span>
		</li>		<li id="li_2" >
		<label class="description" for="element_2">What is their email? </label>
		<div>
			<input id="to_email" name="to_email" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">What is the subject? </label>
		<div>
			<input id="subject" name="subject" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_1" >
		<label class="description" for="element_1">What do you want to say? </label>
		<div>
			<textarea id="text" name="text" class="element textarea medium"></textarea> 
		</div><p class="guidelines" id="guide_1"><small>Message body</small></p> 
		</li>
                <input type="hidden" name="from" value="email tester"></input>
                <input type="hidden" name="from_email" value="no-reply@emailtester.com"></input>
                <input type="hidden" name="address" value=""></input>
                    
                
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="867176" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	
		<div id="footer">
			Demo form to test the email service
		</div>
	</div>
	<img id="bottom" src="bottom.png" alt="">
	</body>
</html>