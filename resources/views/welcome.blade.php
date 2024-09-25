@extends('layouts.app')
@section('title')
  Home
@endSection
@section('content')
    <style>
        .owl-carousel .item {
            height: 10rem;
            background: #4DC7A0;
            padding: 1rem;
        }
        .owl-carousel .item h4 {
            color: #FFF;
            font-weight: 400;
            margin-top: 0rem;
        }
        .slick-slide{
            border: 1px solid #e7e7e7;
            border-radius: 2%;
            padding:20px
        }
        .checked {
            color: orange;
        }
    </style>
    
    <!-- content Starts here -->
    <div class="container mt-5 text-center">
        <h1>Welcome to EdGames</h1>
    </div>
@endSection
