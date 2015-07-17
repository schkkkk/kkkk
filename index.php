<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <title>WEBRTC camera</title>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.red-blue.min.css">
    <style>
        #canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: scroll;
        }
        video {
            transform: translateX(-25%);
        }
        video + div {
            width: 100%;
            height: 2px;
            background: rgba(255, 255, 255, .6);
            position: absolute;
        }
    </style>
<body>
    <div id="canvas"><video></video><div></div></div>

    <script>
        try
        {
            screen.orientation.lock('portrait');
        }
        catch (e)
        {
            if (!screen.mozLockOrientation('portrait'))
            {
                alert('화면 고정을 실패했습니다.\n화면 회전 기능을 수동으로 꺼주세요.');
            }
        }

        var camera = document.querySelector('video');
        camera.height = window.innerHeight * 2;
        camera.ground = camera.nextSibling;
        camera.canvas = camera.parentNode;
        camera.addEventListener('loadedmetadata', () => {
            camera.ground.style.marginTop = -camera.height / 2 + 'px';
            camera.canvas.scrollTop = camera.height / 2 / 7;
            // Chrome needs EventCallback to play
            camera.play();
        });
        camera.addEventListener('contextmenu', e => {
            e.preventDefault();
        });

        navigator.mediaDevices.getUserMedia({
            video: true,
            // Chrome 46.0.2457.0 does not support below constraint
            // video: {
            //     width: 640,
            //     height: 480,
            //     facingMode: 'environment',
            // },
        }).then(stream => {
            camera.src = URL.createObjectURL(stream);
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