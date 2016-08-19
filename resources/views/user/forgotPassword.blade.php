Hello {{ $email }},

Click here to change your password:

<a href="https://{{$url}}/resetPassword/?token={{$token_str}}&usertype={{$usertype}}">Reset Password</a>