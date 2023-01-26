@extends('layouts.app')

@section('content')
<div data-options="region:'center'" title="" style="overflow:auto;padding:1px">
	<table id="dg" class="easyui-datagrid" style="width:auto;height:500px"
		data-options="idField:'id', toolbar:'#toolbar', url:'{{ url()->current() }}/get_data', queryParams:{'_token':'{{ csrf_token() }}'},
            pagination:true, rownumbers:true, fitColumns:true, singleSelect:true">
		<thead>
			<tr>
				<th field="ck" checkbox="true"></th>
                <th field="logo">Logo</th>
				<th field="name" width="150" sortable="true">Name</th>
                <th field="categories">Categories</th>
				<th field="review_count" sortable="true">Count</th>
				<th field="star_rating" sortable="true">Rating</th>
				<th field="price" sortable="true">Price</th>
                <th field="latlon">LatLon</th>
                <th field="display_address">Address</th>
                <th field="phone">Phone</th>
                <th field="distance">Distance</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<div style="margin-bottom:5px">
			<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="newData()"><i class="fas fa-plus"></i> New Data</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="editData()"><i class="fas fa-edit"></i> Edit Data</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="destroyData()"><i class="fas fa-minus"></i> Remove Data</a>
		</div>
		<div class="search-box">
			<table>
				<tr>
					<td>Name</td>
					<td><?=easyui_textbox(['id'=>'sname','name'=>'sname'])?></td>
					<td>Distance</td>
					<td><?=easyui_numberbox(['id'=>'sdistance','name'=>'sdistance'])?> m.</td>
                    <td>Categories</td>
					<td><?=easyui_category(['id'=>'scategory_id','name'=>'scategory_id'])?></td>
					<td align="right">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="doSearch()"><i class="fas fa-search"></i> Search</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="clearSearch()"><i class="fas fa-sync"></i> Reset</a>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="dlg" class="easyui-dialog" style="width:800px;height:670px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
    <input type="hidden" class="txt_csrfname" name="_token" value="{{ csrf_token() }}" />
		<form id="fm" method="POST" novalidate>
        <div class="fitem">
				<label>Logo:</label>
				<?=easyui_textbox(['name'=>'image_url'])?>
			</div>
			<div class="fitem">
				<label>Name:</label>
				<?=easyui_textbox(['name'=>'name','required'=>true])?>
			</div>
            <div class="row">
            @foreach($categories as $category)
                <div class="col-3">
                    <input class="easyui-checkbox" name="categories_{{clearstr($category['alias'])}}" value="1" label="{{$category['title']}}">
                </div>
            @endforeach
            </div>
            <div class="fitem">
				<label>Price:</label>
				<?=easyui_moneybox(['name'=>'price'])?>
			</div>
			<div class="fitem">
				<label>Review Count:</label>
				<?=easyui_numberbox(['name'=>'review_count'])?>
			</div>
            <div class="fitem">
				<label>Rating:</label>
				<?=easyui_numberbox(['name'=>'rating'])?>
			</div>
            <hr/>
            <strong>Coordinate</strong>
            <br/>
            <div class="fitem">
				<label>Latitude:</label>
				<?=easyui_textbox(['name'=>'latitude'])?>
            </div>
            <div class="fitem">
				<label>Longitude:</label>
				<?=easyui_textbox(['name'=>'longitude'])?>
            </div>
            <hr/>
            <strong>Address</strong>
            <br/>
            <div class="fitem">
				<label>Address 1:</label>
				<?=easyui_textarea(['name'=>'address1'])?>
            </div>
            <div class="fitem">
				<label>Address 2:</label>
				<?=easyui_textarea(['name'=>'address2'])?>
            </div>
            <div class="fitem">
				<label>City:</label>
				<?=easyui_textbox(['name'=>'city'])?>
            </div>
            <div class="fitem">
				<label>Zipcode:</label>
				<?=easyui_textbox(['name'=>'zipcode'])?>
            </div>
            <div class="fitem">
				<label>Country:</label>
				<?=easyui_textbox(['name'=>'country'])?>
            </div>
            <div class="fitem">
				<label>State:</label>
				<?=easyui_textbox(['name'=>'state'])?>
            </div>
            <div class="fitem">
				<label>Phone:</label>
				<?=easyui_textbox(['name'=>'phone'])?>
            </div>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveData()">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
	</div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/metro/easyui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.easyui.min.js') }}" defer></script>
<script type="text/javascript">
    var csrfName = $('.txt_csrfname').attr('name');
    var csrfHash = $('.txt_csrfname').val();
    var url;
    var method;
    var id;
    function doSearch(){
        $('#dg').datagrid('load',{
            [csrfName]: csrfHash,
            sname: $('#sname').val(),
            sdistance: $('#sdistance').val(),
            scategory_id: $('#scategory_id').combobox('getValue'),
        });
    }
    function clearSearch(){
        location.reload();
    }
    function newData(){
        $('#dlg').dialog('open').dialog('setTitle','New <?=$title?>');
        $('#fm').form('clear');
        url = '{{ url()->current() }}';
        method='POST';
        id=null;
    }
    function editData(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $('#dlg').dialog('open').dialog('setTitle','Edit <?=$title?>');
            console.log(row);
            $('#fm').form('load',row);
            url = '{{ url()->current() }}/'+row.ck;
            method='PUT';
        }
    }
    function saveData(){
        var result;
        $('#fm').form('submit',{
            url: url,
            onSubmit: function(param){
                param[csrfName]=csrfHash;
                if(method=='PUT'){
                    param._method='PUT';

                }
                return $(this).form('validate');
            },
            success: function(result){
                result = eval('('+result+')');
                if (result.success===false){
                    var message="";
                    Object.values(result.errorMessages).forEach(val => {
                        Object.values(val).forEach(subval => {
                            message+=subval+"<br>";
                        });
                    });
                    $.messager.show({
                        title: 'Error',
                        msg: message
                    });
                } else {
                    $('#dlg').dialog('close');
                    $('#dg').datagrid('reload');
                }
            }
        });
    }
    function destroyData(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Are you sure you want to destroy this <?=$title?>?',function(r){
                if (r){
                    $.post('{{ url()->current() }}/'+row.id,{_method:'DELETE',[csrfName]:csrfHash},function(result){
                        if (result.success){
                            $('#dg').datagrid('reload');
                        } else {
                            $.messager.show({
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        }
                    },'json');
                }
            });
        }
    }
</script>
@endsection
