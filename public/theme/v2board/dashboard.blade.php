<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="/theme/{{$theme}}/assets/components.chunk.css?v={{$verison}}">
    <link rel="stylesheet" href="/theme/{{$theme}}/assets/umi.css?v={{$verison}}">
    <link rel="stylesheet" href="/theme/{{$theme}}/assets/custom.css?v={{$verison}}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
    @php ($colors = [
        'darkblue' => '#3b5998',
        'black' => '#343a40',
        'default' => '#0665d0',
        'green' => '#319795'
    ])
    <meta name="theme-color" content="{{$colors[$theme_color]}}">

    <title>{{$title}}</title>
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700"> -->
    <script>window.routerBase = "/";</script>
    <script>
        window.settings = {
            title: '{{$title}}',
            theme: {
                sidebar: '{{$theme_sidebar}}',
                header: '{{$theme_header}}',
                color: '{{$theme_color}}',
            },
            verison: '{{$verison}}',
            background_url: '{{$backgroun_url}}',
            description: '{{$description}}',
            crisp_id: '{{$crisp_id}}'
        }
    </script>
</head>

<body>
<div id="root"></div>
<script src="/theme/{{$theme}}/assets/vendors.async.js?v={{$verison}}"></script>
<script src="/theme/{{$theme}}/assets/components.async.js?v={{$verison}}"></script>
<script src="/theme/{{$theme}}/assets/umi.js?v={{$verison}}"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-P1E9Z5LRRK"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'G-P1E9Z5LRRK');
</script>
<!-- Messenger Plugin chat Code -->
    <div id="fb-root"></div>

    <!-- Your Plugin chat code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "107030528490153");
      chatbox.setAttribute("attribution", "biz_inbox");
    </script>

    <!-- Your SDK code -->
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v12.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
</body>

</html>
