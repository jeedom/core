<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restauration du Système / System Restoration</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f0f0f0;
            flex-direction: column;
        }

        .container {
            text-align: center;
            margin-bottom: 20px;
        }

        .message {
            font-size: 24px;
            margin-top: 20px;
        }

        .image-container {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            animation: spin 10s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logs {
            width: 100%;
            height: 70%;
            background: #ffffff;
            border: 1px solid #ccc;
            overflow-y: auto;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: monospace;
            font-size: 14px;
        }

        .log-entry {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img src="core/img/LoadingIndicator-512.png" alt="Restoration Image" width=100>
        </div>
        <div class="message" id="message"></div>
        <div class="message"><?php echo date('Y-m-d H:i:s') ?></div>
    </div>
    <?php 
        try {
            require_once __DIR__ . "/core/php/core.inc.php";
            if(network::getUserLocation() == 'internal'){
                echo '<div class="logs" id="logs">';
                echo str_replace("\n","<br/>",file_get_contents(__DIR__.'/log/restore'));
                echo '</div>';
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    ?>
    <script>
        function getBrowserLanguage() {
            return (navigator.language || navigator.userLanguage).toLowerCase();
        }
        function displayMessage() {
            const messageElement = document.getElementById('message');
            const browserLanguage = getBrowserLanguage();

            const messages = {
                'fr': 'Le système est actuellement en cours de restauration...',
                'en': 'The system is currently being restored...'
            };

            if (browserLanguage.startsWith('fr')) {
                messageElement.textContent = messages['fr'];
            } else {
                messageElement.textContent = messages['en'];
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            displayMessage();
            const element = document.getElementById('logs');
            element.scrollTop = element.scrollHeight;
            window.setTimeout(function() {
                window.location.reload();
            }, 10000);
        });
        
    </script>
</body>
</html>
