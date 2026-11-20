<?php

return [
    // API key chỉ nằm trong .env trên máy chạy ứng dụng; không đưa vào Git.
    'api_key' => env('GEMINI_API_KEY', ''),
    'model' => env('GEMINI_MODEL', 'gemini-3.1-flash-lite'),

    // Không bắt người đọc đợi lâu khi Google chậm hoặc quá tải.
    'connect_timeout' => 3,
    'timeout' => 8,
    'cache_minutes' => 20,
];
