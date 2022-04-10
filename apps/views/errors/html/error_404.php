<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html style="height:100%">

<head>
    <meta charset="utf-8" />
    <title>OOPS! - Could not Find it - Whatsapp Group</title>
    <meta name="viewport" content="width=device-width" />
    <style type="text/css">
        body {
            margin: 0;
            background: url("/assets/icons/oops.png") no-repeat;
            background-size: contain;
            position: relative;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #768796;
            min-height: 100%
        }

        .wrap {
            position: absolute;
            top: 60%;
            left: 45%
        }

        .wrap div>span {
            font-weight: 600
        }

        .wrap div {
            font-size: 35px;
            margin-bottom: 30px;
            letter-spacing: 1px;
            font-weight: 300
        }

        .button {
            text-decoration: none;
            border: 2px solid #B4C4D1;
            background-color: #fff;
            padding: 10px 20px;
            color: #8399AB;
            cursor: pointer;
            letter-spacing: .8px;
            font-weight: 600;
            font-size: 16px
        }

        .button span:before {
            content: '\2329';
            position: relative;
            bottom: 1px
        }

        @media only screen and (max-width: 600px) {
            .wrap {
                position: absolute;
                top: 47%;
                left: 6%;
            }

            .wrap div {
                font-size: 28px;
                margin-bottom: 30px;
                letter-spacing: 1px;
                font-weight: 300;
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div><span>Oops !</span> that page never<br />returned from Traffic</div>
        <a href="/" class="button"><span>&nbsp;&nbsp; GO BACK TO HOME PAGE</span></a>
    </div>
</body>

</html>