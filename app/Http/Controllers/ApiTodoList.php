<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TodoList;

class ApiTodoList extends Controller
{
    public function getList()
    {
        $result = TodoList::orderBy('id', "DESC")->get();
        return response()->json($result);
    }

    public function postCreate(Request $request)
    {
        $content = $request->content;
        TodoList::insert([
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil ditambahkan!'
        ]);
    }

    public function postUpdate(Request $request)
    {
        $content = $request->content;
        $id= $request->id;
        TodoList::where('id', $id)->update([
            'content' => $content,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil diupdate!'
        ]);
    }

    public function postDelete(Request $request)
    {
        TodoList::where('id', $request->id)->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    public function editList(Request $request)
    {
        $result = TodoList::where('id', $request->id)->first();
        return response()->json($result);
    }
}
