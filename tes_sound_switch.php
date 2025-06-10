<?php
// File: enhanced_preview.php

class SwitchPreview {
    private $switches;
    
    public function __construct() {
        $this->switches = [
            'red' => [
                'sound' => 'Cherry_MX_Red.mp3',
                'desc' => 'Linear - Halus tanpa bump'
            ],

        ];
    }
    
    public function render() {
        echo '<div class="switch-container">';
        foreach ($this->switches as $name => $data) {
            echo '<div class="switch-card">';
            echo '<h3>'.ucfirst($name).' Switch</h3>';
            echo '<p>'.$data['desc'].'</p>';
            echo '<button class="play-btn" data-sound="'.$data['sound'].'">';
            echo '<i class="fas fa-play"></i> Play Sound';
            echo '</button>';
            echo '</div>';
        }
        echo '</div>';
    }
}

$preview = new SwitchPreview();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enhanced Switch Preview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .switch-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .switch-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            width: 250px;
        }
        .play-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .play-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    
    <?php $preview->render(); ?>
    
    <audio id="audioPlayer"></audio>
    
    <script>
    document.querySelectorAll('.play-btn').forEach(button => {
        button.addEventListener('click', function() {
            const soundFile = this.getAttribute('data-sound');
            const audio = document.getElementById('audioPlayer');
            
            // Hentikan audio yang sedang diputar jika ada
            audio.pause();
            
            // Set sumber audio baru dan mainkan
            audio.src = soundFile;
            audio.play();
            
            // Ganti ikon saat diputar
            const icon = this.querySelector('i');
            icon.classList.replace('fa-play', 'fa-pause');
            
            // Kembalikan ikon ketika audio selesai
            audio.onended = function() {
                icon.classList.replace('fa-pause', 'fa-play');
            };
        });
    });
    </script>
</body>
</html>