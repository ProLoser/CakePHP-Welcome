# Welcome Plugin For CakePHP

Common user functionality to be used with core Auth component for CakePHP

## Features 

### Membership Behavior

* Validation
	* Confirm password field validation
	* Old password field validation
	* Unique [username] field validation
	
### Membership Component

* Email confirmation for registration (in progress)
* Remember Me checkbox (in progress)

* Scaffolded views (for copying)
	* Login
	* Register
	* Forgotton Password (reset account)
	* Update Password


## Installation

1. Download or clone plugin to plugins/welcome (git://github.com/ProLoser/CakePHP-Welcome.git)
2. Install 'Membership' behavior:

<pre><code>
class User extends AppModel {
	var $name = 'User';
	var $actsAs = array(
		'Welcome.Membership' => array(
			// Optional configuration options
			'fields' => array(
				// fieldnames to be used
				'username' => 'username',
				'password' => 'password',
				'old_password' => 'old_password',
				'confirm_password' => 'confirm_password',
			),
		),
	);

}
</code></pre>

3. Just visit /welcome/users/register and the other controller actions in your browser

## Custom Views / Emails

If you would like to override the default views, you can use the CakePHP standard by placing your views in:
<code>app/views/plugins/welcome/users/[here]</code>
And your email templates in:
<code>app/views/plugins/welcome/elements/email/[html|text]/register.ctp</code>