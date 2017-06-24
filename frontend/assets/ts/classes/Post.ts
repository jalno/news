import List from "./Post/List";
import Managements from "./Post/Managements";
export default class Post{
	public static initIfNeeded(){
		List.initIfNeeded();
		Managements.initIfNeeded();
	}
	
}