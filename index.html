<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <title>WEBRTC camera</title>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.red-blue.min.css">
<body>
    <video></video>

    <script>
        var camera = document.querySelector('video');
        camera.addEventListener('canplay', () => {
            // Chrome needs EventCallback to play
            camera.play();
        });

        (window.onresize = function()
        {
            camera.width = window.innerWidth;
            camera.height = window.innerHeight;
        })();

        navigator.mediaDevices.getUserMedia({
            // video: true,
            // Chrome 46.0.2457.0 does not support below constraint
            video: {
                width: 720,
                height: 480,
                facingMode: 'environment',
            },
        }).then(stream => {
            camera.src = window.URL.createObjectURL(stream);
            camera.play();
        }).catch(err => {
            alert(err);
            if (err.name == 'NotFoundError')
            {
                alert('사용가능한 카메라가 없습니다!');
            }
            else
            {
                alert('카메라 접근 권한을 허용해주세요');
            }
        });
    </script>
</html>