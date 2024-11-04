<?php

namespace App\Controller\Component;

use App\Controller\Component\AppComponent;

class FlashComponent extends AppComponent {
    public function success($message) {
        echo "<script>showToast('success', '{$message}');</script>";
    }

    public function error($message) {
        echo "<script>showToast('error', '{$message}');</script>";
    }
}
