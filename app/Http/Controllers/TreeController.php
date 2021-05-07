<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\RedirectResponse as HttpRedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use lluminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class TreeController extends Controller
{


    public function showTree(Request $request): View
    {

        if (!($request->session()->exists('order'))){

            Categories::setCategories();

        } else {

            Categories::setCategories($request->session()->get('order'));

        }

        Categories::createStructure();
        Categories::setParents();
        $htmlStructure = Categories::buildTree();

        return view('tree.tree', ['htmlStructure' => $htmlStructure]);

    }


    public function store(Request $request):HttpRedirectResponse
    {
        if ($request->input('confirmAdding')){
            $parentId = $request->input('idParent');
            $newCategoryName = $request->input('newCategoryName');
            Categories::create(['parent_id' => $parentId,
                                'text' => $newCategoryName]);

        } elseif ($request->input('confirmDel')) {
            $deleteElId = $request->input('deleteElement');
            Categories::deleteCategory((int) $deleteElId );

        } elseif ($request->input('save')){

            $editDataArray  = explode('|',$request->input('save'));
            $editElId = $editDataArray[0];
            $editText = $editDataArray[1];

            Categories::where('id',$editElId)
            ->update(['text' => $editText ]);

        } elseif ($request->input('acceptMove')) {

            $moveElId = $request->input('idMove');
            $moveToElId = $request->input('idMoveTo');

            Categories::find($moveElId)->update(['parent_id' => $moveToElId ]);

        } elseif ($request->input('sort')) {

            $session = $request->session();
            $session->put('order', $request->input('order'));

        }

        return redirect()->route('home');

    }
}
