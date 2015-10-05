## README.md Outline


* Email service

project: An Email service that uses two proiders; a main one and a backup in case that fails

track: backend

reason: I chose the email service because I enjoy writing backend code, have some experience using mailgun in our dev portal and it's something that can be re-used at some other point. I used PHP as the backend because I am most familiar with that language. I have 2.5+ years with it. 

trade-offs/more: The usual trade-offs of speed vs quality vs inexpensive comes into play. Due to time constraints, I wasn't able to incorporate this into a framework like CodeIgniter to give it a MVC framework. This would allow routing to be seemless and less time on writing sanitization code.

Like with any API that we write at my current company, I would like to write unit tests. This would especially be important for all the use cases due to dependancies on the third parties. Getting this deployed on Amazon was also a challenge. Again, this is due to time constraints. With more time, I'd like to have a nice interface test out the service.

NOTE: I've been trying to get my domain whitelisting from mailgun and while I updated one of the DNS records, I wasn't able to add the DKIM or unless the TTL is longer than 48 hrs. As a result, I noticed that yahoo accounts had difficulty receiving the emails while the gmail accounts didn't have an issue.

I don't have much public backend code I can share, but I encourage you to register with the thismoment developer platform and you'll be able to see our docs and apis which I helped write.

USAGE:

the email requires a POST
it needs to call the controller/mailer.php
$email_data  = array('from'       => 'Thanh Pham',
                     'from_email' => 'useagmailaccount @gmail.com',
                     'to'         => 'Tester',
                     'to_email'   => 'asdf+1@gmail.com',
                     'subject'    => time() . ' Just seeing...',
                     'text'       => 'if some email magic is about to happen!');

the logic in the controller will now try to clean the fields and make a cURL call to mail gun. If it succeeds, it will kick back a 200 status code but if not, it will try to send it via mandrill.

