<?php

use Illuminate\Support\Facades\Route;

Route::get('/hello', function () { 
return 'Xin chào Laravel 12!'; 
}); 
Route::get('/time', function () { 
// Dùng Carbon (helper now()) để định dạng thời gian 
return now()->format('H:i:s d/m/Y'); 
});
Route::get('/sum/{a}/{b}', function ($a, $b) { 
if (!is_numeric($a) || !is_numeric($b)) { 
return response('Tham số phải là số nguyên', 400); 
} 
return (int)$a + (int)$b; 
});
