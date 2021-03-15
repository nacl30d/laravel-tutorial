<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Http\Requests\CreateFolder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function showCreateForm () {
        return view('folders/create');
    }

    public function create (CreateFolder $request) {
        // フォルダモデルのインスタンスを作成
        $folder = new Folder();
        $folder->title = $request->title;
        // データベースに書き込む
        $folder->save();

        return redirect()->route('tasks.index', [
            'id' => $folder->id,
        ]);
    }
}
