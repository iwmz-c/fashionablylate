<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index() {
        $contacts = Contact::with('category')->get();
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    public function confirm(ContactRequest $request) {
        $tel = $request->only(['tel1','tel2','tel3']);
        $tel = $tel['tel1'] . $tel['tel2'] . $tel['tel3'];

        $contact = $request->only(['last_name', 'first_name', 'gender', 'email', 'address', 'building', 'category_id', 'detail']);

        $genders = [1 => '男性', 2 => '女性', 3 => 'その他'];
        $contact['gender_text'] = $genders[$contact['gender']] ?? '不明';

        $categories = Category::pluck('content', 'id');
        $contact['category_text'] = $categories[$contact['category_id']] ?? '未分類';

        return view('confirm', compact('contact', 'tel'));
    }

    public function store(Request $request) {
        if ($request->input('action') === 'modify') {
            return redirect('/');
        }
        
        $contact = $request->only([
            'last_name', 'first_name', 'gender', 'email', 'tel', 'address', 'building', 'category_id', 'detail'
        ]);
        Contact::create($contact);
        
        return view('thanks');
    }

    public function admin() {
        $contacts = Contact::with('category')->paginate(7);
        $categories = Category::all();
        return view('admin', compact('contacts', 'categories'));
    }

    public function search(Request $request) {
        $contacts = Contact::query()
            ->search($request->keyword)
            ->gender($request->gender)
            ->Category($request->category_id)
            ->createdDate($request->created_at)
            ->paginate(7);
        $categories = Category::all();

        return view('admin', compact('contacts', 'categories'));
    }

    public function show($id) {
        $contact = Contact::with('category')->findOrFail($id);

        $genders = [1 => '男性', 2 => '女性', 3 => 'その他'];
        $contact->gender_text = $genders[$contact->gender];

        return response()->json($contact);
    }

    public function destroy($id){
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect('/admin')->with('success', '削除しました');
    }


}

