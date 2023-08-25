<!DOCTYPE html>
<!--
Template Name: Rubick - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $dark_mode ? 'dark' : '' }} theme-1">
<!-- BEGIN: Head -->
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{ asset('imgs/favicon.ico') }}" type="image/vnd.microsoft.icon" />
    {{-- <link href="{{ asset('build/assets/images/logo.svg') }}" rel="shortcut icon"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Administración Incibe Formación">
    <meta name="keywords" content="">
    <meta name="author" content="">

    @yield('head')

    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <!-- END: CSS Assets-->
    @livewireStyles
</head>
<!-- END: Head -->

@yield('body')

</html>
