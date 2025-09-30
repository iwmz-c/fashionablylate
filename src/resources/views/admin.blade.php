@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('header')
<div class="header-nav">
    <form class="form" action="{{ route('logout') }}" method="POST">
    @csrf
        <button type="submit" class="header-nav__button">logout</button>
    </form>
</div>
@endsection

@section('content')
<div class="admin__content">
    <div class="admin__heading">

        <p>Admin</p>
    </div>
    <form class="search-form" action="/admin/search" method="get">
        <div class="search-form__item">
            <input class="search-form__item-input" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="名前やメールアドレスを入力してください"/>
            <select class="search-form__item-gender" name="gender">
                <option value="" selected hidden>性別</option>
                <option value="">全て</option>
                <option value="1" {{ request('gender') == 1 ? 'selected' : '' }}>男性</option>
                <option value="2" {{ request('gender') == 2 ? 'selected' : '' }}>女性</option>
                <option value="3" {{ request('gender') == 3 ? 'selected' : '' }}>その他</option>
            </select>
            <select class="search-form__item-category" name="category_id">
            <option value="" selected hidden>お問い合わせの種類</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->content }}
                    </option>
                @endforeach
            </select>
            <input class="search-form__item-date" name="created_at" type="date" value="{{ request('created_at') }}" />
            <div class="search-form__button">
                <button class="search-form__button-submit" type="submit">検索</button>
                <a class="search-form__button-reset" href="/admin">リセット</a>
            </div>
        </div>
    </form>

    {{ $contacts->appends(request()->query())->links('vendor.pagination.tailwind') }}

    <div class="contact-table">
        <table class="contact-table__inner">
            <tr class="contact-table__row">
                <th class="contact-table__header">お名前</th>
                <th class="contact-table__header gender-col">性別</th>
                <th class="contact-table__header">メールアドレス</th>
                <th class="contact-table__header">お問い合わせの種類</th>
                <th class="contact-table__header"></th>
            </tr>
            @foreach($contacts as $contact)
            <tr class="contact-table__row">
                <td class="contact-table__item">
                    {{ $contact->last_name }}  {{ $contact->first_name }}
                </td>
                <td class="contact-table__item gender-col">
                    {{ $contact->gender_text }}
                </td>
                <td class="contact-table__item">
                    {{ $contact->email }}
                </td>
                <td class="contact-table__item">
                    {{ $contact->category->content }}
                </td>
                <td>
                    <button class="detail-btn" data-id="{{ $contact->id }}">詳細</button>
                </td>
            </tr>
            @endforeach
        </table>

        <div id="detailModal">
            <div class="modal-box">
                <div style="text-align: right; margin-top: 10px;">
                    <button id="closeModal">✕</button>
                </div>
                <div id="modalContent"></div>
                <form id="deleteForm" method="POST" style="margin: 20px; text-align: center;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background-color:#BA370D;  color:white; padding:3px 12px; border:none; cursor:pointer;">削除</button>
                </form>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.detail-btn');
            const modal = document.getElementById('detailModal');
            const modalContent = document.getElementById('modalContent');
            const closeBtn = document.getElementById('closeModal');

            // 各「詳細」ボタンにイベントをつける
            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const contactId = this.dataset.id;

                    // JSONでデータ取得（Laravelのshowルート）
                    fetch(`/contacts/${contactId}`)
                        .then(response => response.json())
                        .then(data => {
                            modalContent.innerHTML = `
                                <p><strong>お名前</strong><span>${data.last_name} ${data.first_name}</span></p>
                                <p><strong>性別</strong><span>${data.gender_text}</span></p>
                                <p><strong>メールアドレス</strong><span>${data.email}</span></p>
                                <p><strong>電話番号</strong><span>${data.tel}</span></p>
                                <p><strong>住所</strong><span>${data.address}</span></p>
                                <p><strong>建物名</strong><span>${data.building ?? ''}</span></p>
                                <p><strong>お問い合わせの種類</strong><span>${data.category?.content ?? ''}</span></p>
                                <p><strong>お問い合わせ内容</strong><span>${data.detail}</span></p>
                            `;
                            modal.style.display = 'flex'; // 表示する

                            const deleteForm = document.getElementById('deleteForm');
                            deleteForm.action = `/contacts/${contactId}`;
                        });
                });
            });

            // 閉じるボタン
            closeBtn.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        });
        </script>

    </div>
</div>
@endsection