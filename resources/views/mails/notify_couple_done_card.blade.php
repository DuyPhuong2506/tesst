<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        body {
            width: 100%;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            font-family: Georgia, Times, serif;
        }

        table {
            border-collapse: collapse;
        }

        td#logo {
            margin: 0 auto;
            padding: 14px 0;
        }

        img {
            border: none;
            display: block;
        }

        a.blue-btn {
            display: inline-block;
            margin-bottom: 34px;
            border: 3px solid #3baaff;
            padding: 11px 38px;
            font-size: 12px;
            font-family: arial;
            font-weight: bold;
            color: #3baaff;
            text-decoration: none;
            text-align: center;
        }

        a.blue-btn:hover {
            background-color: #3baaff;
            color: #fff;
        }

        a.white-btn {
            display: inline-block;
            margin-bottom: 30px;
            border: 3px solid #fff;
            background: transparent;
            padding: 11px 38px;
            font-size: 12px;
            font-family: arial;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
            text-align: center;
        }

        a.white-btn:hover {
            background-color: #fff;
            color: #3baaff;
        }

        .border-complete {
            border-top: 1px solid #dadada;
            border-left: 1px solid #dadada;
            border-right: 1px solid #dadada;
        }

        .border-lr {
            border-left: 1px solid #dadada;
            border-right: 1px solid #dadada;
        }

        #banner-txt {
            color: #fff;
            padding: 15px 32px 0px 32px;
            font-family: arial;
            font-size: 13px;
            text-align: center;
        }

        h2#our-products {
            font-family: "Pacifico";
            margin: 23px auto 5px auto;
            font-size: 27px;
            color: #3baaff;
        }

        h3.our-products {
            font-family: arial;
            font-size: 15px;
            color: #7c7b7b;
        }

        p.our-products {
            text-align: center;
            font-family: arial;
            color: #7c7b7b;
            font-size: 12px;
            padding: 10px 10px 24px 10px;
        }

        h2.special {
            margin: 0;
            color: #fff;
            color: #fff;
            font-family: "Pacifico";
            padding: 15px 32px 0px 32px;
        }

        p.special {
            color: #fff;
            font-size: 12px;
            color: #fff;
            text-align: center;
            font-family: arial;
            padding: 0px 32px 10px 32px;
        }

        h2#coupons {
            color: #3baaff;
            text-align: center;
            font-family: "Pacifico";
            margin-top: 30px;
        }

        p#coupons {
            color: #7c7b7b;
            text-align: center;
            font-size: 12px;
            text-align: center;
            font-family: arial;
            padding: 0 32px;
        }

        #socials {
            padding-top: 12px;
        }

        p#footer-txt {
            text-align: center;
            color: #303032;
            font-family: arial;
            font-size: 12px;
            padding: 0 32px;
        }

        #social-icons {
            width: 28%;
        }

        @media only screen and (max-width: 640px) {
            body[yahoo] .deviceWidth {
                width: 440px !important;
                padding: 0;
            }
            body[yahoo] .center {
                text-align: center !important;
            }
            #social-icons {
                width: 40%;
            }
        }

        @media only screen and (max-width: 479px) {
            body[yahoo] .deviceWidth {
                width: 280px !important;
                padding: 0;
            }
            body[yahoo] .center {
                text-align: center !important;
            }
            #social-icons {
                width: 60%;
            }
        }

    </style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix" style="font-family: Georgia, Times, serif">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
       <a href="#" style="display: block; margin: auto; width: 600px; height: 150px; overflow: hidden;">
       <img style="width: 100%; height: 100%; object-fit: cover" src="https://d3jmn01ri1fzgl.cloudfront.net/photoadking/webp_thumbnail/5ff82014131b7_json_image_1610096660.webp" alt="" border="0" />
       </a>
       <table width="600" height="108" border="0" cellpadding="0" cellspacing="0" align="center" class="border-lr deviceWidth" bgcolor="#3baaff">
          <tr>
             <td align="center">
                <p style="color: white">The couple in complete create wedding card ! Please check it at link below! Many thanks !</p>
                <a href={{$appURL}} class="white-btn" align="center">Go To Check</a>
             </td>
          </tr>
       </table>
       <div style="width: 600px; margin: auto; padding: 20px; border: 1px solid rgb(228, 228, 228); box-sizing: border-box;">
            <p>{{$contactName}} 様</p>
            <p>いつもWow(Watching Online Wedding)のご利用ありがとうございます。</p>
            <p>{{$customerName}} 様 からWEB招待状の確認依頼がきておりますので、サイトにログインして内容をご確認ください。</p><br>
       </div>
       <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="border-lr deviceWidth" bgcolor="#fff">
          <tr>
             <td style="text-align: center; padding-top:20px">
                <a href={{$appURL}} class="blue-btn" align="center">Go To Check</a>
                <p>※system@wowwedding.jp は送信専用のメールアドレスのため、本メールに返信をいただくことはできません。
                    お手数ですが、お問い合わせは弊社担当まで直接お願いいたします。</p>
             </td>
          </tr>
       </table>
       <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="border-complete deviceWidth" bgcolor="#eeeeed">
          <tr>
             <td style="text-align: center;">
                <p id="footer-txt"> 
                    <b>Wow(Watching Online Wedding)</b><br>
                    <a href="{{$appURL}}">{{$appURL}}</a>
                    <br/> Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                </p>
             </td>
          </tr>
       </table>
    </table>
    <!-- End Wrapper -->
</body>
</html>