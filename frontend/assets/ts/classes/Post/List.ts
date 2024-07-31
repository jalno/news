import "../jquery.userAutoComplete";
export default class List{
	private static $form = $('#news-post-search');
	private static runUserSearch(){
		$('input[name=author_name]', List.$form).userAutoComplete();
	}
	public static init(){
		if($('input[name=author_name]', List.$form).length){
			List.runUserSearch();
		}
	}
	public static initIfNeeded(){
		if(List.$form.length){
			List.init();
		}
	}
}