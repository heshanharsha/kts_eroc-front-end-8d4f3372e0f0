<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>email Verification</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Oswald:100,500" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,500" rel="stylesheet">
    </head>
    <body style="font-family: 'Roboto', sans-serif;">
        <div style="width: 780px;height: 748px;margin-left: auto;margin-right: auto;background: #f2f2f2;">
          <div style="width: 100%;background: #ff4800;height: 167px;border-radius: 5px 5px 0px 0px;">
          </div>
          <div style="width: 70%;background: #fff;height: 63%;margin: auto;position: relative;top: -64px;border-radius: 5px;box-shadow: 0px 0px 1px 0px;">
            <div style="padding: 20px;">
                <img src="{{ asset('/images/logos/e-roc-bottom.png') }}" alt="eRoc" style="width: 40%;display: block;margin: auto;">
            </div>
            <h3 style="font-family: 'Oswald', sans-serif;font-size: 25px;text-align: center;color: #261269;">eRoc email Verification</h3>
            <p style="padding: 3px 35px;">Please click on the that has just been sent to your email account to verify your email and continue the registration process.</p>
            <div style="text-align: center;margin-top: 3em;margin-bottom: 3em;">
              <a href="{{ $activationLink }}" style="text-decoration: none;background: #ff4800;;color: #ffffff;padding: 10px 27px;border-radius: 4px;">Verify Now</a>
            </div>
            <div style="padding: 3px 35px;line-height: 25px;/*! font-weight: 300; */">
                <span>Thank You</span>
                <br>
                <small style="margin-left: 14px;">eRoc Team</small>
            </div>
          </div>
          <div>

          </div>
        </div>
    </body>
</html>
