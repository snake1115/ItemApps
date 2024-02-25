<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../sample/resources/css/app.css">
    <title>消耗品管理</title>
</head>
 
<body>
<h1>消耗品管理</h1>
<form action="{{ url('item_search') }}" name="searchform" method="POST"  class="search_container">
	@csrf
    <input type = "text" name="searchname" class="search" size="25" placeholder="キーワードを入力"/>
	<input type = "submit" class="searchbtn" value=""/>
	<br />
</form>
<form action="" name="mainform" method="POST" enctype='multipart/form-data'>
	@csrf
 	<input type="hidden" name="delline" value =""/>
 	<input type="hidden" name="editline" value =""/>
 	@if (!empty($error))
 		<div name="error"><font color="red">{{ $error }}</font></div>
 	@endif
 	 @if (!empty($message))
 	 	<div name="message"><font color="blue">{{ $message }}</font></div>
 	 @endif
 	 <div class="plusminus">
 	  	 <img onClick="add()" src="../../sample/resources/image/plus.png" width="25" height="25" alt="追加" />
 	  	 <img onClick="exclude()" src="../../sample/resources/image/minus.png" width="25" height="25" alt="除外" />
 	 </div>
     @if ($item_lists->isNotEmpty())
     <p>検索件数　{{ $item_count }}件</p>
     <table id="tbl" cellspacing="0" cellpadding="5" >
         <thead>
         	<th>id</th>
         	<th>画像</th>
         	<th>品名</th>
         	<th>個数</th>
         	<th>最低個数</th>
         	<th></th>
         	<th></th>
         </thead>
         <tbody class="tbl-body">
         	 @foreach ($item_lists as $item)
            <tr id="item_list{{ $item->id }}">
                 <td>{{ $item->id }}<input type="hidden" name="id{{ $item->id }}" id="id{{ $item->id }}" value ="{{ $item->id }}"/></td>
                 <td id="item-image-lavel{{ $item->id }}"><img src="../../sample/storage/app/{{ $item->itemimage }}" width="100" height="100" alt="商品画像"/></td>
                 <td id="item-name-lavel{{ $item->id }}">{{ $item->name }}<input type="hidden" name="item-name" id="item-name{{ $item->id }}" value ="{{ $item->name }}"/></td>
                 <td id="zaiko-lavel{{ $item->id }}">{{ $item->zaiko }}<input type="hidden" name="zaiko" id="zaiko{{ $item->id }}" value ="{{ $item->zaiko }}"/></td>
                 <td id="minzaiko-lavel{{ $item->id }}">{{ $item->minzaiko }}<input type="hidden" name="minzaiko" id="minzaiko{{ $item->id }}" value ="{{ $item->minzaiko }}"/></td>
                 <td id="edit-lavel{{ $item->id }}"><img id="edit{{ $item->id }}" src="../../sample/resources/image/pencil.png" onClick="edit({{ $item->id }},'{{ $item->name }}',{{ $item->zaiko }},{{ $item->minzaiko }})" width="15" height="15" alt="編集" /></td>
                 <td id="delete-lavel{{ $item->id }}"><img id="delete{{ $item->id }}" src="../../sample/resources/image/scissors.png" onClick="del('{{ url('/item_del') }}',{{ $item->id }})" width="15" height="15" alt="削除" /></td>
             </tr>
             @endforeach
             <tr id="insert-form"></tr>
         </tbody>
     </table>
     @elseif ($item_lists->isEmpty())
     <p>検索件数　{{ $item_count }}件</p>
     <table id="tbl" cellspacing="0" cellpadding="5" >
         <thead>
         	<th>id</th>
         	<th>画像</th>
         	<th>品名</th>
         	<th>個数</th>
         	<th>最低個数</th>
         	<th></th>
         	<th></th>
         </thead>
       </table>
    @endif
</form>
 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 <script src="../../sample/resources/js/app.js"></script>
 <script>
  	function add(){
  		var url = '{{ url("/item_input") }}';
  		document.getElementById("insert-form").innerHTML='<td></td><td><input type="file" name="item-image"></td><td><input type="text" name="item-name" class="item-name"/></td><td>'
  	  		+'<input type="number" name="zaiko" class="zaiko"/></td><td>'
  	  		+'<img src="../../sample/resources/image/check.png" onClick="insert(\''+url+'\')" width="15" height="15" alt="登録" />';
  	  		+'<td></td>';
  	}
  	function exclude(){
  	  	document.getElementById("insert-form").innerHTML='';
	}
  	function insert(url){
    	document.mainform.action = url;
    	document.mainform.submit();
	}
    function del(url,delline){
        alert("削除しますか？");
    	document.mainform.action = url;
    	document.mainform.delline.value = delline;
    	document.mainform.submit();
	}
    function edit(editline,name,zaiko,minzaiko){
        var url = '{{ url("/item_edit") }}';
        document.getElementById("item-image-lavel"+editline).innerHTML='<input type="file" name="item-image'+editline+'" '
    	+ 'id="item-name'+editline+'"/>';
    	document.getElementById("item-name-lavel"+editline).innerHTML='<input type="text" name="item-name'+editline+'" '
        	+ 'id="item-name'+editline+'" value ="'+name+'"/>';
    	document.getElementById("zaiko-lavel"+editline).innerHTML='<input type="number" name="zaiko'+editline+'" '
        	+ 'id="zaiko'+editline+'" value ="'+zaiko+'"/>';
       	document.getElementById("minzaiko-lavel"+editline).innerHTML='<input type="number" name="minzaiko'+editline+'" '
        	+ 'id="minzaiko'+editline+'" value ="'+minzaiko+'"/>';
        document.getElementById("edit-lavel"+editline).innerHTML='<img src="../../sample/resources/image/check.png" onClick="update(\''+url+'\','+editline+')" width="15" height="15" alt="登録" />';
    	document.getElementById("delete-lavel"+editline).innerHTML='';
	}
    function update(url,editline){
    	document.mainform.action = url;
    	document.mainform.editline.value = editline;
    	document.mainform.submit();
	}
 </script>
</body>
</html>