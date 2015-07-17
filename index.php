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
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        video + div {
            position: absolute;
            width: 100%;
            height: 2px;
            border: 1px solid #ddd;
            background: #fff;
            pointer-events: none;
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
            alert('화면 고정을 실패했습니다.\n화면 회전 기능을 수동으로 꺼주세요.');
        }

        var camera = document.querySelector('video');
        camera.height = window.innerHeight * 1.5;
        camera.ground = camera.nextElementSibling;
        (camera.adjustGround = () => {
            camera.ground.style.top = camera.height / 2 + 'px';
        })();
        camera.canvas = camera.parentNode;
        camera.addEventListener('loadedmetadata', e => {
            // Chrome needs EventCallback to play
            camera.play();
        });
        camera.addEventListener('touchstart', e => {
            camera.zoom = undefined;
        });
        camera.addEventListener('touchmove', e => {
            if (e.changedTouches.length == 2)
            {
                var dx = e.changedTouches[0].clientX - e.changedTouches[1].clientX,
                    dy = e.changedTouches[0].clientY - e.changedTouches[1].clientY;
                var zoom = Math.sqrt(dx * dx + dy * dy);
                if (typeof camera.zoom === 'undefined')
                {
                    camera.zoom = zoom;
                    camera.zoomBase = camera.height / window.innerHeight;
                }
                zoom = Math.max(1, Math.min(camera.zoomBase * (1 + (zoom - camera.zoom) / 300), 2));
                camera.height = window.innerHeight * zoom;
                camera.adjustGround();
            }
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