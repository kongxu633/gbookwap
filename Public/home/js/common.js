$(function () {
  
  /*=== Popup ===*/
  $(document).on('click','.link',function () {
	data['photos'] = [];
	$(this).siblings("i").each(function(){
		data['photos'].push( $(this).text() );
	});
	if(data['photos'].length>0){
		$.photoBrowser(data).open();
	}
  });
  
  /*=== highlight ===*/
  $('.tab-item[href="'+current_url+'"]').addClass('active');
  
});