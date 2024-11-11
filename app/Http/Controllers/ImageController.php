<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

  public function show($folder, $subfolder, $subsubfolder, $filename)
  {
    if (!Auth::check()) return;
    $fullpath = "{$folder}/{$subfolder}/{$subsubfolder}/{$filename}";
    return response()->download(storage_path("app/private/{$fullpath}"), null, [], null);
  }
}
