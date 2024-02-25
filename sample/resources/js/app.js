$(function() {
	$(".add1").click(function() {
		var url = '{{ url("/item_input") }}';
		$(".insert-form td").remove();
		$(".insert-form").append($('<td></td><td><input type="text" name="item-name" class="item-name"/></td><td>'
		+'<input type="number" name="zaiko" class="zaiko"/></td><td>'
		+'<input type="button" id="insert{{ $item->id }}" onClick="insert(\''+url+'\',{{ $item->id }})" value="登録"/></td><br/>')); 
	});
});