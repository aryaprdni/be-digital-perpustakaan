@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Welcome to the Book Library</h1>
            <p class="text-center">Explore our collection of books and find your next great read!</p>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Example book list -->
        @foreach ($books as $book)
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ $book->cover_image }}" class="card-img-top" alt="{{ $book->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text">{{ $book->author }}</p>
                        <a href="{{ route('book.show', $book->id) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
