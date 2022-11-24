<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::paginate(10);

        return view('permissions.index', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'guard_name' => 'required'
            ]);
    
            Permission::create($request->all());

            DB::commit();
            return redirect()->route('permissions.index')->with('success','Permissions created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('permissions.add')->with('error',$th->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::whereId($id)->first();

        return view('permissions.edit', ['permission' => $permission]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'guard_name' => 'required'
            ]);
            
            $permission = Permission::whereId($id)->first();

            $permission->name = $request->name;
            $permission->guard_name = $request->guard_name;
            $permission->save();
            
            
            DB::commit();
            return redirect()->route('permissions.index')->with('success','Permissions updated successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('permissions.edit',['permission' => $permission])->with('error',$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
    
            Permission::whereId($id)->delete();
            
            DB::commit();
            return redirect()->route('permissions.index')->with('success','Permissions deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('permissions.index')->with('error',$th->getMessage());
        }
    }
}
