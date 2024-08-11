<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function create(BookCreateRequest $request): JsonResponse {
        $data = $request->validated();
        $user = Auth::user();

        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('cover_images', 'public');
            $data['cover_image'] = $coverImagePath;
        }

        if ($request->hasFile('pdf_file')) {
            $pdfFilePath = $request->file('pdf_file')->store('pdf_files', 'public');
            $data['pdf_file'] = $pdfFilePath;
        }

        $data['created_by'] = $user->id;

        $book = new Book($data);
        $book->save();

        return (new BookResource($book))->response()->setStatusCode(201);
    }

    public function getAllBook(): JsonResponse {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $books = Book::with('category')->get();
        } else {
            $books = Book::where('created_by', $user->id)->with('category')->get();
        }

        return response()->json(BookResource::collection($books), 200);
    }

    public function getAllBookByFilterByCategory($category_id): JsonResponse {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $books = Book::where('category_id', $category_id)->with('category')->get();
        } else {
            $books = Book::where('category_id', $category_id)
                         ->where('user_id', $user->id)
                         ->with('category')
                         ->get();
        }

        if ($books->isEmpty()) {
            return response()->json(['message' => 'No books found'], 404);
        }

        return response()->json(BookResource::collection($books), 200);
    }

    public function update(int $id, BookUpdateRequest $request): JsonResponse {
        $data = $request->validated();
        $user = Auth::user();

        $book = Book::where('id', $id)
                ->where(function ($query) use ($user) {
                    if ($user->role !== 'admin') {
                        $query->where('created_by', $user->id);
                    }
                })->first();

        if (!$book) {
            return response()->json(['message' => 'cant update because its not yours'], 404);
        }

        Log::info('Updating Book:', $data);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $coverImagePath = $request->file('cover_image')->store('cover_images', 'public');
            $data['cover_image'] = $coverImagePath;
        }

        if ($request->hasFile('pdf_file')) {
            if ($book->pdf_file) {
                Storage::disk('public')->delete($book->pdf_file);
            }
            $pdfFilePath = $request->file('pdf_file')->store('pdf_files', 'public');
            $data['pdf_file'] = $pdfFilePath;
        }

        Log::info('Book updated successfully:', [
            'book_id' => $book->id,
            'data' => $book->toArray(),
        ]);

        $book->update($data);

        return (new BookResource($book))->response()->setStatusCode(200);
    }

    public function delete(int $id): JsonResponse {
        $user = Auth::user();

        $book = Book::where('id', $id)
                ->where(function ($query) use ($user) {
                    if ($user->role !== 'admin') {
                        $query->where('created_by', $user->id);
                    }
                })->first();

        if (!$book) {
            return response()->json(['message' => 'cant delete because its not yours'], 404);
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        if ($book->pdf_file) {
            Storage::disk('public')->delete($book->pdf_file);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted successfully'], 200);
    }

    public function getBookById(int $id): JsonResponse {
        $user = Auth::user();

        $book = Book::where('id', $id)
        ->where(function ($query) use ($user) {
            if ($user->role !== 'admin') {
                $query->where('created_by', $user->id);
            }
        })->first();

        if (!$book) {
            return response()->json(['message' => 'cant show because its not yours'], 404);
        }

        return response()->json(new BookResource($book), 200);
    }
}
