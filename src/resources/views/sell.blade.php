@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-form">
    <h2 class="sell-form__heading">商品の出品</h2>
    <div class="sell-form__inner">
        <form class="sell-form__form" action="/sell" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="sell-form__group">
                <label class="sell-form__label">商品画像</label>
                <div class="sell-form__upload-inner">
                    <img class="sell-form__img" id="imagePreview" src="" alt="プレビュー画像" style="display: none; width: 130px; height: 130px; object-fit: cover;  margin-left: -160px; margin-right: 30px;">
                    <input type="file" id="fileInput" name="image_path" accept="image/*" style="display:none;">
                    <label class="sell-form__upload" for="fileInput">画像を選択する</label>
                </div>
                <p class="sell-form__error-message">
                    @error('image_path')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById('fileInput').addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    const preview = document.getElementById('imagePreview');

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.style.display = "block";
                        }
                        reader.readAsDataURL(file);
                    } else {
                        preview.src = "";
                        preview.style.display = "none";
                    }
                });
                if (preview.src) {
                    preview.style.display = "block";
                }
            });
            </script>

            <h3 class="sell-form__ttl">商品詳細</h3>
            <div class="sell-form__group">
                <label class="sell-form__label" for="">カテゴリー</label>
                <div class="sell-form__checkbox-inner">
                    @foreach($categories as $category)
                    <input class="checkbox" type="checkbox" name="categories[]" id="category_{{ $category->id }}" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }} style="display:none; [input[type="checkbox"]:checked + label]">
                    <label class="sell-form__checkbox" for="category_{{ $category->id }}">{{ $category->content }}</label>
                    @endforeach
                </div>
                <p class="sell-form__error-message">
                    @error('categories')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="">商品状態</label>
                <div class="sell-form__select-wrap">
                    <select class="sell-form__select-inner" name="condition" required>
                        <option value="" hidden>選択してください</option>
                        <option value="1" {{ old('condition', $input['condition'] ?? '') == '1' ? 'selected' : '' }}>良好</option>
                        <option value="2" {{ old('condition', $input['condition'] ?? '') == '2' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                        <option value="3" {{ old('condition', $input['condition'] ?? '') == '3' ? 'selected' : '' }}>やや傷や汚れあり</option>
                        <option value="4" {{ old('condition', $input['condition'] ?? '') == '4' ? 'selected' : '' }}>状態が悪い</option>
                    </select>
                    <div class="sell-form__select-arrow"></div>
                </div>
                <p class="sell-form__error-message">
                    @error('condition')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <h3 class="sell-form__ttl">商品名と説明</h3>
            <div class="sell-form__group">
                <label class="sell-form__label" for="name">商品名</label>
                <input class="sell-form__input" type="text" id="name" name="name">
                <p class="sell-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="brand">ブランド名</label>
                <input class="sell-form__input" type="text" id="brand" name="brand" value="{{ old('brand') }}">
                <p class="sell-form__error-message">
                    @error('brand')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="description">商品の説明</label>
                <textarea name="description" id="description" class="sell-form__input">{{ old('description') }}</textarea>
                <p class="sell-form__error-message">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="price">販売価格</label>
                <div class="sell-form__price-container">
                    <span class="sell-form__price-symbol">¥</span>
                    <input class="sell-from__price-input" type="text" id="price" name="price" value="{{ old('price') }}">
                </div>
                <p class="sell-form__error-message">
                    @error('price')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <button class="sell-form__btn btn" type="submit">出品する</button>
        </form>
    </div>
</div>
@endsection