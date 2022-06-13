<?php

namespace App\Models\Common;

enum ClientApp: string
{
    case NativeAndroid = 'com.example.native_app';
    case NativeIos = 'com.example.nativeApp';
    case Web = 'web';
}
